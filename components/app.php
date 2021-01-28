<?php
// class autoload
spl_autoload_register(function($class){
	ini_set('include_path', __DIR__);
	require_once $class.'.php';
});

// function helper

require_once 'funcHelper.php';

// calling the application settings file

callfile('settings');