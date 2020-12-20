<?php

class Validate {
	protected $_pass = false, $_file = "", $_errors = [];
	
	public function validator($src, $fields = []){
		if ($_SERVER["SERVER_NAME"] != Config::get("session/domain")) {
			$this->addError(['msg' => 'Error understanding this URI', 'status' => 404]);
		} elseif (!$this->req('__csrf')) {
			if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !$_SERVER['QUERY_STRING'])
				$this->addError(['msg' => 'Csrf not found in form!', 'status' => 419]);
		} else {
			if (empty($this->error())) {
				$csrf = $this->validate_csrf($this->req('__csrf'));
				if ($csrf === false) {
					//$this->addError(['status' => 422, 'msg' => 'Csrf token mismatch, refresh the page!']);
					return;
				} else {
					// other validations may now follow!
					foreach($fields as $field => $options){
					$input = @trim($src[$field]);
					foreach($options as $rule => $value){
						if(empty($option["field"])  && empty($option["error"])){
							$field_name = ucfirst($field);
							$field_error = "";
						} else {
							$field_name = ucfirst($options['field']);
							$field_error = ucfirst($options['error']);
						}
						if($rule == "required" && empty($input)){
							$this->addError("$field_name cannot be empty!");
						}else{
							switch($rule){
								case "email":
									if(!strpos($input, ".") || !strpos($input, "@")){
										$this->addError("Invalid email address!");
									}
									break;
								case "match":
									if($input !== $src[$value]){
										$this->addError("Password do not match!");
									}
									break;
								case "max":
									if (!is_numeric($input))
										if(strlen($input) > $value){
											$this->addError("Maximum characters exceeded for $field_name field");
										}
									else 
										if ($input > $value) {
											$this->addError("$field_name is greater than $value");
										}
									break;
								case "min":
									if(!is_numeric($input)) {
										if(strlen($input) < $value)
											$this->addError("$field_name should be at least minimum of $value characters!");
									} else {
										if ($input < $value) 
											$this->addError("$field_name value is less than $value");
									}
									break;
								case "number":
									if(!is_numeric($input)){
										$this->addError("$field_name should have a numeric value!");
									}
									break;
								case "unique":
									$d = Db::instance();
									$d->table($value);
									$d->get(["id"])->where([$field, $input])->res(1);
									if($d->count())
										$this->addError($field_name . " exists, try another!");
									break;
								case "wordcount":
									$cal = $value - str_word_count($input);
									if(str_word_count($input) < $value){
										$this->addError("$field_name should have at least $value words! Remain $cal.");
									}
									break;
								case "multiple":
									if(!count(array_filter($src[$field]))){
										$this->addError("{$field_name} is required!");
									}
							}
						}
					}
				}			
				}
			}
		}
		
		if(!empty($this->_errors)){
			$this->_pass = true;
		}
	}
		
	/* auto validating http request */

	public function autoValidate (...$rules) {
			$keys = $val = [];
			$i = 0;
			
			if (end($rules) === true) {
				array_pop($rules);
				$rules = $rules[0];
			}
			
			foreach ($this->req() as $key => $value) {
				$rule = null;
				if ($key != '__csrf') {
					$rule = ["required" => true];
					if (is_array($value))
						$rule = ["multiple" => true];
						
					if (!empty($rules)) {
						if (@$rules[$i])
							$rule = $rules[$i];
					}
					$this->validator($this->req(), [
						$key => $rule
					]);
					if ($this->error())
						break;
					
					// removing csrf key
					if($key != "__csrf") {
						array_push($keys, $key);
						array_push($val, $value);
					}
					$i++;
				}
			}
			$this->filter_array($keys);
			$this->filter_array($val);
			$forked = [$keys, $val];
			return $forked;
	}
	
	public function filter_str ($str) {
		preg_match_all('/^--/i', $str, $match, PREG_OFFSET_CAPTURE);
		
		print_r($match);
	}
	
	

	/* http request handler */
	
	public static function req ($r = "") {
		if (!empty($_POST)) {
			$req = $_POST;
		} elseif (!empty($_GET)) {
			$req = $_GET;
		} else {
			return false;
		}

		if ($r) {
			if (@$req[$r]) {
				$req = $req[$r];
			} else {
				$req = false;
			}
		}

		return $req;
	}

	private function img_comp ($file, $dest) {
		$mm = getimagesize($file)["mime"];

		if ($mm == "image/jpeg") {
			$img = imagecreatefromjpeg($file);
		} elseif ($mm == "image/bmp") {
			$img = imagecreatefromwbmp($file);
		} elseif ($mm == "image/gif") {
			$img = imagecreatefromgif($file);
		} elseif ($mm == "image/png") {
			$img = imagecreatefrompng($file);
		}

		if (imagejpeg($img, $dest, 30))
			return $dest;
		else
			return false;
	}
	
	public function uploader($data){
		$src = $_FILES;
		$folder = "assets/tmp/";
		if (!is_dir($folder)) 
			mkdir($folder);

		$file = $src[$data];
		$tmp = $file['tmp_name'];
		$name = $file['name'];
		$type = $file['type'];
		$size = $file['size'];
		$count = count($name);
		
		if (empty($name[0])) {
			$this->addError("Kindly select a file!");
		} else {
			if ($count > config::get("file-upload/max-file-upload")) {
				$count = config::get("file-upload/max-file-upload");
			}
			for ($i = 0; $i < $count; $i++) {
				if (!empty($name[$i])) {
					if (!Utils::get_type($tmp[$i])){
						$this->addError("$name[$i] type not supported!");
					} else {
						if (config::get("file-upload/rename-file")) {
							$name = config::get("project/name") . "_" . Utils::gen() . "_" . time();
						} else {
							$name = $name[$i];
						}
						if (Utils::get_type($tmp[$i]) == "image") {
							// image compresson
							$img = $this->img_comp($tmp[$i], $folder.$name);
						} elseif (Utils::get_type($tmp[$i]) == "video" || Utils::get_type($tmp[$i]) == "audio") {
							move_uploaded_file($tmp[$i], $folder.$name);
							$img = $folder.$name;
						} else {
							$this->addError("$name[$i] type not supported!");
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
	
	public function complete_upload($dest = "assets/img/"){
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
	
	public static function _hash ($hash) {
		return hash_hmac('haval160,4', $hash, 'tlight is lit');
	}
	
	public function addError($error = ""){
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
				return array_filter(array_map([$this, "filter"], $_POST[$data]));
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
	
	public function filter_array ($arr) {
		return array_map([$this, "filter"], $arr);
	}
	
	public static function filter($str){
		if (is_array($str)) {
			return array_map([Validate::class, "filter"], $str);
		}
		
		return @htmlentities(trim((@ucfirst($str))), ENT_QUOTES, "utf-8", false);
	}
	
	// bug is here 
	public static function csrf() {
		// if there aint an active csrf
		if (!Session::get("csrf_token")) {
			$ses = Session::set("csrf_token", substr(self::_hash(Utils::gen()), 0, 32));
			Session::set("expires", time() + 60 * 60);
		} else {
			// if there is
			$ses = Session::get("csrf_token");
		}
		
		$html = <<<__here
		<div><label>csrf</label><input type="hidden" name="__csrf" value="$ses" id="__csrf"/></div>
__here;
		return $html;
	}

	public function validate_csrf ($str) {
		if (Session::check("csrf_token")) {
			// check if the csrf token has timed out
			if (time() > Session::get("expires")) {
				self::addError(['msg' => 'Form has expired, refresh the page!', 'status' => 419]);
				Session::del("expires");
				Session::del('csrf_token');
			} else {
				// if still active
				if ($str === Session::get("csrf_token")) {
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
}