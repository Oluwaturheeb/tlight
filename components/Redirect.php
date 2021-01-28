<?php

class Redirect {
	public static function to($loc = '/', $msg = null) {
		if (is_numeric($loc)) {
			if ($loc == 404) {
				header('404 Not found!');
				require_once 'inc/error/404.php';
				exit();
			} elseif ($loc == 500) {
				$typ = 'Internal Server Error!';
				header('500 Server error!');
				require_once 'inc/error/error.php';
				exit();
			}
		}
		header('location: '. $loc);
	}
}