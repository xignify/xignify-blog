<?php
namespace Xignify\Tools;

use Xignify\View;
use Xignify\Model;
use Xignify\Controller;
use Xignify\Error;

class Controller_Init extends Controller {

	public function __fromGet() {
		args_init( $this->args, "main" );
		
		switch($this->args[0]) {
			case "main" :
				View::load("Xignify\\Tools\\View_Init")->main();	
				break;
			case "generator" :
				$this->_genetator();		
				break;
			default :
				Error::page(404);
		}

	}

	public function __fromPost() {

		args_init( $this->args, "main" );
		
		switch($this->args[0]) {
			case "generator" :
				$this->_generatorAction();		
				break;
			default :
				Error::page(404);
		}
	}

	public function _generatorAction() {

		$input = Model::factory("Xignify\\Common\\Model_Input")->singleton();

		$key64 = $input->post("key64");
		$key256 = $input->post("key256");
		$key512 = $input->post("key512");
		$salt	= $input->post("salt");

		$encrypt = Model::factory("Xignify\\Common\\Model_Encrypt")
				->make($key64, $key256, $key512, $salt);

		$this->_genetator( $encrypt->password($input->post("password")) );
	}

	private function _genetator( $from_post_values = null ) {

		$key_values = array();

		$input = Model::factory("Xignify\\Common\\Model_Input")->singleton();
		
		if ( $input->get("action") == "generator" ) {
			$key_generator = Model::factory("Xignify\\Tools\\Model_KeyGenerator")->singleton();
			$key_values = $key_generator->generate();
			$key_values['salt'] = substr(sha1(rand()), 0, 10);
		}
		else {
			$key_values = \Xignify\Config::fromPhp("xignify-admin-init");
		}
		View::load("Xignify\\Tools\\View_Init")->generator( $key_values, $from_post_values );

	}




}