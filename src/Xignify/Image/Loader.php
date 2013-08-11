<?php
namespace Xignify\Image;

use \Xignify\Controller;
use \Xignify\Module\Input;
use \PHPImageWorkshop\ImageWorkshop;

class Loader extends Controller {

	private $basepath = "";

	public function __init() {
		$this->basepath = __ROOT__."/files/";
		$this->cachepath = $this->basepath . "__cache/";
	}

	public function __fromRequest() {

		args_init( $this->args, "none" );

		$file = pathinfo($this->args[0]);

		$width	= Input::get("width", 0);
		$forced = Input::get("forced", false);

		$cache_file = $file['filename'] . "_w{$width}" . "." . $file['extension'];
		$cache_file_path = $this->cachepath . $cache_file;
		if ( !file_exists( $cache_file_path ) || $forced === "true" ) {
			$origin_file = $this->basepath . $this->args[0];
			$layer = ImageWorkshop::initFromPath( $origin_file );

			$exif = @exif_read_data($origin_file);
			if ( isset($exif) && isset($exif['Orientation']) ) {
				switch($exif['Orientation']) {
					case 2 :
						$layer->flip("horizontal");
						break;
					case 3 :
						$layer->rotate(180);
						break;
					case 4 :
						//$layer->rotate(180);
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
			}
			if ( $width !== 0 ) {
				$layer->resizeInPixel($width, null, true);
			}
			$layer->save($this->cachepath, $cache_file, true, null, 100);
		}
		header("Content-type:image/jpeg");
		readfile($cache_file_path);

	}

	public function makeimage( $file ) {

	}

	public function download($file) { // $file = include path 
		/*
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }*/

    }

}