<?php
#namespace Easy;

class Easy extends Db {
	protected $_method, $_col = [], $_inp = [], $_v, $_val, $_pages;
	
	public function __construct () {
		parent::__construct();
		$this->_v = new Validate();
	}

	/*
	all the methods in this class works the request object and all user input is already validated!
	*/

	/*

	this method argument can be with the class "with()" method!

	*/

	public function unique ($col) {
		$this->_error = null;
		if (is_array($col)) {
			$this->get(["id"])->where([$col[0], $this->_v->req($col[0])], [$col[1], $this->_v->req($col[1])])->res();
		} else {
			$this->get(["id"])->where([$col, $this->_v->req($col)])->res();
		}

		if ($this->count()) 
			return true;
		
		return false;
	}

	public function create () {
		$v = $this->_v;
		list($this->_col, $this->_inp) = $v->val_req();
		if (!$v->pass()){
			$this->_error = $v->error();
		} else {
			$this->add($this->_col, $this->_inp);
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
		$val = $this->_v;

		if ($val->req()) {
			list($this->_col, $this->_inp) = $val->val_req();
			if ($val->pass()) {
				$this->_error = $val->error();
			}
		}

		if (!$this->_error) {
			$this->get($cols);
			//checking to remove more as its reserved for pagination, so that it wont use it for where clause;
			
			if ($this->_col){
				$key = array_search("page", $this->_col);
		
				if ($key !== false){
					array_splice($this->_col, $key);
					array_splice($this->_inp, $key);
				}
			}
			// ends here
			
			if (count($where)) {
				$this->_col = $this->_inp = [];
				if(!is_array(end($where)) && !is_numeric($where)) {
					$this->concat(end($where));
					array_pop($where);
				}
				foreach ($where as $k => $v) {
					$this->_col[] = $v[0];
					if (count($v) == 2) {
						$this->_inp[] = $v[1];
					} elseif (count($v) > 2) {
						$this->_inp[] = $v[2];
					}
				}
				$this->where($where, true);
				$this->_lastid = $where;
			} elseif ($this->_col) {
				$this->_lastid = $this->with_supp($this->_col, $this->_inp);
				$this->where($this->_lastid, true);
			}
			$this->_method = "fetch";
		}

		return $this;
	}
	
	/*	UPDATE	*/
	
	/*
	
	this method collect the data to be updated in the request method with an optional column names only if the form/url names/keys doesnt match what is in the db this can be good for security
	
	*/
	public function update (...$where) {
		$v = $this->_v;
		if ($v->req() && !empty($_POST)) {
			list($this->_col, $this->_inp) = $v->val_req();
			if (!$v->pass()) {
				$this->error = $v->error();
			} else {
				$this->set($this->_col, $this->_inp);
				
				if ($where) {
					$this->where($this->_val = $where, true);
				}

				$this->_method = "update";

			}
			return $this;
		}
	}

	public function del ($con = [], $ops = []) {
		$v = $this->_v;
		if ($v->req()) {
			list($this->_col, $this->_inp) = $v->val_req();
			if ($v->pass()) {
				$this->_error = $v->error();
			}
		}

		if(!$this->_error) {
			$this->rm();
			if ($con) {
				$d->concat($con);
			}

			$this->where($this->_val = $this->with_supp($this->_col, $this->_inp), true);
			if (count($this->_col))

			$this->_method = "delete";
		}
		return $this;
	}

	public function with ($type, ...$var) {
		switch ($type) {
			case 'pages':
				$this->sort()->pages($var[0], "page");
				$this->_pages = $var[0];
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

				if (!empty($where)) {
					$this->_val = $where;
					$this->where($where, true);
				}
				break;
			case "change":
				if ($this->_col)
					$this->_col = $var[0];
				break;
		}

		// resetting here 
		
		if ($this->_col) {
			$this->_sql = @strstr($this->_sql, "where", true);
		}
		$this->_query_value = [];

		switch ($this->_method) {
			case 'create':
				$this->add($this->_col, $this->_inp);
				break;
			case "update":
				$this->set($this->_col, $this->_inp);
				if ($this->_val) {
					$this->where($this->_val, true);
				}
				break;
			case 'fetch': 
				if ($this->_val)
					if(!is_array(end($this->_val)) && !is_numeric($this->_val)) 
						$c = $this->concat(end($this->_sql));


				if (count($this->_col)) {
					$this->where($this->with_supp($this->_col, $this->_inp), true);
					if ($this->_pages)
						$this->pages($this->_pages, "page");
				}
				break;
			case "delete":
				if ($this->_col)
					$this->where($this->with_supp($this->_col, $this->_inp), true);
		}

		return $this;
	}

	public function with_supp ($col, $val) {
		$f = [];
		foreach ($col as $key => $v) {
			$f[] = [$col[$key], $val[$key]];
		}
		return $f;
	}

	/*
	
	this method can only works with 2 tables
	can result in unexpected behaviour if tables is more than 2

	You use the Db c for more than 2 tables;

	$d->table(["table_a", "table_b", "table_c", ....]);
	$d->get(["columns"])
	->use("join", ["join type"])
	->match(arrays of joins predicate)
	->where()->sort()->pages()
	->res();

	*/

	public function rfetch ($col = ["*"], $pre = [], ...$where) {
		$val = $this->_v;

		if ($val->req()) {
			list($this->_col, $this->_inp) = $val->val_req();
			if ($val->pass()) {
				$this->_error = $val->error();
			}
		}
		if (!$this->_error) {
			if (count($where)) { 
				$this->_col = $this->_inp = [];
				if(!is_array(end($where)) && !is_numeric($where)) {
					$this->concat(end($where));
					array_pop($where);
				}
				foreach ($where as $k => $v) {
					$this->_col[] = $v[0];
					if (count($v) == 2) {
						$this->_inp[] = $v[1];
					} elseif (count($v) > 2) {
						$this->_inp[] = $v[2];
					}
				}
				$this->where($where, true);
			} elseif ($this->_col) {
				$this->where($this->with_supp($this->_col, $this->_inp), true);
			}
			
			if (!is_array($this->_table)) {
				$this->_error = "**Error: The table method require an array as param!";
			} else {
				$this->get($col)->use("join", array_fill(1, count($this->_table) - 1, "left"))->match($pre);
			}
			$this->_method = "fetch";
		}
		return $this;
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