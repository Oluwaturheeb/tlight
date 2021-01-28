<?php
// error handler and logging

function dError ($errno, $errstr, $errfile, $errline) {
	$s = config('state/development');
	if ($errno) {
		if ($s) {
			$compact = <<<__here
			Error encountered in $errfile on $errline
			<details open>
				<summary>More details</summary>
				<p style="padding-left: 1rem;">$errstr</p>
			</details>
__here;
			$log = "Error encountered in $errfile on $errline \n $errstr";
			Logger::log($log, 'Error');
		} else {
			$compact = <<<__here
			Something went wrong, contact Server administrator.
__here;
		}
	} else {
		return;
	}
	ob_end_clean();
	header('HTTP/1.1 500 Internal Server Error');
	callfile('../pages/inc/error/error', [500, 'Internal server error!', $compact]);
	die();
}

// xss
if (Validate::sameSite())
	Redirect::to($_SERVER['HTTP_ORIGIN']);

// development state

if (config('state/development')) {
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
} else {
	ini_set('error_reporting', 0);
	ini_set('display_errors', 0);
}

if ($_SERVER['SCRIPT_FILENAME'] !== 'tlyt') {
	if (!isAjax())
		set_error_handler('dError');
}
	
// timezone
date_default_timezone_set(config('project/region'));	

// sessions
ini_set('session.cookie_domain', config('.' . 'session/name'));
ini_set('session.auto_start', true);
ini_set('session.use_strict_mode', true);
session_name(config('session/name'));

//logging of requests
if ($_SERVER['SCRIPT_FILENAME'] !== 'tlyt')
	if (config('log/log_request'))
		Logger::logReq();
		
// database caching 
if (config('cache/dbcache')) {
	ini_set('mysqlnd_qc.enable_qc', true);
	ini_set('mysqlnd_qc.cache_by_default', true);
}

// constants
const STYLE = 'assets/css/';
const JS = 'assets/js/';
const jQuery = 'assets/js/jquery/jquery.js';
const IMG = 'assets/img/';
$pn = config('project/name');
define('PNAME', $pn);

// begin the session
session_start();

// active login and also redirectLogin if user visit login page after being logged.
(new Auth())->activeLogin();
redirectLogin();

Caching::showCache();