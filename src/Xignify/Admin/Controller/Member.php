<?php
namespace Xignify\Admin;

use \Xignify\Model;
use \Xignify\View;
use \Xignify\Controller;

class Controller_Member extends Controller {

	public function __init() {
		$this->model = Model::load("Xignify\\Admin\\Model_Wandu");
		$this->view	= View::load("Xignify\\Admin\\View_Wandu");
	}

	public function __fromGet() {

		args_init($this->args, "main");

		switch($this->args[0]) {
			case "main" : 
				$values = $this->model->main();
				$this->view->main( $values );
		}



	}
}