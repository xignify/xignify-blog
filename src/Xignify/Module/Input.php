<?php
namespace Xignify\Module;

class Input {

	public static function esc( $var ) {
		
		if ( is_array($var) ) {
			foreach( $var as $key => $value )
				$var[$key] = $this->esc($value);
			return $var;
		}
		else return stripslashes($var);
	}

	public static function post( $name, $def = null ) {
		if ( !isset($_POST[$name]) ) return $def;
		if ( get_magic_quotes_gpc() ) return self::esc($_POST[$name]);
		else return $_POST[$name];
	}

	public static function get( $name, $def = null  ) {
		if ( !isset($_GET[$name]) ) return $def;
		if ( get_magic_quotes_gpc() ) return self::esc($_GET[$name]);
		else return $_GET[$name];
	}

	public static function request( $name, $def = null  ) {
		if ( !isset($_REQUEST[$name]) ) return $def;
		if ( get_magic_quotes_gpc() ) return self::esc($_REQUEST[$name]);
		else return $_REQUEST[$name];
	}

	public static function postExists( $name = null ) {
		$ret = false;
		if ( is_null($name) ) {
			if ( isset($_POST) && count($_POST) != 0 ) $ret = true;
		}
		else {
			if ( isset($_POST[$name]) && $_POST[$name] !== "" ) $ret = true;
		}
		return $ret;
	}

	public static function getExists( $name = null ) {
		$ret = false;
		if ( is_null($name) ) {
			if ( isset($_GET) && count($_GET) != 0 ) $ret = true;
		}
		else {
			if ( isset($_GET[$name])  && $_GET[$name] !== "" ) $ret = true;
		}
		return $ret;
	}

	public static function requestExists( $name = null ) {
		$ret = false;
		if ( is_null($name) ) {
			if ( isset($_REQUEST) && count($_REQUEST) != 0 ) $ret = true;
		}
		else {
			if ( isset($_REQUEST[$name])  && $_REQUEST[$name] !== "" ) $ret = true;
		}
		return $ret;
	}


}