<?php

class Config {
	public static function get($path){
		$path = explode('/', $path);
		$data = $GLOBALS['config'];
		foreach ($path as $val) {
			if(isset($data[$val])){
				$data = $data[$val];
			}else{
				$data = false;
			}
		}
		return $data;
	}
	
	public static function init() {
		if (self::get('db/database') != 'tlight') {
			$d = new mysqli(self::get('db/host'), self::get('db/usr'), self::get('db/pwd'));
			$db = self::get('db/database');
			if ($d->query('create database if not exists $db') === true) {
				$d->select_db(self::get('db/database'));
	
				$auth = 'create table if not exists auth(id int auto_increment, email varchar(150) not null, password varchar(64) not null, last_log datetime, last_pc datetime default now()';
				
				if (!self::get('auth/single')) {
					$auth .= ', type varchar(50) null';
				}
	
				$auth .= ', primary key(id), unique(email))';
				$d->query($auth);
				if (!$d->error)
					return true;
			}
		}
		return false;
	}
}