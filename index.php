<?php
namespace Xignify;

define("__ROOT__", __DIR__);

$loader = require "vendor/autoload.php";

date_default_timezone_set("Asia/Seoul");

switch( $args[0] ) {
	case "." :
		$fa = Controller::factory("Xignify\\Blog\\Controller_Init");
		break;
	case "view" :
		$fa = Controller::factory("Xignify\\Blog\\Controller_View");
		break;
	case "admin" :
		$fa = Controller::factory("Xignify\\Admin\\Controller_Init");
		break;
	case "tools" : 
		$fa = Controller::factory("Xignify\\Tools\\Controller_Init");
		break;
	case "image" :
		$fa = Controller::factory("Xignify\\Image\\Loader");
		break;
	default :
		Error::page(404);
}

$fa->next( $args );

exit;