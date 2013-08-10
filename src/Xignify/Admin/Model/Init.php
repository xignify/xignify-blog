<?php
namespace Xignify\Admin;

use \Xignify\Model;
use \Xignify\Exception\NotInitializeException;


/*
 |
 | Dependency : 
 |	- Xignify\Common\Model_Encrypt
 |	- Xignify\Common\Model_Input
 |
 |
 |
 */
class Model_Init extends Model{


	private $root = array();

	private $blowfish = "";

	private $encrypt = null;


	public function __construct() {
		session_start();
	}



	public function setConfig( $config ) {
		$this->root		= $config['root'];
		$this->blowfish = $config['salt'];
		$this->encrypt = Model::factory("Xignify\\Common\\Model_Encrypt")
				->make($config['key64'], $config['key256'], $config['key512'], $config['salt']);
	}



	public function auth() {
		if ( !isset($this->encrypt) ) throw new NotInitializeException();

		$member = $this->getMember();
		
		if ( isset( $member) ) return true;
		return false;

	}	

/*
	public function getUsers() {
		$ret = array();
		foreach($this->users as $k => $v) {
			array_push($ret, $v['id']);
		}
		return $ret;
	}
*/
	public function getMember() {
		if ( !isset($this->encrypt) ) throw new NotInitializeException();
		
		static $ret;
		
		if ( isset($ret) ) return $ret;
		
		if ( !isset($_SESSION[$this->blowfish."_auth"]) ) return null;
		
		$member = $this->encrypt->decoding( $_SESSION[$this->blowfish."_auth"] );
		
		if ( $member !== false ) $ret = $member;
		else $ret = null;
		
		return $ret;
	}

	function login() {

		if ( !isset($this->encrypt) ) throw new NotInitializeException();

		$input = Model::factory("Xignify\\Common\\Model_Input")->singleton();
		$id			= $input->post('id');
		$password	= $input->post('password');

		$ret = false;
		$member = array();
		
		foreach( $this->root as $user ) {
			if ( $user['id'] == $id && $user['password'] == $this->encrypt->password($password) ) {
				$ret = true;
				break;
			}
		}
		
		if ( $ret ) {
			$member['id'] = $id;
			$member['log_date'] = time();
			$_SESSION[$this->blowfish."_auth"] = $this->encrypt->encoding( $member );
		}
		else {
			$this->logout();
		}

		return $ret;
	}
	function logout() {
		$_SESSION[$this->blowfish."_auth"] = null;
		return true;
	}


}