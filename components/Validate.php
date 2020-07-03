<?php

class Validate {
	protected $_pass = false, $_file = "", $_errors = [];
	
	public function validator($src, $fields = array()){
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
					$this->addError("$field_name is required");
				}else{
					switch($rule){
						case 'csrf':
							$this->validate_csrf($input);
							break; 
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
									$this->addError("Maximum character exceeded for $field_name field");
								}
							else 
								if ($input > $value) {
									$this->addError("$field_name is greater than $value");
								}
							break;
						case "min":
							if(!is_numeric($input))
								if(strlen($input) < $value){
									$this->addError("$field_name should be at least minimum of $value character!");
								}
							else
								if ($input < $value) {
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
		
		if(empty($this->_errors)){
			$this->_pass = true;
		}
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
	
	/* validating http request */

	public function val_req (...$rules) {
		if ($this->req()) {
			$keys = $val = [];
			$i = 0;

			foreach ($this->req() as $key => $value) {
				if ($key == "csrf") {
					$rule = ["csrf" => true];
				} else {
					$rule = ["required" => true];
					if (is_array($value))
						$rule = ["multiple" => true];
				}

				if (!empty($rules[$i])) {
					$rule = $rules[$i];
				}
				$this->validator($this->req(), [
					$key => $rule
				]);
				// removing csrf key and captcha
				if($key != "csrf" && $key != "captcha") {
					array_push($keys, $key);
					array_push($val, $value);
				}
				$i++;
			}

			$this->filter_array($keys);
			$this->filter_array($val);
			$forked = [$keys, $val];
			if ($_SERVER["SERVER_NAME"] != Config::get("session/domain")) {
				$this->addError("Error understanding this URI");
			}
			return $forked;
		}
		return false;
	}

	// user validation ends here

	// file upload validation begins here

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
		$hash = str_split($hash, 2);
		$hash = "$hash[2] $hash[0] $hash[1]";
		return hash("sha256", $hash);
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
	
	public function filter($str){
		if (is_array($str)) {
			return array_map([$this, "filter"], $str);
		}
		
		return @htmlentities(trim((@ucfirst($str))), ENT_QUOTES, "utf-8", false);
	}

	public static function csrf($c = true) {
		if (!Session::check("csrf")) {
			$ses = Session::set("csrf", substr(self::hash(Utils::gen()), 0, 21));
			if ($c) {
				substr(Session::set("expires", time() + 30 * 60), 0, 21);
			}
		} else {
			$ses = Session::get("csrf");
		}


		$html = <<<__here
		<input type="hidden" name="csrf" value="$ses"/>
__here;
		return $html;
	}

	public function validate_csrf ($str) {
		if (Session::check("csrf")) {
			if (Session::check("expires")) {
				if (time() > Session::get("expires")) {
					self::addError("Form has expired, try again!");
					Session::del("expires");
				} else {
					if ($str === Session::get("csrf")) {
						return true;
					} else {
						$this->addError("Request error!");
					}
				}
			} else {
				if ($str === Session::get("csrf")) {
					return true;
				} else {
					$this->addError("Request error!");
				}
			}
		} else {
			$this->addError("Request denied!");
		}
	}

	// this method initialize and setup the captcha process

	public function captcha () {
		if (Session::check("security_check")) {
			return Session::get("security_check");
		} else {
			$cap = substr($this->hash(Utils::gen()), 0, 5);
			Session::set("security_check", $cap);
			return $cap;
		}
	}

	public function v_captcha ($req = "captcha") {
		if (Session::check("security_check")) {
			if ($this->req($req)) {
				if($this->req($req) === Session::get("security_check")) {
					Session::del("security_check");
					return true;
				}
			}
		}
		return false;
	}
}