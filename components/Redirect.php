<?php

class Redirect {
	public static function to($loc = "/") {
		if (is_numeric($loc)) {
			if ($loc == 404) {
				header("404 Not found!");
				require_once 'inc/error/404.php';
				exit();
			} elseif ($loc == 500) {
				header("500 Server error!");
				require_once 'inc/error/500.php';
				exit();
			}
		}
		header("location: ". $loc);
	}
}