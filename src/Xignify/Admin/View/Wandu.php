<?php
namespace Xignify\Admin;

use \Xignify\View;

class View_Wandu {
	public function __construct() {
		$this->view_init	= View::load("Xignify\\Admin\\View_Init");
	}
	public function main( $values ) {

		$this->view_init->header(array(
			"css_files" => array(
				"library/admin/wandu/css/main.css"
			)
		));
		View::html("library/admin/wandu/main", $values );
		$this->view_init->footer();

	}


}