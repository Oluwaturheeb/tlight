<?php

class Session{
	public static function check($name = ""){
		if($name){
			return (isset($_SESSION[$name])) ? true : false;
		}else{
			return (count($_SESSION)) ? true : false;
		}
	}
	
	public static function set($name, $value){
		return $_SESSION[$name] = $value;
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