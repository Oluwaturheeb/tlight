<?php

class Redirect{
	public static function to($loc = "/"){
		if(is_numeric($loc)){
			header("Http/2.0 404 Not found!");
			require_once "error/404.php";
			exit();
		}else if($loc == "login") {
		    require_once "error/login.php";
		    exit();
		}
		header("location: ". $loc);
	}
}