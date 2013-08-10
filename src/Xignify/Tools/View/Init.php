<?php
namespace Xignify\Tools;

use \Xignify\View;

class View_Init {

	public function main() {

		View::html("library/common/header", array(
			"title" => "Tools",
			"css_files" => View::css(
				"components/bootstrap/css/bootstrap.min.css",
				"library/common/css/bootstrap-wandu.css",
				"library/tools/css/init-main.css"
			)
		));
		View::html("library/tools/init-main", array(
			"href_self" => HOME."/tools"
		));
		View::html("library/common/footer", array(
			"js_files" => ""
		));


	}

	public function generator( $config, $from_post_values = null ) {

		if (isset($from_post_values)) {
			$config['password_result'] = $from_post_values;
		}

		View::html("library/common/header", array(
			"title" => "Tools",
			"css_files" => View::css(
				"components/bootstrap/css/bootstrap.min.css",
				"library/common/css/bootstrap-wandu.css",
				"library/tools/css/generator.css"
			)
		));
		View::html("library/tools/generator", array(
			"href_self" => HOME."/tools"
		), $config);
		View::html("library/common/footer", array(
			"js_files" => ""
		));


	}

}