<?php
namespace Xignify\Blog;

use \Xignify\Controller;
use \Xignify\View;
use \Xignify\Model;

use \dflydev\markdown\MarkdownExtraParser;

class Controller_Init extends Controller {

	public function __init() {

		$this->db	= Model::load("Xignify\\Common\\Model_Pdo");
		$this->view = View::load("Xignify\\Blog\\View_Init");

		$this->parser = new MarkdownExtraParser();

	}

	public function __fromGet() {

		$page = \Xignify\Module\Input::get("page", 1);

		$stmt = $this->db->prepare("
			select
				`{@prefix}blog_data`.`idx` as `idx`, 
				`{@prefix}blog_data`.`subject` as `subject`, 
				`{@prefix}blog_category`.`name` as `category`,
				`{@prefix}blog_data`.`writer` as `writer`,
				`{@prefix}blog_data`.`markdown` as `markdown`,
				`{@prefix}blog_data`.`contents` as `contents`
			from `{@prefix}blog_data` left outer join `{@prefix}blog_category`
			on `{@prefix}blog_data`.`category_idx` = `{@prefix}blog_category`.`idx`
			order by `idx` desc limit ".( ($page -1)*10 ) .", 10");
		$stmt->execute();
		$rows = $stmt->fetchAll();

		$limit = 500;

		foreach($rows as &$row) {
			$temp =  $row['markdown'];
//			$temp 
			$ret = array();
			$cnt = 0;
			if( preg_match("/!\[.+\]\((?P<url>.+)\)/", $temp, $match) ) {
				$match_temp = pathinfo($match['url']);
				$row['thumbnail'] = $match_temp['basename'];
//				p( );
			}

			$temp = preg_replace("/!\[.+\]\(.+\)/", "", $temp);
			$lines = preg_split("/(\n|\r)+/", $temp);
			foreach($lines as $line) {
				if ($cnt > $limit) {
					array_push($ret, "<div class=\"more\">(더 보기)</div>");
					break;
				}
				$cnt += strlen($line);
				array_push($ret, $line);
			}
			$row['summary'] = $this->parser->transformMarkdown( implode("\r\n\r\n", $ret) );
		}

		$this->view->main(array(
			"rows" => $rows,
			"page" => $page
		));

	}

}