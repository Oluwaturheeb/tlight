<?php
spl_autoload_register(function($class){
    include_once "components/" .$class . ".php";
});

$c = new Config();
// constants
const style = "assets/css/";
const js = "assets/js/";
const jq = "assets/js/jquery/jquery.js";
define("PNAME",  $c->get("project/name"));

// configs 

if ($c->get("state/development"))
 ini_set("error_reporting", E_ALL);
else
	ini_set("error_reporting", 0);

// timezone

date_default_timezone_set($c->get("project/region"));

// sessions

ini_set("session.cookie_domain", $c->get("." . "session/name"));
session_name($c->get("session/name"));
session_start();