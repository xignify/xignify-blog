<?php
namespace Xignify\Admin;

use \Xignify\View;

class View_Blog {
	
	public function __construct() {
		$this->view_init	= View::load("Xignify\\Admin\\View_Init");
	}

	public function blogList( $variables ) {

		$this->view_init->header(array(
			"css_files" => array(
				"library/admin/wandu/css/main.css"
			)
		));
		View::html("library/admin/blog/list", $variables );
		$this->view_init->footer();

	}

	public function blogNew( $variables ) {

		$this->view_init->header(array(
			"css_files" => array(
				"library/admin/blog/css/main.css",
				"library/admin/blog/css/mdeditor.css"
			)
		));
		View::html("library/admin/blog/new", $variables );
		$this->view_init->footer(array(
			"js_files" => array(
				"submodule/markdown-js/lib/markdown.js",
				"library/admin/blog/js/jquery.form.min.js",
				"library/admin/blog/js/jquery.mdeditor.js",
				"library/admin/blog/js/blog.js"
			)
		));


	}
}