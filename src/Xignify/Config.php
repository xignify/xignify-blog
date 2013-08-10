<?php
namespace Xignify;

class Config {

	public static function fromPhp( $name ) {
		static $config = array();
		if ( !isset($config[$name]) ) {
			$config[$name] = require __ROOT__.'/config/'.$name.'.php';
		}
		return $config[$name];
	}
	public static function fromJson( $name ) {
		static $config = array();
		if ( !isset($config[$name]) ) {
			$config[$name] = json_decode( require __ROOT__.'config/'.$name.'.json' );
		}
		return $config[$name];
	}

}