<?php

class Caching {
	static $loc = '../components/cache/';
	
	protected static function getUrl () {
		$file = Validate::_hash($_SERVER['SCRIPT_FILENAME'], 8);
		return self::$loc . $file;
	}
	
	public static function showCache () {
		// clear php stats
		clearstatcache();
		// rename file
		if (config('cache/cache')) {
			$pfile = self::getUrl();
			if (file_exists($pfile)) {
				if (filemtime($pfile) > time() - config('cache/cacheexp')) {
					echo file_get_contents($pfile);
					die();
				} else {
					unlink($pfile);
				}
			}
		}
		ob_start();
	}
	
	public static function createCacheFile () {
		if (config('cache/cache')) {
			$buff = ob_get_contents();
			file_put_contents(self::getUrl(), $buff);
			ob_end_flush();
		}
	}
	
	public static function clearCache () {
		Utils::r_delete(__DIR__.'/cache/');
		return;
	}
}