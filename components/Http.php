<?php

/**
 * 
 */
class Http extends Validate {
	
	public static function req ($r = "") {
		if (self::server()) {
			if (!empty($_POST))
				$req = $_POST;
			elseif (!empty($_GET))
				$req = $_GET;
			else
				$req = false;

			if ($r) {
				if (@$req[$r])
					$req = $req[$r];
				else
					$req = false;
			}

			if ($req) {
				$req = self::filter($req);
			}

			return $req;
		}
	}

	public static function res ($data = "ok", int $status = 200) {
		if (is_array($data)) {
			if (array_key_exists('status', $data)) {
				$status = $data['status'];
			} 
		} else {
			$data = ['msg' => $status, 'msg' => $data];
		}
		
		switch($status) {
			case 200:
				header('HTTP/1.1 200 OK');
			break;
			case 404:
				header('HTTP/1.1 404 Not found');
			break;
			case 500:
				header('HTTP/1.1 500 Internal Server Error');
			break;
			case 419:
				header('HTTP/1.1 419 Invalid form');
			break;
			case 422:
				header('HTTP/1.1 422 Validation error');
			break;
		}
		echo Utils::json($data);
	}

	public static function server () {
		if (Config::get('session/domain') == $_SERVER['SERVER_NAME'] && isset($_SERVER['HTTP_USER_AGENT'])) {
			return true;
		}
		return false;
	}

	public static function is_ajax(){
		if ($_SERVER['HTTP_X_REQUESTED_WITH']) {
			return true;
		}
		return false;
	}
}