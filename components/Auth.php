<?php

class Auth extends Validate {
	private $_e, $_log;
	
	public function __construct () {
		$this->_e = Db::instance();
		$this->_e->table("auth");
	}

	public static function logged () {
		if (Session::get("user")) {
			return true;
		}
		return false;
	}

	protected function cap () {
		// checking for login attempts if enabled
		$att = Config::get("auth/login_attempts");
		if ($att > 0) {
			// setting captcha info
			if (!Session::check("count")) {
				Session::set("count", 1);
			} else {
				if (Session::get("count") >= $att) {
					if (Session::check("cap")) {
						//if there is a cap ses
						$cap = Session::get("cap");
					} else {
						//else gen new
						$cap = substr(Utils::gen(), 0, 5);
						Session::set("cap", $cap);
					}
					
					if ($this->req('captcha'))
						return $this->capt($cap);
					
					$this->addError(['status' => 'captcha', 'msg' => 'Too many login attempts!','captcha' => $cap]);
					return true;
				} else {
					$c = Session::get("count");
					$c++;
					Session::set("count", $c);
				}
			}
		}
		return false;
	}
	
	public function capt ($cap) { 
			if($this->req('captcha') !== Session::get("cap")) {
				$this->addError(['status' => 'captcha','msg' => 'Captcha error!', 'captcha' => $cap]);
				return true;
			} else {
				Session::del("count");
				Session::del("cap");
			}
	}
	
	public function login (...$fv) {
		if ($this->cap()) {
			return $this;
		} else {
			list($inp, $val) = $this->autoValidate($fv);
			if($this->error())
				return $this;
			
			// remove d auth type
			
			$key = array_search('type', $inp, true);
			unset($val[$key]);
			unset($inp[$key]);
			
			$d = $this->_e;
			$id = ['id', 'password, last_log'];
			
			if (Config::get("auth/last_pc") > 0) 
				$id[] = "datediff(now(), last_pc) as days";
				
			if (Config::get("auth/single") == false)
				$id[] = "type";
				
			$log = $d->get($id)->where([$inp[0], $val[0]])->res();
			
			if ($d->count()) {
				$c = false;
				foreach ($log as $k) {
					if  ($k->password) {
						if (password_verify($val[1], $k->password)) {
							$c = true;
							break;
						}
					}
				}
				
				if ($c == true) {
					// do something
					$this->_log = $log[0];
					$this->updateLogin($this->_log->id);
				} else {
					$this->addError("Credentials does not match any account!");
				}
			} else if ($d->count() === 1) {
				if (password_verify($val[1], $log[0]->password)) {
					// do something with all the cols selected
					$this->_log = $log[0];
					$this->updateLogin($this->_log->id);
				} else {
					$this->addError("Credentials does not match any account!");
				}
			} else {
				$this->addError("Credentials does not match any account!");
			}
			return $this;
		}
	}
	
	public function reg (...$fv) {#$this->cap()
		if (false) {
			return $this;
		} else {
			$this->autoValidate($fv, true);
			if($this->error()) 
				return $this;
				
			/*$d = new Easy();
			$id = $d->table('auth')->create()
			->with('remove', ['type', 'password', 'captcha'])
			->with('append', ['password'], [$this->hash($this->req('password'))])
			->exec();
			
			if (!$d->count()) {
				$this->addError(['status' => 500, 'msg' => 'There is an account with that email address!']);
			} else {
				$this->_log = $id;
			}*/
			return $this;
		}
	}
	
	public function set ($ses = 'auth') {
		if ($this->error()) {
			$msg = $this->error();
			if (is_array($msg))
				return $msg;
			else
				return ['status' => 'error', 'msg' => $msg];
		} else {
			session_regenerate_id();
			if (is_numeric($this->_log)) {
				//register
				Session::set($ses, $this->_log);
				$msg = 'ok';
				$red = "index";
			} else {
				// login
				Session::set($ses, $this->_log->id);
				$msg = 'ok';

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
			return ['status' => $msg, "msg" => $msg, "days" => @$days, "redirect" => $red];
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
	
	public static function authId($auth = 'auth') {
		return Session::get($auth)->id;
	}
	
	public static function auth($data = null, $auth = null) {
		if ($auth)
			if ($data)
				return Session::get($auth)->$data;
			else
				return Session::get($auth);
		else
			return Session::get('auth');
	}
	
	private function updateLogin ($id) {
		$this->_e->set(['last_log'], ['\now()'])->where($id)->res();
	}
}