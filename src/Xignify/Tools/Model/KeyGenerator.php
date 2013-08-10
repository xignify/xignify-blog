<?php
namespace Xignify\Tools;

class Model_KeyGenerator {

	private $key = null;

	private $path_config = null;

	function __construct( ) {
		$this->key = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&*()-_=+~";
	}
	function generate() {
		$result['key64'] = $this->_genkey(64/8);
		$result['key256'] = $this->_genkey(256/8);
		$result['key512'] = $this->_genkey(512/8);

		return $result;
	}

	function _genkey( $n ) {
		$key = "";
		$KL = strlen($this->key)-1;
		
		for ($i = 0; $i < $n; $i++) $key .= $this->key[rand(0, $KL)];
		return $key;
	}


	

}