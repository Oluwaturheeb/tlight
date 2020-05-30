<?php

class Easy extends Validate {
	private $_tab, $_db, $_sql, $_method, $_col = [], $_inp = [], $_fcol;
	
	public function __construct () {
		$this->_db = Db::instance();
	}
	
	public function table ($name) {
		$this->_tab = $name;
		$this->_db->table($name);
	}

	/*
	all the methods in this class works the request object and all user input is already validated!
	*/

	/*

	this method argument can be with the class "with()" method!

	*/

	public function create () {
		$d = $this->_db;
		list($this->_col, $this->_inp) = $this->val_req();
	  if (!$this->error()){
			$this->_db->add($this->_col, $this->_inp);
			$this->_method = "create";
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

	** arg 
	fetch(cols, [id, 1], [user, 10], "or") // same as the url example except on can override the join to be "or"

	*/

	public function fetch ($cols = ["*"], ...$where) {
		if ($this->req())
			list($this->_col, $this->_inp) = $this->val_req();
			if (!$this->error()) {
				$d = $this->_db;
				$d->get($cols);
				$this->_fcol = $cols;
				$this->_method = "fetch";
				//checking to remove more as its reserved for pagination, so that it wont use it for where clause;
				if ($this->_col){
					$key = array_search("more", $this->_col);
			
					if ($key !== false){
						array_splice($this->_col, $key);
						array_splice($this->_inp, $key);
					}
				}
				// ends here
				
				if (count($where)) {
					$this->_col = $this->_inp = [];
					foreach ($where as $k => $v) {
						$this->_col[] = $v[0];
						if (count($v) == 2) {
							$this->_inp[] = $v[1];
						} elseif (count($v) > 2) {
							$this->_inp[] = $v[2];
						}
					}
					if(!is_array(end($where)) && !is_numeric($where)) {
						$d->concat(end($where));
						array_pop($where);
					}
					$d->where($where, true);
					$this->_sql = $where;
				}
				$this->_fcol = $cols;
				$this->_method = "fetch";
			}

		return $this;
	}

	public function with_supp ($col, $val) {
		$f = [];

		for ($i = 0; $i < count($col); $i++) {
			$f[] = [$col[$i], $val[$i]];
		}
		return $f;
	}
	
	/*	UPDATE	*/
	
	/*
	
	this method collect the data to be updated in the request method with an optional column names only if the form/url names/keys doesnt match what is in the db this can be good for security
	
	*/
	public function update (...$where) {
		$d = $this->_db;
		if ($this->req() && !empty($_POST)) {
			list($this->_col, $this->_inp) = $this->val_req();
			if (!$this->error()) {
				$d->set($this->_col, $this->_inp);
				
				if ($where) {

					if (is_numeric($where[0])){
						$d->where($where[0]);
					} else {
						$d->where($where, true);
					}

					$this->_sql = $where;
				}

				$this->_method = "update";

			}
			return $this;
		}
	}

	public function with ($type, ...$var) {
		if (!$this->error()) {
			switch ($type) {
				case 'pages':
					$this->_db->sort()->pages($var[0], "more");
					break;
				case "append":
					list($col, $val) = $var;
	
					if (is_numeric($val))
						$val = [$val];
	
					$this->_col = array_merge($this->_col, $col);
					$this->_inp = array_merge($this->_inp, $val);
					break;
				case "remove":
					if (empty($var[0])) {
						$this->_col = $this->_inp = [];
					} else {
						foreach($var[0] as $k) {
							$key = array_search($k, $this->_col, true);
							unset($this->_col[$key]);
							unset($this->_inp[$key]);
						}
					}
					
					break;
				case "use":
					$use = $var[0];
					$where = [];
	
					foreach ($use as $key) {
						$k = array_search($key, $this->_col);
	
						if ($k !== false) {
							$where[] = [$this->_col[$k], $this->_inp[$k]];
						}
					}
					$this->_sql = $where;

					if (!empty($where))
						$this->_db->where($where, true);
					break;
				case "change":
					$this->_col = $var[0];
					break;
			}
			
			# resetting the db class here!
			
			$db = $this->_db = new Db();
			$db->table($this->_tab);
			
			switch ($this->_method) {
				case 'create':
					$db->add($this->_col, $this->_inp);
					break;
				case "update":
					$db->set($this->_col, $this->_inp);
	
					if ($this->_sql) {
						$db->where($this->_sql, true);
					}
					break;
				case 'fetch': 
					if ($this->_sql)
						if(!is_array(end($this->_sql)) && !is_numeric($this->_sql)) {
							$c = $db->concat(end($this->_sql));
						}
						
					$db->get($this->_fcol);
					
					if (count($this->_col) == 1) {
						$db->where([$this->_col[0], $this->_inp[0]]);
					} elseif (count($this->_col) > 1) {
						$db->where([$this->_col[0], $this->_inp[0]], [$this->_col[1], $this->_inp[1]]);
					}
					break;
			}
		}
		return $this;
	}

	public function del ($con = "", $cols = [], $ops = []) {
		$d = $this->_db;
		list($col, $val) = $this->val_req();
		if(!$this->error()) {
			$d->rm();
			if ($con) {
				$d->concat($con);
			}
			
			if($cols) {
				$col1 = [$cols[0], $val[0]];
				$col2 = [];
				
				if ($ops) {
					$col1 = [$cols[0], $ops[0], $val[0]];
				}
				if (count($cols) > 1) {
					$col2 = [$cols[1], $val[1]];
					if ($ops) {
						$col2 = [$cols[1], $ops[1], $val[1]];
					}
				}
			} else {
				$col2 = [];
				$col1 = [$col[0], $val[0]];
				if (count($col) > 1) {
					$col2 = [$col[1], $val[1]];
				}
			}
		}
		return $this;
	}

	public function exec($check = false) {
		if ($this->error()) {
			return $this->error();
		} else {
			$res = $this->_db->res($check);
			$this->_sql = $this->_col = $this->_inp = $this->_method = null;
			$this->addError($this->_db->error());
			return $res;
		}
	}
}