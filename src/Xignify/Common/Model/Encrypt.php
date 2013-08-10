<?php
namespace Xignify\Common;

class Model_Encrypt {

	private $salt	= null;

	private $key64 = null, $key448 = null, $key512 = null;

	private $cipher		= null;

	public function __construct( $key64, $key256, $key512, $salt = "__default__" ) {

		$this->key64	= $key64;
		$this->key256	= $key256;
		$this->key512	= $key512;
		$this->salt		= $salt;
	}

	function encoding( $plainData ) {

		$data = serialize($plainData);
		$key = $this->key256;

		if (function_exists("mcrypt_encrypt")) {
			if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
			$padding = 16 - (strlen($data) % 16);
			$data .= str_repeat(chr($padding), $padding);
			$data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
		}
		
		return base64_encode($data);
	}

	function decoding($encryptedData) {

		$data = base64_decode( $encryptedData );
		$key = $this->key256;

		if (function_exists("mcrypt_encrypt")) {
			if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
			$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
			$padding = ord($data[strlen($data) - 1]); 
			$ret = substr($data, 0, -$padding); 
		}
		$ret = @unserialize($ret);		
		if ($ret === false) {
			return false;
		}
		return $ret;
	}

	function password($pw) {
		$pw = $this->salt.$pw .$this->salt;
		for ($i = 0; $i < 300; $i++) {
			$pw = sha1($pw);
		}
	 	$pbin = $pw;
	 	$new = array();
	 	for($i = 0; $i < strlen($pbin); $i++) {
	 		$j = (int)($i/3);
	 		if ( !isset($new[$j]) ) $new[$j] = 0;
			$new[$j] += pow(16, ($i % 3)) * hexdec($pbin[$i]);
		}
		for ($i = 0; $i < count($new); $i++) {
			$new[$i] = $this->key512[(int)($new[$i] / 64)].$this->key512[$new[$i] % 64];
		}

	 	return implode("", $new);
	}


}