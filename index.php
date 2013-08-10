<?php
namespace Xignify;

$loader = require "vendor/autoload.php";

define("__ROOT__", __DIR__);
define("HOME",  substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "/index.php")));

// get args
$args = ( isset( $_SERVER['PATH_INFO'] ) && $_SERVER['PATH_INFO'] !== "/" ) ?
		explode( "/", trim($_SERVER['PATH_INFO'], "/") ) : args_init( $args, "blog" );

switch( $args[0] ) {
	case "blog" :
		$fa = Controller::factory("Xignify\\Blog\\Controller_Init");
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