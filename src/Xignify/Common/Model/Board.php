<?php
namespace Xignify\Common;

class Model_Board {
	public function makePagination( $total, $o = array() ) {

		$defaults = array(
			"len_line" => 20,
			"len_page" => 7,
			"page_link" => HOME,
			"page"	=> 1
		);

		$settings = array_merge($defaults, $o);

		$len_line	= $settings['len_line'];
		$len_page	= $settings['len_page'];
		$page_link	= $settings['page_link'];
		$page		= $settings['page'];
		
		$max_page = max(ceil($total / $len_line), 1); // 총 몇페이지, 단 최대값은 1.
		
		$pagination = "<ul>";

		if ($max_page <= $len_page) {
			for ($i = 1; $i <= $max_page; $i++) $pagination .= "<li".(($page==$i)?" class=\"active\"":"")."><a href=\"{$page_link}?page={$i}\">{$i}</a></li>";
		}
		else {
			$pagination .= "<li".(($page==1)?" class=\"active\"":"")."><a href=\"{$page_link}?page=1\">1</a></li>";
			if ($page < $len_page - 3) {
				for ($i = 2; $i <= $len_page - 2; $i++) $pagination .= "<li".(($page==$i)?" class=\"active\"":"")."><a href=\"{$page_link}?page={$i}\">{$i}</a></li>";
				$pagination .= "<li class=\"disabled\"><span>..</span></li>";
			}
			else if ($page > $max_page - ($len_page - 3) ) {
				$pagination .= "<li class=\"disabled\"><span>..</span></li>";
				for ($i = $max_page - ($len_page - 3) ; $i < $max_page ; $i++) $pagination .= "<li".(($page==$i)?" class=\"active\"":"")."><a href=\"{$page_link}?page={$i}\">{$i}</a></li>";
			}
			else {
				$pagination .= "<li class=\"disabled\"><span>..</span></li>";
				for ($i = $page-1 ; $i < $page+2 ; $i++) $pagination .= "<li".(($page==$i)?" class=\"active\"":"")."><a href=\"{$page_link}?page={$i}\">{$i}</a></li>";
				$pagination .= "<li class=\"disabled\"><span>..</span></li>";
			}
			$pagination .= "<li".(($page==$max_page)?" class=\"active\"":"")."><a href=\"{$page_link}?page={$max_page}\">{$max_page}</a></li>";
		}

		$pagination .= "</ul>";

	/////		
		return $pagination;		
	}	
}