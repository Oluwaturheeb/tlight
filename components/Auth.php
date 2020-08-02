<?php

class Auth extends Validate {
	private $_e, $_log;
	public $result;
	
	public function __construct () {
		$this->_e = new Easy();
		$this->_e->table("auth");
	}

	public static function logged () {
		if (Session::get("user")) {
			return true;
		}
		return false;
	}

	protected function init_count () {
		// checking for login attempts if enabled
		if (Config::get("auth/login_attempts") > 0) {
			if (!Session::check("count")) {
				Session::set("count", 1);
			} else {
				// this validate the captcha from the request!
				if (Http::req("captcha")) {
					if (!$this->v_captcha()) {
						$this->addError(["msg" => "captcha", "captcha" => $this->captcha(), "error" => "Captcha error!"]);
						return true;
					} else {
						Session::del("count");
						return false;
					}
				} else {
					// this increment the captcha process
					if (Session::get("count") >= Config::get("auth/login_attempts")) {
						$this->addError(["msg" => "captcha","captcha" => $this->captcha()]);
						return true;
					} else {
						$c = Session::get("count");
						$c ++;
						Session::set("count", $c);
					}
				}
			}
		}
		return false;
	}
	
	public function login () {
		if ($this->init_count()) {
			return $this;
		} else {
			$d = $this->_e;
			$id = ["id"];
			
			if (Config::get("auth/last_pc") > 0) 
				$id[] = "datediff(now(), last_pc) as days";
				
			if (Config::get("auth/single") == false)
				$id[] = "type";
				
			$this->_log = $this->result = $d->fetch($id)->with("remove", ["type", "password"])->with("append", ["password"], [$this->hash(lcfirst($this->fetch("password")))])->exec(1);
			
			if ($d->error()) {
				$this->addError($d->error());
			} else {
				if (!$this->_log) {
					$this->addError("Credentials does not match any account!");
				}
			}
			return $this;
		}
	}
	
	public function reg ($cols = []) {
		if ($this->init_count()) {
			return $this;
		} else {
			$d = $this->_e;
			$this->_log = $this->result = $d->create()->with("remove", ["type", "password"])
			->with("append", ["password"], [$this->hash(lcfirst($this->fetch("password")))])
			->with("change", ['email', 'type', 'password'])
			->exec(1);
			
			if ($d->error()) {
				$this->addError("There is an account with that email address!");
			}
			return $this;
		}
	}
	
	public function set ($ses = "user") {
		if ($this->error()) {
			$msg = $this->error();
			if (is_array($msg))
				return $msg;
			else
				return ["msg" => $msg];
		} else {
			session_regenerate_id();
			if (is_numeric($this->_log)) {
				//register
				Session::set($ses, $this->_log);
				$msg = "ok";
				$red = "home";
			} else {
				// login
				Session::set($ses, $this->_log->id);
				$msg = "ok";

				// checking for multiple login
			
				if(Config::get("auth/single") == false)
					$red = $this->_log->type;
				else 
					$red = "home";
				
				// checking password change option
				
				if (Config::get("auth/last_pc") > 0)
					if($this->_log->days > Config::get("auth/last_pc")) {
						$msg = "change";
						$days = $this->_log->days;
					}
			}
			return ["msg" => $msg, "days" => @$days, "redirect" => $red];
		}
	}
	
	public function chpwd ($ses = "user") {
		$d = $this->_e;
		if($this->error()) {
			if (is_array($this->_error()))
				return $this->_error;
			else
				return ["msg" => $this->error()];
		} else {
			$this->validator($_POST, [
				"password" => ["match" => "verify"]
			]);

			if ($this->error()) {
				return ["msg" => $this->error()];
			} else {
				$d->update(Session::get("user"))->with("remove")->with("append", ["last_pc", "password", "captcha"], ["now()", $this->hash(Http::req("password"))])->exec();
				if ($d->error()) 
					return ["msg" => $d->error()];
				else 
					return ["msg" => "ok"];
			}
		}
	}
	
	public function lpass () {
		if ($this->init_count()) {
			return $this->error();
		} else {
			$d = $this->_e;
			list($col, $val) = $this->val_req();
			
			$d->unique("email");

			if ($d->error() || !$d->count()) {
				$this->addError("Email does not match any account, try again!");
			} else {
				return $this->fetch("email");
				/*$token = Utils::gen();
				$dom = Config::get("session/domain");
				$time_exp = time() + 60 * 60 * 30;
				$time = date(Utils::time(), $time_exp);

				$msg = mail($this->fetch("email"), "Password recovery", "click <a href='$dom/fpass/$token'>here</a> this link expires $time");
				echo $msg;*/
			}
		}
	}
}