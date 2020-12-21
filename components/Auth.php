<?php

class Auth extends Validate {
	private $_e, $_log;
	
	public function __construct () {
		$this->_e = Db::instance();
		$this->_e->table("auth");
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
	
	private function capt ($cap) { 
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
			list($inp, $val) = $this->autoValidate($fv, true);
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
					Session::set('auth', $this->_log);
					Session::set('authId', $this->_log->id);
				} else {
					$this->addError("Credentials does not match any account!");
				}
			} else {
				$this->addError("Credentials does not match any account!");
			}
			return $this;
		}
		
		/* else if ($d->count() === 1) {
				if (password_verify($val[1], $log[0]->password)) {
					// do something with all the cols selected
					$this->_log = $log[0];
					Session::set('authId', $this->_log->id);
					Session::set('auth', $this->_log);
					$this->updateLogin($this->_log->id);
				} else {
					$this->addError("Credentials does not match any account!");
				}
			}*/ 
	}
	
	public function reg (...$fv) {
		if ($this->cap()) {
			return $this;
		} else {
			$this->autoValidate($fv, true);
			if($this->error()) 
				return $this;
				
			$d = new Easy();
			$id = $d->table('auth')->create()
			->with('remove', ['type', 'password', 'captcha'])
			->with('append', ['password'], [$this->hash($this->req('password'))])
			->exec();
			
			if (!$d->count()) {
				$this->addError(['status' => 500, 'msg' => 'There is an account with that email address!']);
			} else {
				$this->_log = $id;
				Session::set('authId', $this->_log);
			}
			return $this;
		}
	}
	
	public function set () {
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
				Session::set('authId', $this->_log);
				$msg = 'ok';
				$red = "index";
			} else {
				// login
				Session::set('authId', $this->_log->id);
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
	
	public function chpwd (...$fv) {
		//if ($this->cap()) return $this->error();
		
		$this->autoValidate($fv, true);
		if ($this->error()) return $this->error();
		
		$e = new Easy();
		$d = $e->table('auth')->update($this->authId())->with('remove')->with('append', ['last_pc', 'password'], [date("Y-m-d H:i:s", time()), $this->hash($this->req('password'))])->exec();
		
		if ($e->error() || !$e->count())
			return ['status' => 500, 'msg' => $e->error()];
		else
			return ['status' => 'ok', 'msg' => 'Password updated successfully!'];
	}
	
	public function lpass ($fv = ['required' => true, 'email' => 'true']) {
		if ($this->cap()) {
			return $this->error();
		} else {
			$this->autoValidate($fv, true);
			
			if ($this->error())
				return ['status' => 422, 'msg' => $this->error()];
				
			$e = new Easy();
			$e->table('auth')->fetch()->with('remove', ['captcha', 'type'])->exec();
			
			if ($e->error() || !$e->count()) {
				$this->addError('Email does not match any account, try again!');
			} else {
				//no verification
				return ['status' => 'change', 'msg' => 'Create new password'];
				/*
				email verification 
				
				if (Session::check('fpass')) {
					goto ret;
				} else {
					$token = Utils::gen();
					$dom = Config::get("session/domain");
					$time_exp = time() + 60 * 60 * 30;
					$time = date("D, jS \of F, Y h:i:a", $time_exp);
	
					$msg = mail($this->fetch("email"), 'Password recovery', "click <a href='$dom/fpass/$token'>here</a> this link expires $time");
					
					if ($msg) {
						// create the fpass file bro check the session for what to do!
						return ['status' => 'error', 'msg' => 'A link has been sent to your email address to create a new password!'];
					}
				}*/
			}
		}
	}
	
	public static function authId() {
		return Session::get('authId');
	}
	
	public static function auth($data = '') {
		if ($data)
			return (isset(Session::get('auth')->$data) ? Session::get('auth')->$data : false);
		else
			return Session::get('auth');
	}
	
	private function updateLogin ($id) {
		$this->_e->set(['last_log'], [date('Y-m-d H:i:s', time())])->where($id)->res();
	}
}