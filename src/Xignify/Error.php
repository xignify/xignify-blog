<?php
namespace Xignify;

class Error {

	public static function page( $number, $msg = null, $config = array() ) {
		switch ($number) {
			case 404 :
				echo "404error, page not found.";
				break;
		}
		exit;
	}
}