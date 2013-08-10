<?php
namespace Xignify\Admin;

use \Xignify\Model;
use \Xignify\View;
use \Xignify\Controller;

class Controller_Blog extends Controller {

	public function __init() {

		date_default_timezone_set("Asia/Seoul");

		$this->model = Model::load("Xignify\\Admin\\Model_Blog");
		$this->view	= View::load("Xignify\\Admin\\View_Blog");
	}

	public function __fromGet() {

		args_init($this->args, "list");


		switch($this->args[0]) {
			case "list" : 
				$variables = $this->model->blogList();
				$this->view->blogList( $variables );
				break;
			case "new" : 
				$variables = $this->model->blogNew();
				$this->view->blogNew( $variables );
				break;
			case "test" :
				$this->model->imageResize();
				break;
			default :
		}



	}
	public function __fromPost() {

		args_init($this->args, "list");

		switch($this->args[0]) {
			case "upload" : 
				$variables = $this->model->imageUpload();
				break;
			case "new" : 
				$variables = $this->model->blogNew();
				$this->view->blogNew( $variables );
				
		}


	}
}