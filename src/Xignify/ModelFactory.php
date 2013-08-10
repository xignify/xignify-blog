<?php
namespace Xignify;

//Factory Class
class ModelFactory {

	public static function factory( $name ) {
		static $ins;

		if ( !isset($ins[$name]) ) { $ins[$name] = new Model($name); }
		return $ins[$name];

	}
	

	private $name = "None";
	private $singleton_instance = array();

	public function __construct( $name ) {
		$this->name = $name;
	}

	// @import : *args
	public function make() {
		$args = func_get_args();
		$rc = new \ReflectionClass( $this->name );
		return $rc->newInstanceArgs( $args );
	}

	// not use construct vars
	public function singleton( $identifier = 1 ) {
		if ( !isset($this->singleton_instance[$identifier]) ) {
			$this->singleton_instance[$identifier] = new $this->name();
		}
		return $this->singleton_instance[$identifier];
	}

}