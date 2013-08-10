<?php
namespace Xignify\Admin;

use \Xignify\Model;
use \Xignify\View;
use \Xignify\Controller;
use \Xignify\Config;
use \Xignify\Error;

class Controller_Init extends Controller {

	public function __init() {

		$config = Config::fromPhp("xignify-admin-init");

		$this->view = View::load("Xignify\\Admin\\View_Init");
		$this->model = Model::load("Xignify\\Admin\\Model_Init");
		$this->model->setConfig( $config );

		args_init( $this->args, "wandu" );
		
		if ( $this->args[0] == "logout" ) {
			$this->model->logout();
			if ( isset($_SERVER['HTTP_REFERER']) ) {
				go( $_SERVER['HTTP_REFERER'] );
			}
			else {
				echo "Logout Complete";
				exit;
			}
		}
	}

	public function __fromGet() {

		$args = $this->args;
		
		if ( !$this->model->auth() ) {
			$this->view->login();
			exit;
		}
		$starttime = microtime();//explode(" ", microtime());
		$contents = View::getCapture(function() use($args) {

			$kongs = Config::fromPhp("xignify-admin-kongs");

			$is_used_controller = false;

			foreach($kongs as $kong) {
				if ($args[0] == $kong['name']) {
					Controller::factory( $kong['controller'] )->next( $args );
					$is_used_controller = true;
					break;
				}
			}
			if ( !$is_used_controller ) {
				Error::page(404);
			}

		});

		$endtime = microtime();//explode(" ", microtime());
		
		$runtime = "runtime : " . ( $endtime - $starttime) . "(sec)";
		echo str_replace("{runtime}", $runtime, $contents);

	}

	public function __fromPost() {

		switch($this->args[0]) {
			case "login" :
				$result = $this->model->login();
				View::header("json");
				echo json_encode(array(
					'action' => "login",
					'result' => $result
				));
				exit;
			default :
				$kongs = Config::fromPhp("xignify-admin-kongs");

				$is_used_controller = false;

				if ( isset($kongs[ $this->args[0] ]['controller']) ) {
					Controller::factory( $kongs[ $this->args[0] ]['controller'] )->next( $this->args );
					$is_used_controller = true;
				}
				if ( !$is_used_controller ) {
					Error::page(404);
				}
		}
	}


}