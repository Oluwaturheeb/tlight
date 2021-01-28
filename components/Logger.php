<?php

class Logger {
	//option = daily, extended
	// type = info, request, error
	const option = 'daily';
	
	public static function log ($data, $type = 'info') {
		$date = Utils::date();
		// justifying the name
		switch(self::option) {
			case 'daily':
				$opt = $date;
				break;
			case 'extended':
				$opt = 'tlight';
				break;
		}
		
		$rep = [' ', ',', '-'];
		// tlight root for logging
		$path = '../log/';
		$file_name = str_replace($rep, array_fill(0, count($rep), '_'), $opt). '.log';
		self::filelog($path.$file_name, $data);
	}
	
	public static function logReq() {
		if ($_SERVER['SCRIPT_NAME'] != 'tlight')
			if (config('log/log_request')) {
				$s = $_SERVER;
				$type = $s['REQUEST_METHOD'];
				$url = $s['REQUEST_URI'];
				$time = Utils::datetime($s['REQUEST_TIME'], true);
				$ip = $s['REMOTE_ADDR'];
				
				if ($type != 'GET') {
					$input = Utils::json($_POST);
				} elseif (isset($s['QUERY_STRING'])) {
					$input = $s['QUERY_STRING'];
				} else {
					$input = null;
				}
				
				$log = <<<__tlyt
	$time	$ip	$type	$url	$input
__tlyt;
			self::log($log, 'Request');
			}
	}
	
	private static function filelog ($f, $d) {
		$str = <<<__tlight

$d
____________________________________
__tlight;
	file_put_contents($f, $str, FILE_APPEND);
	}
}