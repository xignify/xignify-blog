<?php
namespace Xignify\Blog;

use \Xignify\View;

class View_Init {


	public function main( $variables ) {
		View::html("library/common/header", array(
			"title" => "Wandu Administrator",
			"css_files" => View::css(
				"components/bootstrap/css/bootstrap.min.css",
				"components/bootstrap/css/bootstrap-responsive.min.css",
				"components/font-awesome/css/font-awesome.min.css",
				"library/blog/css/main.css"
			)
		));
		View::html("library/blog/list", $variables );
		View::html("library/common/footer", array(
			"js_files" => View::js(
				"components/modernizr/modernizr.js",
				"components/jquery/jquery.min.js",
				"library/blog/js/jquery.wanimodal.js",
				"library/blog/js/jquery.easing.min.js",
				"library/blog/js/jquery.hashanalyzer.js",
				"library/blog/js/jquery.masonry.min.js",
				"library/blog/js/jquery.infinitescroll.min.js",
				"library/blog/js/blog-main.js"
			)
		));
	}
	
}