<?php
namespace Xignify;

$loader = require "vendor/autoload.php";

define("__ROOT__", __DIR__);
define("HOME",  substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "/index.php")));

if ( isset( $_SERVER['PATH_INFO'] ) && $_SERVER['PATH_INFO'] !== "/" ) {
	$temp = explode("?", $_SERVER['PATH_INFO']);
	$args = explode( "/", trim($temp[0], "/") );
	if ( $args[0] == "" ) $args[0] = ".";
}
else {
	$args = array( "." );
}

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