<?php

class Session{
	public static function check($name = ''){
		if($name){
			return (isset($_SESSION[$name])) ? true : false;
		}else{
			return (count($_SESSION)) ? true : false;
		}
	}
	
	public static function set($name, $value, $exp = false){
		
		$_SESSION[$name] = $value;
		
		// setting cookie along with d session
		if ($exp)
			self::setCookie($name, $value, $exp);
	}
	
	public static function get($name = ''){
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
	
	public static function del($name = ''){
		if(!empty($name)){
			if(self::check($name)){
				unset($_SESSION[$name]);
			}
		}else{
			session_unset();
		}
	}
	
	public static function getCookie ($name = '') {
		if ($name) {
			if (isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}
		} else {
			return $_COOKIE;
		}
	}
	
	private static function setCookie ($name, $value, $exp) {
		if (is_bool($exp)) $exp = time() + 3600 * 24 * 30;
			($_SERVER['REQUEST_SCHEME'] == 'https') ? $s = true : $s = false;
			@setcookie($name, $value, $exp, '/', Config::get('session/domain'), $s, true);
	}
	
	public static function delCookie($name = '') {
		if (!is_array($name)) {
			self::setCookie($name, '', time() - 3600 * 24);
		} else {
			if (!empty($name))
			foreach($name as $n) {
				self::delCookie($n);
			}
		}
	}
}