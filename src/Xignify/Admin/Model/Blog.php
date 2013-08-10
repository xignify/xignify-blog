<?php
namespace Xignify\Admin;

use \Xignify\Config;
use \Xignify\Model;

class Model_Blog {

	public function __construct() {

		$this->admin	= Model::load("Xignify\\Admin\\Model_Init");
		$this->db		= Model::factory("Xignify\\Common\\Model_Pdo")->singleton();
		$this->input	= Model::factory("Xignify\\Common\\Model_Input")->singleton();
		$this->board	= Model::factory("Xignify\\Common\\Model_Board")->singleton();
		$this->file		= Model::load("Xignify\\Common\\Model_Files");
	}

	public function blogList() {
		$page = $this->input->get("page", 1);
		$len_line = 20;

		$len = $this->db->query("select count(*) as len from `{@prefix}blog_data`")->fetchColumn(0);
		$rows = $this->db->query('
				select 
					`data`.`idx` as `idx`,
					`data`.`writer` as `writer`,
					`data`.`subject` as `subject`,
					`category`.`name` as `category` ,
					`data`.`reg_date` as `reg_date`
				from `{@prefix}blog_data` as `data`
				left outer join `{@prefix}blog_category` as `category`
				on `data`.`category_idx` = `category`.`idx`
				order by `data`.`idx` desc
				limit '.($page-1)*$len_line.', '.$len_line
			)->fetchAll();

		$cnt = count($rows);
		for ($i = 0; $i < $cnt; $i++) { $rows[$i]['n'] = $len - $i - ($page-1)*$len_line; }
		unset($cnt);

		return array(
			"href" => HOME . "/admin",
			"id"	=> "blog",
			"rows" => $rows,
			"pagination" => $this->board->makePagination($len, array('page' => $page, 'page_link' => HOME . "/admin/blog"))
		);
	}
	public function blogNew() {
		$member = $this->admin->getMember();
		$member = $member['id'];

		$stmt = $this->db->prepare('select `nick` from {@prefix}member where `id`=:id');
		$stmt->execute(array(
			':id' => $member
		));
		$result = $stmt->fetch();

		return array(
			"href" => HOME . "/admin",
			"id"	=> "blog",
			"result"	=> array(
				"files"		=> array(),
				"writer"	=> $result['nick']
			),
			"category"	=> $this->db->query('select * from {@prefix}blog_category')->fetchAll(),
			"action"	=> "new"
		);
	}
	public function imageUpload() {

		$this->file->upload();

		$ret = array();
		
		$ret['url'] = $this->file->getUrl('fileupload');
		$ret['data'] = $this->file->getData('fileupload');

		\Xignify\View::header("ajax");

		echo json_encode($ret);
		exit;
	}

	public function imageResize() {



	}


}