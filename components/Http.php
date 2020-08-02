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

	public static function res ($data = "ok") {
		if (is_array($data))
			echo Utils::json($data);
		else
			echo Utils::json(["msg" => $data]);
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