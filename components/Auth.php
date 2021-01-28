<?php

class Auth extends Validate {
	private $_log;
	public $table;
	
	public function __construct () {
		$this->_e = Db::instance();
		$this->table = 'auth';
		$this->_e->table($this->table);
	}

	protected function cap () {
		// checking for login attempts if enabled
		$att = config('auth/login_attempts');
		if ($att > 0) {
			// setting captcha info
			if (!Session::check('count')) {
				session('count', 1);
			} else {
				if (session('count') >= $att) {
					if (Session::check('cap')) {
						//if there is a cap ses
						$cap = session('cap');
					} else {
						//else gen new
						$cap = substr(Utils::gen(), 0, 5);
						session('cap', $cap);
					}
					
					if ($this->req('captcha'))
						return $this->capt($cap);
					
					$this->addError(['status' => 'captcha', 'msg' => 'Too many login attempts!','captcha' => $cap]);
					return true;
				} else {
					$c = session('count');
					$c++;
					session('count', $c);
				}
			}
		}
		return false;
	}
	
	private function capt ($cap) { 
			if($this->req('captcha') !== session('cap')) {
				$this->addError(['status' => 'captcha','msg' => 'Captcha error!', 'captcha' => $cap]);
				return true;
			} else {
				Session::del('count');
				Session::del('cap');
			}
	}
	
	public function login (...$fv) {
		if ($this->cap()) {
			return $this;
		} else {
			if(!$this->error()) {
				$id = ['id', 'password, last_log'];
			
			if (config('auth/last_pc') > 0) 
				$id[] = 'datediff(now(), last_pc) as days';
				
			if (config('auth/single') == false)
				$id[] = 'type';
				
				$c = new Crud($this->table);
				$log = $c->fetch($id)
				->with('remove')
				->with('append', ['email'], [req('email')])
				->exec();
				
				// after fetch
				$check = false;
				if ($c->count()) {
					foreach ($log as $k) {
						if  ($k->password) {
							if (password_verify(req('password'), $k->password)) {
								$check = true;
								break;
							}
						}
					}
				}
				
				// if password is found
				if ($check) {
					// do something
					$this->_log = $log[0];
					$this->updateLogin($this->_log->id);
					session('auth', $this->_log);
					
					(req('remember')) ? $r = true : $r = false;
					session('authId', $this->_log->id, $r);
				} else {
					$this->addError('Credentials does not match any account!');
				}
			}
		return $this;
		}
	}
	
	public function reg (...$fv) {
		if ($this->cap()) {
			return $this;
		} else {
			if(!$this->error()) {
				$c = new Crud($this->table);
				$c->validationRule($fv, true);
				$id = $c->create()
				->with('remove', ['type', 'password', 'captcha'])
				->with('append', ['password'], [$this->hash($this->req('password'))])
				->exec();
			
				if (!$c->count()) {
					$this->addError(['status' => 422, 'msg' => 'There is an account with that email address!']);
				} else {
					$this->_log = $id;
					session('authId', $this->_log);
				}
			}
			return $this;
		}
	}
	
	public function set ($red = '/') {
		if ($this->error()) {
			$msg = $this->error();
			if (is_array($msg)) {
				return $msg;
			}
			goto ret;
		} else {
			session_regenerate_id();
			$msg = 'ok';

			// checking for multiple login
			if(config('auth/single') == false)
				$red = $this->_log->type;
			// checking password change option
			$pc = config('auth/last_pc');
			if ($pc > 0)
				if(@$this->_log->days > $pc) {
					$msg = 'change';
					$days = @$this->_log->days;
				}
			}
			ret:
		return ['status' => $msg, 'msg' => $msg, 'days' => @$days, 'redirect' => $red];
	}
	
	public function chpwd (...$fv) {
		if ($this->cap()) return $this->error();
		
		$this->autoValidate($fv, true);
		if ($this->error()) return $this->error();
		
		$e = new Easy();
		$d = $e->table($this->table)->update($this->authId())->with('remove')->with('append', ['last_pc', 'password'], [date('Y-m-d H:i:s', time()), $this->hash($this->req('password'))])->exec();
		
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
					$dom = config('session/domain');
					$time_exp = time() + 60 * 60 * 30;
					$time = date('D, jS \of F, Y h:i:a', $time_exp);
	
					$msg = mail($this->fetch('email'), 'Password recovery', "click <a href='$dom/fpass/$token'>here</a> this link expires $time");
					
					if ($msg) {
						// create the fpass file bro check the session for what to do!
						return ['status' => 'error', 'msg' => 'A link has been sent to your email address to create a new password!'];
					}
				}*/
			}
		}
	}
	
	public static function authId() {
		return session('authId');
	}
	
	public static function auth($data = '') {
		if ($data)
			return (isset(session('auth')->$data) ? session('auth')->$data : false);
		else
			return session('auth');
	}
	
	private function updateLogin ($id) {
		$this->_e->set(['last_log'], [date('Y-m-d H:i:s', time())])->where($id)->res();
	}
	
	public function activeLogin () {
		if (!authId()) {
			if ($active = getCookie('authId')) {
				$this->_log = $this->_e->get()->where($active)->res(1);
				if ($this->_e->count()) {
					Session::set('auth', $this->_log);
					Session::set('authId', $this->_log->id);
					$this->updateLogin(authId());
				}
			}
		}
	}
}