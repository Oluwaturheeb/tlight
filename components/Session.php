<?php

class Session{
	public static function check($name = ""){
		if($name){
			return (isset($_SESSION[$name])) ? true : false;
		}else{
			return (count($_SESSION)) ? true : false;
		}
	}
	
	public static function set($name, $value, $exp = 0, $c = false){
		
		$_SESSION[$name] = $value;

		if ($exp) {
			session_set_cookie_params($exp);
		}

		if ($c)
			session_commit();
	}
	
	public static function get($name = ""){
		if($name){
			if (self::check($name)) {
				return $_SESSION[$name];
			} else {
				return false;
			}
		}else{
			return $_SESSION;
		}
	}
	
	public static function del($name = ""){
		if(!empty($name)){
			if(self::check($name)){
				unset($_SESSION[$name]);
			}
		}else{
			session_unset();
		}
	}
}