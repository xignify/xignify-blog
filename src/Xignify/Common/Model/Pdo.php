<?php
namespace Xignify\Common;

use \Xignify\Config;

class Model_Pdo {

	private $conn = null;

	public function __construct() { /* Empty */ 

		try {
			$config = Config::fromPhp("xignify-pdo");
			array_extend($this->config, $config);
		}
		catch (\Exception $e) {
			Core\Error::box($e->getMessage()."(".__FILE__.":".__LINE__.")");
			exit;
		}
		$this->connect($config);

	}

	public function connect( $config ) {
		if ( !isset($config['type']) ) throw new \PDOException("db type이 없습니다.");
		$dns = $config['type'] . ':';
		$dns .= 'host='. ( (isset($config['host']))?$config['host']:"127.0.0.1" ) .';';
		
		if ( isset($config['dbname']) ) {
			$dns .= 'dbname='. $config['dbname'] .';';
		}
		$this->conn = new \PDO($dns, $config['id'], $config['password'],
			array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	// if you want manual,,
	public function setConn( $conn ) {
		$this->conn = $conn;
	}
	
	public function getConn() {
		return $this->conn;
	}
	
	public function query( $qry ) {
		if (is_array($qry)) {
			$qry = implode(";", $qry);
		}
		$qry = str_replace("{@prefix}", $this->config['prefix'], $qry);
		return $this->conn->query($qry);
	}
	
	public function prepare( $qry ) {
		$qry = str_replace("{@prefix}", $this->config['prefix'], $qry);
		return $this->conn->prepare($qry);		
	}

}