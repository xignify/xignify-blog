<?php
namespace Xignify;

// Factory Class
class ControllerFactory {

	private $name = "";
	private $instance = null;
	
	public function __construct( $name ) {
		$this->name = $name;
	}

	// @import : *args
	public function run( $args ) {
		if ( !isset($this->instance) ) {
			$this->instance = new $this->name();
		}
		$this->instance->setArgs( $args );
		$this->runMethod();
	}

	public function next( $args ) {
		if ( !isset($this->instance) ) {
			$this->instance = new $this->name();
		}
		array_shift( $args );
		$this->instance->setArgs( $args );
		$this->runMethod();
	}

	private function runMethod() {
		if ( method_exists($this->instance, "__init") ) {
			$this->instance->__init();
		}
		if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {
			if ( method_exists($this->instance, "__fromPost") ) {
				$this->instance->__fromPost();
			}
			else if ( method_exists($this->instance, "__fromRequest") ) {
				$this->instance->__fromRequest();
			}
			else {
				$this->instance->__error();
				exit;
			}
		}
		else {
			if ( method_exists($this->instance, "__fromGet") ) {
				$this->instance->__fromGet();
			}
			else if ( method_exists($this->instance, "__fromRequest") ) {
				$this->instance->__fromRequest();
			}
			else {
				$this->instance->__error();
				exit;
			}
		}		
	}



}