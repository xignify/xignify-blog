<?php
namespace Xignify;

//Factory Class
abstract class Model {

	public static function factory( $name ) {
		static $ins;

		if ( !isset($ins[$name]) ) { $ins[$name] = new ModelFactory($name); }
		return $ins[$name];

	}

	public static function load( $name ) {
		return Model::factory($name)->singleton();
	}

}