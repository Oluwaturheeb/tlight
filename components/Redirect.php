<?php

class Redirect{
	public static function to($loc = "/"){
		if(is_numeric($loc)){
			header("404 Not found!");
			echo "<h1>404</h1>
			The request url not found on this server!
			";
			exit();
		}
		header("location: ". $loc);
	}
}