<?php
function args_init( &$args, $value = 0 ) {
	if ( count($args) == 0 ) { array_push( $args, $value ); }
}


function array_extend( &$default, $added ) {
	//p($added);
	if (!is_array($added)) {
		$default = $added;
		return;
	}
	foreach($added as $k => $v) {
		if ( is_array($v) ) {
			array_extend($default[$k], $v);
		}
		else {
			$default[$k] = $v;
		}
	}
}

/*
 * go( url:string )
 * go location.
 *
 **/

function go( $url ) {
	header("Location:".$url);exit;
}



/*
 * p( val:mixed )
 * view print_r
 *
 **/

function p( $val ) {
	echo "<pre>".str_replace(array("<", ">"), array("&lt;", "&gt;"), print_r($val, true))."</pre>";
}



/*
 * caseChange( input:int, output:int, text:string ):string
 * text를 input타입으로 받았을 때, output으로 처리합니다.
 *
 **/
/*
define("CASE_CAMEL", 1);
define("CASE_PASCAL", 2);
define("CASE_UNDERBAR", 3);
define("CASE_SPACE", 4);
define("CASE_DOT", 5);
define("CASE_SLASH", 6);
define("CASE_ESCAPE", 7);

function caseChange( $input, $output, $text ) {
	switch($input) {
		case CASE_CAMEL :
		case CASE_PASCAL :
			$changer = explode(":", preg_replace("/([A-Z])/", ":$1", $text));
			break;
		case CASE_UNDERBAR :
			$changer = explode("_", $text);
			break;
		case CASE_SPACE :
			$changer = explode(" ", $text);
			break;
		case CASE_DOT :
			$changer = explode(".", $text);
			break;
		case CASE_SLASH :
			$changer = explode("/", $text);
			break;
		case CASE_ESCAPE :
			$changer = explode("\\", $text);
			break;
	}
	
	$o = array();
	foreach ($changer as $value) if ($value != "") array_push($o, strtolower($value));
	
	switch($output) {
		case CASE_CAMEL :
			foreach($o as $key => $value) if ($key != 0) $o[$key] = ucwords($value);
			return implode("", $o);
		case CASE_PASCAL :
			foreach($o as $key => $value) $o[$key] = ucwords($value);
			return implode("", $o);
		case CASE_UNDERBAR :
			return implode("_", $o);
		case CASE_SPACE :
			return implode(" ", $o);
		case CASE_DOT :
			return implode(".", $o);
		case CASE_SLASH :
			return implode("/", $o);
		case CASE_ESCAPE :
			return implode("\\", $o);
	}
}


*/
/*
 * _unserialize( context:string ):mixed
 * context를 받아서 unserialize 하는데, null은 제거한다.
 *
 **/
function _unserialize( $context ) {
	
	if ( is_null($context) || $context == "" ) return null;
	
	$ret = unserialize($context);
	
	if ($ret === false) return null;
	
	foreach($ret as $k => $v) { if ( is_null($v) ) unset($ret[$k]); }
	
	if ( count($ret) == 0 ) return null;

	return $ret;
	
}


