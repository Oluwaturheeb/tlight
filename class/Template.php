<?php

class Template {
	private $_html, $_meta, $_body = [];
	public function __construct () {
		$js = self::asset("js", "app.js");
		$css = self::asset("css", "app.css");
		$body = $this->_body;
		$this->_html = <<<__here

		<!DOCTYPE html>
		<html>
			<head>
				$this->_meta
				$css
				$js
			</head>
			<body>
				<div class="container">
					$body
				</div>
			</body>
		</html>

__here;

		return $this;
	}

	public function init () {
		return $this->_html;
	}

	public function meta ($other = "") {
		$this->_meta = <<<__here
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scaleable=no">
		$other

__here;
		return $this;
	}

	public function asset ($type, $file) {
		switch ($type) {
			case 'css':
				$loc = "assets/css/" . $file;
				$loc = '<link rel="stylesheet" href="' . $loc . '"/>';
				break;
			
			case "js": 
				$loc = "assets/js/" . $file;
				$loc = '<script src="' . $loc . '" type="text/javascript"></script>';
				break;
		}
		return $loc;
	}

	public function header ($level = 1) {
		switch ($level) {
			case 1:
				$html = <<<__here

__here;
				break;
		}
		return $html;
	}

	public function body ($html) {
		array_push($this->_body, $html);
		return self::__toString($this->_body);
	}
}

?>