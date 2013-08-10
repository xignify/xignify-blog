<?php
namespace Xignify;

// Factory Class
abstract class Controller {

	public static function factory( $name ) {
		static $ins;

		if ( !isset( $ins[$name] ) ) { $ins[$name] = new ControllerFactory($name); }
		return $ins[$name];

	}
	
	/* instance */

	protected $args = array();

	final public function __construct() {
	}

	final public function setArgs( $args ) {
		$this->args = $args;
	}

	final public function getArgs() {
		return $this->args;
	}

	public function __error() {
		\Xignify\Error::page(404);
	}

}