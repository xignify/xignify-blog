<?php
namespace Xignify\Admin;

use \Xignify\Config;

class Model_Wandu {

	public function main() {
		$kongs = Config::fromPhp("xignify-admin-kongs");
		foreach( $kongs as $k => $kong ) {
			$kong_config = Config::fromPhp("kongs/".$kong['name']);
			$kongs[$k]['href'] = HOME.'/admin/'.$kong['name'];
			$kongs[$k]['icon'] = HOME.'/'.$kong_config['icon'];
			$kongs[$k]['name'] = $kong_config['name'];
		}

		return array("kongs" => $kongs);
	}


}