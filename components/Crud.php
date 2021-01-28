<?php

class Crud extends Db {
	protected $_method, $_col = [], $_inp = [], $_v, $_val, $_op;
	
	public function __construct ($tab = null) {
		parent::__construct();
		$this->_v = new Validate();
		$this->table($tab);
	}
	
	
	public function validationRule (...$rules) {
		if (end($rules) === true) {
			array_pop($rules);
			$rules = $rules[0];
		}
		
		list($this->_col, $this->_inp) = $this->_v->autoValidate($rules, true);
		if ($this->_v->error())
			$this->_error = $this->_v->error();
		return $this;
	}

	/*
	all the methods in this class works the request object and all user input is already validated!
	*/

	/*

	this method argument can be with the class "with()" method!

	*/

	public function unique ($key, $val = '') {
		$this->_error = null;
		if (!$val) {
			$val = req($key);
		}
		
		$this->get(['id'])->where([$key, $val])->res();

		if ($this->count()) 
			return true;
		
		return false;
	}

	public function create () {
		if (!$this->error()){
			// if the validationRule has not been called
			if (!$this->_col)
				$this->validationRule();
			$this->add($this->_col, $this->_inp);
			$this->_method = 'create';
		}
		return $this;
	}

	/*	FETCH	*/


	/*
	the default selection is to select all from the given table!

	but this can be overridden by specifying the columns to select


	This also doesnt take any argument except only if the form or the url that holds the input doesn't not match with the keys of the url or names of the form.

	example

	db column is user but the url holds id=10
	so for this reason it should be specified as in the normal where for the db class e.g ["user", "=", 10];

	and this method can only take only 2 arrays for "where", same thing for the url!

	for example
	** url
	id=1&user=10 by default and will be used to join it e.g where id=1 and user=10

	*/

	public function fetch ($cols = ['*'], ...$where) {
		if(!$this->_col)
			$this->validationRule();
		
		if (!$this->_error) {
			$this->get($cols);
			
			//checking to remove more as its reserved for pagination, so that it wont use it for where clause;
			if ($this->_col){
				$key = array_search('page', $this->_col);
		
				if ($key !== false){
					array_splice($this->_col, $key);
					array_splice($this->_inp, $key);
				}
			}
			// pass true arg to use orWhere if there is request
			
			// there is a request and wanna use orWhere
			if (req()) {
				$wia = $this->with_supp($this->_col, $this->_inp);
				
				if($where) {
					$this->orWhere($wia, true);
					$this->_lastid = $where;
				}else{
					$this->where($wia, true);
				}
			}
				
			$this->_method = 'fetch';
		}
		return $this;
	}
	
	/*	UPDATE	*/
	
	/*
	
	this method collect the data to be updated in the request method
	
	*/
	public function update (...$where) {
		if (empty($_POST)) {
			$this->_error = 'Update is via POST method only!';
		} else {
			if (!$this->_col)
				$this->validationRule();
			
			if (!$this->error()) {
				$this->set($this->_col, $this->_inp);
				if ($where) {
					$this->where($this->_val = $where, true);
				}
				$this->_method = 'update';
			}
		}
		return $this;
	}

	public function delete ($c = false) {
		if (!$this->_col)
			$this->validationRule();
			

		if(!$this->_error) {
			$this->rm();
			
			if ($c) {
				$this->_lastid = $c;
				$this->orWhere($this->with_supp($this->_col, $this->_inp), true);
			} else {
				$this->where($this->with_supp($this->_col, $this->_inp), true);
			}
			$this->_method = 'delete';
		}
		return $this;
	}

	public function with ($type, ...$var) {
		if (!$this->error()) {
			switch ($type) {
				case 'append':
				if (count($var) == 2)
					list($col, $val) = $var;
				else
					list($col, $eq, $val) = $var;
	
					if (is_numeric($val))
						$val = [$val];
	
					if (count($this->_col) && isset($eq)) {
						if (count($eq) == 1) {
							$eq = [1 => $eq[0]];
						}  elseif (count($eq) > 1) {
							$e = []; $i = -1;
							foreach ($eq as $key) {
								$e[] = ['i' => count($col) + $i, 'aa' => $key];$i++;
							}
							$eq = array_column($e, 'aa', 'i');
						}
					}
	
					$this->_col = array_merge($this->_col, $col);
					$this->_inp = array_merge($this->_inp, $val);
					break;
				case 'remove':
					if (empty($var[0])) {
						$this->_col = $this->_inp = [];
					} else {
						foreach($var[0] as $k) {
							$key = array_search($k, $this->_col);
							if ($key !== false) {
								unset($this->_col[$key]);
								unset($this->_inp[$key]);
							}
						}
					}
			}
			// resetting here 
			if ($this->_col) {
				$this->_sql = @strstr($this->_sql, 'where', true);
				$this->_query_value = [];
			}
	
	
			switch ($this->_method) {
				case 'create':
					$this->add($this->_col, $this->_inp);
					break;
				case 'update':
					$this->set($this->_col, $this->_inp);
					break;
				case 'fetch': 
					if (count($this->_col)) {
						if ($this->_lastid)
							$this->orWhere($this->with_supp($this->_col, $this->_inp, @$eq), true);
						else
							$this->where($this->with_supp($this->_col, $this->_inp, @$eq), true);
					}
					break;
				case 'delete':
					if ($this->_col)
						if ($this->_lastid)
							$this->orWhere($this->with_supp($this->_col, $this->_inp, @$eq), true);
						else
							$this->where($this->with_supp($this->_col, $this->_inp, @$eq), true);
			}
		}
		return $this;
	}
	
	public function using (array $id) {
		if ($id && !$this->error()) {
			$where = [];
			foreach ($id as $key) {
				$k = array_search($key, $this->_col);
					if ($k !== false)
						$where[] = [$this->_col[$k], $this->_inp[$k]];
			}

			if ($where) {
				$this->where($where, true);
			}
		}
	}

	public function with_supp ($col, $val, $op = '') {
		$f = [];

		foreach ($col as $k => $v) {
			if ($op) {
				if (@$op[$k]) {
					$w = [$v, $op[$k], $val[$k]];
				} else {
					$w = [$v, $val[$k]];
				}
				$f[] = $w;
			} else {
				$f[] = [$col[$k], $val[$k]];
			}
		}
		return $f;
	}

	public function exec($check = false) {
		
		if ($this->_error) {
			return $this->error();
		} else {
			$res = $this->res($check);
			if ($this->_error) {
				return $this->_error;
			} else {
				$this->_col = $this->_inp = $this->_method = null;
				return $res;
			}
		}
	}
}