<?php
namespace Xignify\Admin;

use \Xignify\Config;
use \Xignify\Model;
use \Xignify\Error;

use \Xignify\Module\Input;
use \PHPImageWorkshop\ImageWorkshop;

class Model_Blog {

	public function __construct() {

		$this->admin	= Model::load("Xignify\\Admin\\Model_Init");
		$this->db		= Model::factory("Xignify\\Common\\Model_Pdo")->singleton();
		$this->board	= Model::factory("Xignify\\Common\\Model_Board")->singleton();
		$this->file		= Model::load("Xignify\\Common\\Model_Files");
	}

	public function blogList() {
		$page = Input::get("page", 1);
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

	public function blogView( $idx ) {

		if ($idx === 0) Error::box('잘못된 접근입니다!('.__FILE__.':'.__LINE__.')');

		$this->variables['category'] = $this->db->query("select * from {@prefix}blog_category")->fetchAll();

		$stmt = $this->db->prepare("select * from `{@prefix}blog_data` where `idx`= ? limit 0, 1");
		$stmt->execute(array($idx));
		$result = $stmt->fetch();
		
		if ( $result['files'] == "" ) $result['files'] = array();
		else {
			$result['files'] = explode(',', $result['files']);
		}

		return array(
			"href"	=> HOME ."/admin",
			"id"	=> "blog",
			"result" => $result,
			"category"	=> $this->db->query('select * from {@prefix}blog_category')->fetchAll(),
			"action"	=> "modify"
		);

	}
	public function blogNewProcess() {

		if ( Input::post("action") != "new" ) {
			Error::alert("Not New!!");
		}

		$category_idx = (int)Input::post("category_idx");
		
		
		$writer = Input::post("writer");
		$subject = Input::post("subject");
		$markdown = Input::post("markdown");
		$contents = Input::post("contents");

		$files = implode(",", Input::post("files", array()));

		$this->file->upload();
		$thumbnail = $this->file->getData();
		$thumbnail = ( isset($thumbnail['thumbnail'][0]) )?$thumbnail['thumbnail'][0]:Input::post("thumbnail");

		try {
			$stmt = $this->db->prepare("
					insert into `{@prefix}blog_data` (`category_idx`, `member_idx`, `writer`, `password`, `subject`, `contents`, `markdown`, `files`, `thumbnail`, `ip`, `reg_date`)
					values (:category_idx, :member_idx, :writer, :password, :subject, :contents, :markdown, :files, :thumbnail, :ip, :reg_date)");

			$return = @$stmt->execute(array(
				':category_idx' => $category_idx,
				':member_idx' => 0,
				':writer' => $writer,
				':password' => sha1(1),
				':subject' => $subject,
				':contents' => $contents,
				':markdown' => $markdown,
				':thumbnail' => $thumbnail,
				':files' => $files,
				':ip' => $_SERVER['REMOTE_ADDR'],
				':reg_date' => time()
			));

			if ( !$return ) {
				Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");
			}
		}
		catch( \PDOException $e ) {
			Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");	
		}

	}
	public function blogModifyProcess() {

		$idx = Input::post("idx");
		if ($idx === 0) Error::box('잘못된 접근입니다!('.__FILE__.':'.__LINE__.')');
	
		$category_idx = (int)Input::post("category_idx");
		$subject = Input::post("subject");
		$markdown = Input::post("markdown");
		$contents = Input::post("contents");

		$files = implode(",", Input::post("files", array()));

		$this->file->upload();
		$thumbnail = $this->file->getData();
		$thumbnail = ( isset($thumbnail['thumbnail'][0]) )?$thumbnail['thumbnail'][0]:Input::post("thumbnail");

		$delete_thumbnail = Input::post("delete_thumbnail", 0);
		if ( $delete_thumbnail == 1 ) $thumbnail = "";


		try {
			$stmt = $this->db->prepare("
					update `{@prefix}blog_data`
					set
						`category_idx` = :category_idx,
						`subject` = :subject,
						`contents` = :contents,
						`markdown` = :markdown,
						`files` = :files,
						`thumbnail` = :thumbnail
					where `idx` = :idx");

			$return = @$stmt->execute(array(
				':category_idx' => $category_idx,
				':subject' => $subject,
				':contents' => $contents,
				':markdown' => $markdown,
				':files' => $files,
				':thumbnail' => $thumbnail,
				':idx' => $idx
			));

			if ( !$return ) {
				Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");
			}
		}
		catch( \PDOException $e ) {
			Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");	
		}

	}
	public function blogDeleteProcess() {

		$idx = Input::post("idx");
		if ($idx === 0) Error::box('잘못된 접근입니다!('.__FILE__.':'.__LINE__.')');
	
		try {
			$stmt = $this->db->prepare("
					delete from `{@prefix}blog_data`
					where `idx` = :idx");

			$return = @$stmt->execute(array(
				':idx' => $idx
			));

			if ( !$return ) {
				Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");
			}
		}
		catch( \PDOException $e ) {
			Error::alert("DB에 데이터를 쓰는 중 문제가 발생하였습니다!");	
		}
	}

	
	public function imageUpload() {
		ob_start();
		$this->file->upload();

		$ret = array();
		
		$ret['url'] = $this->file->getUrl('fileupload');
		$ret['data'] = $this->file->getData('fileupload');
		
		
		foreach( $ret['data'] as $image ) {

			$file = __ROOT__."/files/".$image;

			if ( file_exists( $file ) && is_file( $file ) ) {
				$exif = @exif_read_data( $file );
				if ( isset($exif) && isset($exif['Orientation']) ) {
					$layer = ImageWorkshop::initFromPath( $file );
					switch($exif['Orientation']) {
						case 2 :
							$layer->flip("horizontal");
							break;
						case 3 :
							$layer->rotate(180);
							break;
						case 4 :
							$layer->flip("vertical");
							break;
						case 5 :
							$layer->rotate(90);
							$layer->flip("horizontal");
							break;
						case 6 : 
							$layer->rotate(90);
							break;
						case 7 :
							$layer->rotate(-90);
							$layer->flip("horizontal");
							break;
						case 8 :
							$layer->rotate(-90);
							break;
					}
					$layer->save( __ROOT__."/files/", $image, true, null, 100);
				}
			}

			
		}
		ob_clean();
		\Xignify\View::header("ajax");

		echo json_encode($ret);
		exit;
	}

	public function imageResize() {



	}
}