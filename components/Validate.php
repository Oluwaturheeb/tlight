<?php

class Validate {
	protected $_pass = false, $_file = '', $_errors = [];
	public $validated;
	
	public function validator($src, $fields = [], bool $stack = false){
		if (!req('__csrf')) {
			if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !$_SERVER['QUERY_STRING'])
				$this->addError(['msg' => 'Csrf not found in form!', 'status' => 419]);
		} else {
			if (empty($this->error())) {
				$csrf = $this->validate_csrf($this->req('__csrf'));
				if ($csrf === false) {
					return;
				} else {
					// other validations may now follow!
					// ini errors
					$dError = [];
					foreach ($fields as $field => $options) {
						$input = @trim($src[$field]);
						foreach($options as $rule => $value){
							// check error report type
							if (!$stack)
								if ($dError)
									break;
							
							// checking custom error
							if(empty($options['error'])){
								$error = '';
							} else {
								$error = ucfirst($options['error']);
							}
							// if the field is optional
							
							if (!isset($options['null'])) {
								// check for field name
								if (isset($options['name'])) {
									$field_name = $options['name'];
								} else {
									$field_name = $field;
								}
								
								//execute other rules
								if($rule == 'required' && empty($input)){
									if (!$error)
										$def .= $field_name. ' cannot be empty!';
								}else{
									// default error 
									$def ='';
									switch($rule){
										case 'email':
											if(!strpos($input, '.') || !strpos($input, '@')){
												$def .= 'Invalid email address!';
											}
											break;
										case 'match':
											if($input !== $src[$value]){
												$def .= 'Password do not match!';
											}
											break;
										case 'max':
											if (!is_numeric($input))
												if(strlen($input) > $value){
													$def .= 'Maximum characters exceeded for ' .$field_name. ' field!';
												}
											else 
												if ($input > $value) {
													$def .= $field_name.' is greater than '.$value;
												}
											break;
										case 'min':
											if(!is_numeric($input)) {
												if(strlen($input) < $value)
													$def .= $field_name.' should be at least minimum of '. $value .' characters!';
											} else {
												if ($input < $value) 
													$def .= $field_name.' value is less than '.$value;
											}
											break;
										case 'number':
											if(!is_numeric($input)){
												$def .= $field_name.' should have a numeric value!';
											}
											break;
										case 'unique':
											$d = (new Crud($value))->unique($field);
											if($d)
												$def .= $field_name . ' exists, try another!';
											break;
										case 'wordcount':
											$cal = $value - str_word_count($input, 0, '@._');
											if(str_word_count($input, 0, '@._') < $value){
												$def .= $field_name.' should have at least '.$value.' words! Remain '.$cal;
											}
											break;
										case 'multiple':
											if(!count(array_filter($src[$field]))) {
												$def .= $field_name.' is required!';
											}
											break;
										case 'cap':
											$input = ucfirst($input);
										break;
										case 'capword':
											$input = ucwords($input);
										break;
									}
								}
							}
							//subbing errors
							if ($def) {
								if ($error)
									$dError[] = $error;
								else
									$dError[] = $def;
							}
							$def = null;
						}
						$key[] = $field_name;
						$val[] = $input;
					}			
				}
			}
		}
		// formatting error output
		if($dError) {
			$this->_pass = true;
			if (!$stack)
				$this->addError($dError[0]);
			else
				$this->addError($dError);
		} else {
			$this->validated = (object) array_combine($this->filter($key), $this->filter($val));
		}
	}
		
	/* auto validating http request */

	public function autoValidate (...$rules) {
			$keys = $name = $val = [];
			$i = 0;
			
			if (end($rules) === true) {
				array_pop($rules);
				$rules = $rules[0];
			}
			
			foreach (req() as $key => $value) {
				$rule = $c = null;
				if ($key != '__csrf') {
					$rule = ['required' => true];
					if (is_array($value))
						$rule = ['multiple' => true];
					
					if (!empty($rules)) {
						if (isset($rules[$i])) {
							$rule = $rules[$i];
							
						if (isset($rule['name'])) $c = true;
						}
					}
					$this->validator($this->req(), [
						$key => $rule
					]);
					if ($this->error())
						break;
					
					// removing csrf key
					if($key != '__csrf') {
						if ($c) $key = $rule['name'];
						array_push($keys, $key);
						array_push($val, $value);
					}
					$i++;
				}
			}
			$this->validated = null;
			return [$this->filter($keys), $this->filter($val)];
	}

	/* http request handler */
	
	public static function req ($r = '') {
		if (!empty($_POST)) {
			$req = $_POST;
		} elseif (!empty($_GET)) {
			$req = $_GET;
		} else {
			return false;
		}

		if ($r) {
			(isset($req[$r])) ? $req = $req[$r] : $req = false;
		}

		return $req;
	}

	private function img_comp ($file, $dest) {
		$mm = getimagesize($file)['mime'];

		if ($mm == 'image/jpeg') {
			$img = imagecreatefromjpeg($file);
		} elseif ($mm == 'image/bmp') {
			$img = imagecreatefromwbmp($file);
		} elseif ($mm == 'image/gif') {
			$img = imagecreatefromgif($file);
		} elseif ($mm == 'image/png') {
			$img = imagecreatefrompng($file);
		}

		if (imagejpeg($img, $dest, 30))
			return $dest;
		else
			return false;
	}
	
	public function uploader($data){
		$src = $_FILES;
		$folder = 'assets/tmp/';
		if (!is_dir($folder)) 
			mkdir($folder);

		$file = $src[$data];
		$tmp = $file['tmp_name'];
		$name = $file['name'];
		$type = $file['type'];
		$size = $file['size'];
		$count = count($name);
		
		if (empty($name[0])) {
			$this->addError('Kindly select a file!');
		} else {
			if ($count > config::get('file-upload/max-file-upload')) {
				$count = config::get('file-upload/max-file-upload');
			}
			for ($i = 0; $i < $count; $i++) {
				if (!empty($name[$i])) {
					if (!Utils::get_type($tmp[$i])){
						$this->addError($name[$i].' type not supported!');
					} else {
						if (config::get('file-upload/rename-file')) {
							$name = config::get('project/name') . '_' . Utils::gen() . '_' . time();
						} else {
							$name = $name[$i];
						}
						if (Utils::get_type($tmp[$i]) == 'image') {
							// image compresson
							$img = $this->img_comp($tmp[$i], $folder.$name);
						} elseif (Utils::get_type($tmp[$i]) == 'video' || Utils::get_type($tmp[$i]) == 'audio') {
							move_uploaded_file($tmp[$i], $folder.$name);
							$img = $folder.$name;
						} else {
							$this->addError($name[$i].' type not supported!');
						}

						if ($count == 1) {
							$this->_file = [$img, $name];
						} else {
							$this->_file[] = [$img, $name];
						}
					}
				}
			}
		}
		return $this;
	}

	public function preview () {
		return $this->_file[0];
	}
	
	public function complete_upload($dest = 'assets/img/'){
		if ($this->_errors) {
			return $this->error();
		} else {
			if (!is_dir($dest))
				mkdir($dest);

			$files = $this->_file;
			
			if (!is_array($files[0])) {
				if (rename($files[0], $dest.$files[1]))
					return $dest.$files[1];
			} else {
				$file = [];
				for ($i = 0, $count = count($this->_file); $i < $count; $i++) {
					if (rename($files[0], $dest.$files[1]))
						$file[] = $dest.$files[1];
				}

				return $file;
			}
		}
	}
	
	public static function hash ($hash) {
		return password_hash($hash, PASSWORD_BCRYPT);
	}
	
	public static function _hash ($hash, $len = 0) {
		$hash = hash_hmac('haval160,4', $hash, 'tlight is lit');
		
		if ($len)
			return substr($hash, 0, $len);
			
		return $hash;
	}
	
	public function addError($error = ''){
		$this->_errors[] = $error;
	}
	
	public function error() {
		return end($this->_errors);
	}
	
	public function pass(){
		return $this->_pass;
	}

	public function fetch($data){
		if(!empty($_POST)){
			if(is_array($_POST[$data])){
				return array_filter(array_map([$this, 'filter'], $_POST[$data]));
			}else{
				return $this->filter($_POST[$data]);
			}
		}elseif(!empty($_GET)){
			if(is_array($_GET[$data])){
				return array_filter($_GET[$data]);
			}else{
				return $this->filter($_GET[$data]);
			}
		}
		return false;
	}
	
	public static function filter($str){
		if (!$str) return;
		
		if (is_array($str))
			return array_map([Validate::class, 'filter'], $str);
		
		return htmlentities(trim(self::filter_str($str)), ENT_QUOTES, 'utf-8', false);
	}
	
	private static function filter_str ($str) {
		$si = ['/*' => '', '*/' => '', '==' => '', 'select * from' => '', 'drop table' => '', 'drop database' => '', 'delete from' => '', 'display:none' => '', 'display: none' => '', '--' => '- -', ' --' => '', '-- '=> ''];
		$str = strtr($str, $si);
		return $str;
	}
	
	public static function csrf() {
		// if there aint an active csrf
		if (!Session::check('csrf_token')) {
			$ses = substr(self::_hash(Utils::gen()), 0, 32);
			Session::set('csrf_token', $ses);
			Session::set('expires', time() + 60 * 30);
		} else {
			// if there is
			$ses = Session::get('csrf_token');
		}
		
		$html = <<<__here
		<div><label style="display: none">csrf</label><input type="hidden" name="__csrf" value="$ses" id="__csrf"/></div>
__here;
		return $html;
	}

	public function validate_csrf ($str) {
		if (Session::check('csrf_token')) {
			// check if the csrf token has timed out
			if (time() > Session::get('expires')) {
				self::addError(['msg' => 'Form has expired, refresh the page!', 'status' => 419]);
				Session::del('expires');
				Session::del('csrf_token');
			} else {
				// if still active
				if ($str === Session::get('csrf_token')) {
					return true;
				} else {
					$this->addError(['status' => 419, 'msg' => 'Csrf token error, refresh the page!']);
				}
			}
		} else {
			$this->addError(['status' => 419, 'msg' => 'Csrf token error, refresh the page!']);
		}
		return false;
	}
	
	public static function sameSite () {
		if (isset($_SERVER['HTTP_ORIGIN']))
			if ($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'] !== $_SERVER['HTTP_ORIGIN'])
			if (!strstr($_SERVER['REQUEST_URI'], '/api/'))
				return true;
		return false;
	}
}