<?php
$loc = 'config.php';
if ($_SERVER['SCRIPT_NAME'] != 'tlight')
	 $loc = '../' . $loc;
require_once $loc;

spl_autoload_register(function($class){
	ini_set("include_path", "../");
	include_once "components/{$class}.php";
});

$c = new Config();
// constants
const STYLE = 'assets/css/';
const JS = 'assets/js/';
const jQuery = 'assets/js/jquery/jquery.js';
const IMG = 'assets/img/';
define('PNAME',  $c->get('project/name'));

// configs 

if ($c->get('state/development'))
 ini_set('error_reporting', E_ALL);
else
	ini_set('error_reporting', 0);

// timezone

date_default_timezone_set($c->get('project/region'));

// sessions

ini_set('session.cookie_domain', $c->get('.' . 'session/name'));
session_name($c->get('session/name'));
session_start();
require_once 'funcHelper.php';