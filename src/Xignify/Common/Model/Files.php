<?php
namespace Xignify\Common;

class Model_Files {

	private $realpath	= null;
	private $urlpath	= null;
	
	private $data	= array();
	private $url	= array();
	private $error	= array();
	
	public function __construct() { 

		$this->realpath = __ROOT__."/files";
		$this->urlpath	= HOME."/files";

		if ( !is_dir($this->realpath) ) {
			$oldumask = umask(0);
			mkdir($this->realpath, 0777);
			umask($oldumask);
			unset($oldumask);
		}
	}

	public function files_exists( $name = null ) {
		$ret = false;

		if ( is_null($name) ) {
			foreach($_FILES as $k => $v) {
				if ( $v['error'] == 0 ) $ret = true;;
			}
		}
		else {
			if ( isset($_FILES[$name])  && $_FILES[$name] !== "" && $_FILES[$name]['error'] == 0) $ret = true;
		}
		return $ret;
	}

	public function upload( $error_flag = false ) {

		$fileSrch = array(" ", "-", "|", "+");
		$fileRplc = array("_", "_", "_", "_");
		
		foreach($_FILES as $k => $v) {

			if ( !isset($this->data[$k] ) ) $this->data[$k]	= array();
			if ( !isset($this->url[$k]  ) ) $this->url[$k]	= array();
			if ( !isset($this->error[$k]) ) $this->error[$k]	= array();

			if ( is_array( $_FILES[$k]['error'] ) ) { // 배열일때!

				for ($i = 0; $i < count($_FILES[$k]['error']); $i++ ){
	
					if ( $_FILES[$k]['error'][$i] == 0 ) {
						$path_parts = pathinfo( str_replace($fileSrch, $fileRplc, $_FILES[$k]['name'][$i]) );
						
						$file_name	= $path_parts["filename"];
						$file_type	= $path_parts["extension"];
						
						// 중복파일 체크
						for ($j = 0; ; $j++) {
							$newFileName	= $file_name."_".$j.".".$file_type;
							$newFileSrc		= $this->realpath."/".$newFileName;
							if (!file_exists($newFileSrc)) break;
						}
						move_uploaded_file( $_FILES[$k]['tmp_name'][$i], $newFileSrc);
						
						array_push($this->error[$k], $_FILES[$k]['error'][$i] );
						array_push($this->data[$k], ($newFileName) );
						array_push($this->url[$k], ( $this->urlpath . "/" . ($newFileName)) );

					}
					else if ( $error_flag ) {
						array_push($this->error[$k], $_FILES[$k]['error'][$i] );
						array_push($this->data[$k], "error" );
						array_push($this->url[$k]	, "error" );
					}
				}
			}
			else { // 아니배열일때!!
				
				if ( $_FILES[$k]['error'] == 0 ) { // 에러안날때!!

					$path_parts = pathinfo( str_replace($fileSrch, $fileRplc, $_FILES[$k]['name']) );
					
					$file_name	= $path_parts["filename"];
					$file_type	= $path_parts["extension"];

					// 중복파일 체크
					for ($j = 0; ; $j++) {
						$newFileName	= $file_name."_".$j.".".$file_type;
						$newFileSrc		= $this->realpath."/".$newFileName;
						if (!file_exists($newFileSrc)) break;
					}
					move_uploaded_file( $_FILES[$k]['tmp_name'], $newFileSrc);

					array_push($this->error[$k], $_FILES[$k]['error'] );
					array_push($this->data[$k], ($newFileName) );
					array_push($this->url[$k], ($this->urlpath."/".($newFileName)) );
				
				}
				else if ( $error_flag ) {
					array_push($this->error[$k], $_FILES[$k]['error'] );
					array_push($this->data[$k], "error" );
					array_push($this->url[$k], "error" );
				}
			}
		}
		foreach ( $this->error as $k => $v ) {
			if (count( $this->error[$k]  ) == 0) 
				unset($this->error[$k], $this->data[$k], $this->url[$k] );
		}

		if (count($this->error) == 0) return 0;
		return 1;
	}
	
	public function getUrl( $name = null ) {
		if ( isset($name) && isset($this->url[$name]) ) {
			return $this->url[$name];
		}
		return $this->url;		
	}
	public function getData( $name = null ) {
		if ( isset($name) && isset($this->data[$name]) ) {
			return $this->data[$name];
		}
		return $this->data;		
	}
	public function getError( $name = null ) {
		return $this->error;		
	}
// End Class
}
?>