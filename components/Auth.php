<?php

class Auth extends Validate {
	private $_e, $_log;
	public $result;
	
	public function __construct () {
		$this->_e = new Easy();
		$this->_e->table("auth");
	}
	
	protected function cap () {
		// checking for login attempts if enabled
		if (Config::get("auth/login_attempts") > 0) {
			// if there is captcha in the request this validate the captcha
			/*if(@$this->req()["captcha"]) {
				return ["captcha" => $this->capt()];
			} else {*/
				// if no captcha this init the captcha process
				if (!Session::check("count")) {
					Session::set("count", 1);
				} else {
					if (Session::get("count") >= Config::get("auth/login_attempts")) {
						if (Session::check("cap")) {
							$cap = Session::get("cap");
						} else {
							$cap = substr(Utils::gen(true), 0, 5);
							Session::set("cap", $cap);
						}
						$this->addError(["msg" => "captcha","captcha" => $cap]);
					} else {
						$c = Session::get("count");
						$c ++;
						Session::set("count", $c);
					}
				}
				return $this;
			}
		//}
	}
	
	public function login ($cols = []) {
		//check if there there is captcha
		$d = $this->_e;
		$id = ["id"];
		
		if (Config::get("auth/last_pc") > 0) 
			$id[] = "datediff(now(), last_pc) as days";
			
		if (!Config::get("auth/single"))
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
	
	public function reg ($cols = []) {
		$d = $this->_e;
		$this->_log = $this->result = $d->create()->with("remove", ["type"])->exec(1);
		if ($d->error()) {
			$this->addError($d->error());
		} else {
			if (!$this->_log) {
				$this->addError("There is an account with that email address!");
			}
		}
		return $this;
	}
	
	public function set ($ses = "user") {
		if ($this->error()) {
			$msg = $this->error();
			if (is_array($msg))
				return $msg;
			else
				return ["msg" => $msg];
		} else {
			if ($this->_log) {
			//register
				if (is_numeric($this->_log)) {
					Session::set($ses, $this->_log);
					$msg = "ok";
				} else {
					// login
					Session::set($ses, $this->_log->id);
					$msg = "ok";
					if(!Config::get("auth/single"))
						$type = $this->_log->type;
					
					// checking password change option
					
					if (Config::get("auth/last_pc") > 0)
						if($this->_log->days > Config::get("auth/last_pc")) {
							$msg = "change";
							$days = $this->_log->days;
						}
					return ["msg" => $msg, "days" => @$days, "type" => @$type];
				}
			}
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
				$d->update(Session::get("user"))->with("remove")->with("append", ["last_pc", "password"], ["now()", $this->hash($this->req("password"))])->exec();
				if ($d->error()) 
					return ["msg" => $d->error()];
				else 
					return ["msg" => "ok"];
			}
		}
	}
	
	public function lpass () {
		$d = $this->_e;
		list($col, $val) = $this->val_req();
		
		$d->get($col[0])
		->where([$col[0], $val[0]])
		->res();
		
		if ($d->error() || !$d->count()) 
			$this->addError("Email does not match any account, try again!");

		$token = Utils::gen();
		$dom = Config::get("session/domain");
		$time_exp = time() + 60 * 60 * 30;
		$time = time("d-M-y h:m:i", $time_exp);

		mail($d[0]->email, "Password recovery", "click <a href='$dom/fpass/$token'>here</a> this link expires $time");
		$msg = mail(to, subject, message);
		echo $msg;
	}
}