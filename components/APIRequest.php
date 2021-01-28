<?php

class APIRequest {
	private $_ini, $_uri, $_type = 'get', $_error, $_info;
	
	public function url($uri) {
		((!$uri))? $this->_error = 'No url specified!' : $this->_uri = $uri;
		$this->_ini = curl_init($this->_uri);
		return $this;
	}
	
	public function method($m = 'get') {
		switch ($m) {
			case 'put':
				curl_setopt($this->_ini, CURLOPT_PUT, true);
			break;
			case 'post':
				curl_setopt($this->_ini, CURLOPT_POST, true);
			break;
			case 'file':
				curl_setopt($this->_ini, CURLOPT_PUT, true);
			break;
			case 'delete':
				curl_setopt($this->_ini, CURLOPT_CUSTOMREQUEST, 'DELETE');
			break;
			default:
				curl_setopt($this->_ini, CURLOPT_HTTPGET, true);
			break;
		}
		$this->_type = $m;
		return $this;
	}
	
	public function body ($key, $val = true) {
		if (!is_array($key) && !is_array($val)) {
			$key = [$key]; $val = [$val];
		} else {
			if (count($key) !== count($val)) {
				$this->_error = 'Keys and values given for body does not match!';
				return;
			}
		}
		
		$body = array_combine($key, $val);
		
		if ($this->_type === 'get' && $this->_type === 'delete') {
			(is_bool($val[0]))? $this->_uri .= '/'. $key[0] : $this->_uri .= '?' . http_build_query($body);
			$this->url($this->_uri);
			return $this;
		}
		curl_setopt($this->_ini, CURLOPT_POSTFIELDS, $body);
		return $this;
	}
	
	public function file ($loc) {
		$t = $this->_type;
		if ($t != 'put' && $t != 'post') {
			$this->_error = 'Put or Post method is required for file upload!';
		} else {
			if (!file_exists($loc)) {
				$this->_error = 'Cannot find the given file!';
			} else {
				$size = filesize($loc);
				$file = fopen($loc, 'r');
				curl_setopt_array($this->_ini, array_combine([CURLOPT_INFILE, CURLOPT_INFILESIZE], [$file, $size]));
			}
		}
		return $this;
	}
	
	public function headers ($head) {
		if (!is_array($header)) {
			$this->_error = 'The header method expects an array as parameter';
		} else {
			curl_setopt($this->_ini, CURLOPT_HTTPHEADER, $head);
		}
	}
	
	public function send($c = true) {
		curl_setopt_array($this->_ini, array_combine([CURLOPT_RETURNTRANSFER, CURLOPT_HEADER], [true, false]));
		$res = curl_exec($this->_ini);
		
		if (curl_error($this->_ini)) 
			$this->_error = curl_error($this->_ini);
			
		// meta
		
		$this->_info = curl_getinfo($this->_ini);
		
		if ($c)
			$res = Utils::djson($res);
			
		curl_close($this->_ini);
		return $res;
	}
	
	public function info () {
		return (object) $this->_info;
	}
	
	public function error () {
		return $this->_error;
	}
}