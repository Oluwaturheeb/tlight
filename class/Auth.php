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
			if (@$this->req()["captcha"]) {
				$this->val_req();
			} else {
				if (!Session::check("count")) {
					Session::set("count", 1);
				} else {
					if (Session::get("count") >= Config::get("auth/login_attempts")) {
						if (Session::check("cap")) {
							$cap = Session::get("cap");
						} else {
							$cap = substr(Utils::gen(), 0, 5);
							Session::set("cap", $cap);
						}
						$this->addError("cap " .$cap);
					} else {
						$c = Session::get("count");
						$c ++;
						Session::set("count", $c);
					}
				}
				return $this;
			}
		}
	}
	
	public function login ($cols = [], $c = true) {
		$this->cap();
		$d = $this->_e;
		$id = ["id", "datediff(now(), last_pc) as days"];
		
		if (!$c) 
			$id = ["id"];

		$this->_log = $this->result = $d->fetch($id)->with("remove", ["type"])->exec(1);

		if (!$this->_log) {
			$this->addError("Credentials does not match any account!");
		}
		
		return $this;
	}
	
	public function reg ($cols = []) {
		$d = $this->_e;
		$this->_log = $this->result = $d->create()->with("remove", ["type"])->exec(1);

		return $this;
	}
	
	public function set ($ses = "user") {
		if ($this->error()) {
			return $this->error();
		} else {
			if($this->_log)
			//register
				if (is_numeric($this->_log)) {
					Session::set($ses, $this->_log);
					return "ok";
				} else {
				// login
					Session::set($ses, $this->_log->id);

					if($this->_log->days > 30) {
						return "change {$this->_log->days}";
					} else {
						if (Config::get("auth/single")) {
							return "ok";
						} else {
							return $this->_e->type;
						}
					}
				}
		}
	}
	
	public function chpwd ($ses = "user") {
		$d = $this->_e;

		if($this->error()) {
			echo $this->error();
		} else {
			$this->validator($_POST, [
				"password" => ["match" => "verify"]
			]);

			if ($this->error()) {
				return $this->error();
			} else {
				$d->update([], Session::get("user"))->with("remove", ["type", "verify"])->with("append", ["last_pc"], ["now()"])->exec();
				if ($d->error()) 
					return $d->error();
				else 
					return "ok";
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