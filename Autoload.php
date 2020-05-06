<?php
require_once "class/Config.php";

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
}

spl_autoload_register(function($class){
    require_once "class/" .$class . ".php";
});


$c = new Config();
const style = "assets/css/";
const js = "assets/js/";
const jq = "assets/js/jquery/jquery.js";
define("PNAME",  $c->get("project/name"));

ini_set("session.cookie_domain", $c->get("." . "session/name"));
date_default_timezone_set($c->get("project/region"));
session_name($c->get("session/name"));
session_start();
