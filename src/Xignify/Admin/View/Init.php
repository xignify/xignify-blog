<?php
namespace Xignify\Admin;

use \Xignify\View;

class View_Init {

	public function login() {

		View::html("library/common/header", array(
			"title" => "Tools",
			"css_files" => View::css(
				"components/bootstrap/css/bootstrap.min.css",
				"library/common/css/bootstrap-wandu.css",
				"library/admin/init/css/login.css"
			)
		));
		View::html("library/admin/init/login", array(
			"action" => HOME."/admin/login"
		));
		View::html("library/common/footer", array(
			"js_files" => View::js(
				"components/modernizr/modernizr.js",
				"components/jquery/jquery.min.js",
				"library/admin/init/js/login.js"
			)
		));

	}
	public function header( $values = array() ) {

		View::html("library/common/header", array(
			"title" => "Wandu Administrator",
			"css_files" => View::css(
				"components/bootstrap/css/bootstrap.min.css",
				"components/bootstrap/css/bootstrap-responsive.min.css",
				"components/font-awesome/css/font-awesome.min.css",
				"library/common/css/bootstrap-wandu.css"
			). ( isset($values['css_files']) ? "\r\n".View::css( $values['css_files']) : "" )
		));
		View::html("library/admin/init/header", array(
			"href" => HOME."/admin"
		));
	}
	public function footer( $values = array() ) {
		View::html("library/admin/init/footer");
		View::html("library/common/footer", array(
			"js_files" => View::js(
				"components/modernizr/modernizr.js",
				"components/jquery/jquery.min.js",
				"components/bootstrap/js/bootstrap.min.js"
			). ( isset($values['js_files']) ? "\r\n".View::js( $values['js_files']) : "" )
		));
	}
}