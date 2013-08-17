<?php
namespace Xignify\Blog;

use \Xignify\Controller;
use \Xignify\View;
use \Xignify\Model;

use \dflydev\markdown\MarkdownExtraParser;

class Controller_View extends Controller {

	public function __init() {

		$this->db	= Model::load("Xignify\\Common\\Model_Pdo");
		$this->view = View::load("Xignify\\Blog\\View_Init");

	}
	public function __fromGet() {
		args_init($this->args, 0);

		if ($this->args[0] === 0) {
			go( HOME );
		}
		else {
			go( HOME ."/#!/view/" . $this->args[0] );
		}
		
		exit;

	}

	public function __fromPost() {
		args_init($this->args, 0);

		$ret = array(
			"action"	=> "view"
		);

		if ($this->args[0] == 0) {
			$ret["result"] = false;
		}
		$idx = $this->args[0];

		$stmt = $this->db->prepare("select * from `{@prefix}blog_data` where `idx`= ? limit 0, 1");
		$stmt->execute(array($idx));
		$ret["result"] = $stmt->fetch();
		$ret["result"]["reg_date"] = date("Y.m.d", $ret["result"]["reg_date"]);

		View::header("json");
		echo json_encode($ret);
		exit;

	}

}