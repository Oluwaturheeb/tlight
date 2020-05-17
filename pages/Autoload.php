<?php
ini_set("include_path", "../");
require_once "Config.php";

class Config {
    public static function get($path){
        $path = explode("/", $path);
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
    
    public function init() {
		if (self::get('db/database') != "tlight") {
			$d = new mysqli(self::get("db/host"), self::get("db/usr"), self::get("db/pwd"));
			$db = self::get('db/database');
			if ($d->query("create database if not exists $db") === true) {
				$d->select_db(self::get('db/database'));
	
				$auth = "create table if not exists auth(id int auto_increment, email varchar(150) not null, password varchar(64) not null, last_log datetime, last_pc datetime default now()";
				
				$rel = "create table if not exists relation(id int auto_increment, tab varchar(100) not null, rel varchar(200) not null, primary key(id))";
	
				if (!self::get("auth/single")) {
					$auth .= ", type varchar(50) null";
				}
	
				$auth .= ", primary key(id), unique(email));";
				$d->multi_query($auth . $rel);
				if (!$d->error)
					return true;
			}
		}
		return false;
	}
}

require_once "class/setting.php";