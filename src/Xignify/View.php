<?php
namespace Xignify;

class View {

	static public function load( $name ) {
		static $ins;

		if ( !isset($ins[$name]) ) { $ins[$name] = new $name; }
		return $ins[$name];

	}

	static public function html( $url ) {
		static $contents;

		$args = func_get_args();

		if ( !isset($contents[$url]) ) {
			$path = __ROOT__.'/'.$url.'.html';
			if ( !file_exists($path) ) throw new \Xignify\Exception\FileNotFoundException("\"{$path}\" there is no file!");
			$contents[$url] = file_get_contents($path);
			$contents[$url] = str_replace("<?=", "<?php echo ", $contents[$url]);
		}

		for( $i = 1; $i < count($args); $i++ ) {
			if ( !is_array($args[$i]) ) throw new \InvalidArgumentException("No Array!");
			extract( $args[$i] );

		}
		eval("?>".$contents[$url]);
	}

	static public function css() {
		$args = func_get_args();
		if ( count($args) == 1 && is_array($args) ) {
			$args = $args[0];
		}
		array_walk($args, function( &$arg ) {
			$arg = '<link rel="stylesheet" href="' . HOME . '/' . htmlspecialchars($arg) . '" />';
		});
		return implode("\r\n", $args);
	}

	static public function js() {
		$args = func_get_args();
		if ( count($args) == 1 && is_array($args) ) {
			$args = $args[0];
		}
		array_walk($args, function( &$arg ) {
			$arg = '<script src="' . HOME . '/' .htmlspecialchars($arg) .'"></script>';
		});
		return implode("\r\n", $args);
	}


	/**
	 * header
	 * 헤더를 정의 합니다.
	 * 
	 * @param string $type 문서의 타입을 정의, (html|xml|css|js|json) default="html"
	 * @param string $encoding, 문서 인코딩을 정의 default = "utf-8"
	 *
	 * @return void
	 */

	public static function header( $type = "html", $encoding = "utf-8" ) {

		switch($type) {
			case "html" :
			case "xml" :
			case "css" :
				header("Content-Type: text/{$type};charset={$encoding}");
				break;
			case "js" :
				header("Content-type: text/javascript;charset={$encoding}");
				break;
			case "json" :
				header("Content-Type: application/json;charset={$encoding}");
				break;
		}

	}
	/**
	 * getCapture
	 * func 내부 함수를 실행한 결과를 캡쳐합니다.
	 * 
	 * @param Closure $func 익명함수를 사용하여 내부 내용을 캡쳐합니다.
	 *
	 * @return string
	 */
	public static function getCapture( $func ) {
		ob_start();

		$func();

		$buffer = ob_get_contents();
		ob_end_clean();		
		return $buffer;
	}


}