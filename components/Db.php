<?php

class Db {
	protected $_pdo, $_error = false, $_instance, $_table, $_sql, $_query_value = [], $_misc,  $_sort, $_count, $_lastid, $_result = null;

	public $paging = false, $next = 0, $prev;
	
	public function __construct () {
		if ($this->_error) return $this->_error();
		try{
			$c = new Config();
			$this->_pdo = $this->_instance = new PDO('mysql:host='. $c->get('db/host') .';dbname=' . $c->get('db/database'), $c->get('db/usr'), $c->get('db/pwd'));
		} catch (Exception $e) {
			$this->_error = $e->getMessage();
		}
	}

	public static function instance () {
		if (!isset(self::$_instance)) {
			$_instance = new Db();
		}
		return $_instance;
	}

	public function table ($name) {
		$this->_table = $name;
		return $this;
	}

	public function query ($sql, $opt = []) {
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			if (@count($opt)) {
				$i = 1;
				foreach ($opt as $clause) {
					$this->_query->bindValue($i, $clause);
					$i++;
				}
			}

			if ($this->_query->execute()) {
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = $this->_query->errorInfo()[2];
			}
		}
		$this->_lastid = $this->_pdo->lastInsertId();
		return $this;
	}

	public function customQuery ($sql, $ops = []) {
		if (is_array($ops)) {
			$this->_sql = $sql;
			$this->_query_value = $ops;
		} else {
			$this->_error = 'This method expect an array for second parameter';
		}
		return $this;
	}

	public function add ($col, $val) {
		if (!$this->_error) {
			$p = array_fill(0, count($val), '?');

			$this->_sql = "insert into {$this->_table}(" . implode(',', $col). ') values(' . implode(',', $p) . ')';
			$this->_query_value = $val;
		}
		return $this;
	}

	public function get ($cols = ['*']) {
		if (!$this->_error) {
			if (!is_array($this->_table)) {
				if (!is_array($cols)) {
					$this->_error = 'This method expects an array as parameter 1';
				} else {
					$this->_sql = 'select ' . implode(', ', $cols) . ' from ';
					$this->_sql .= $this->_table;
				}
			} else {
				// this little code is use for union   

				if (count($this->_table) == 1) {
					// error message
					$this->_error = '**Error: Number of tables given does not match the given columns!';
				} else {
					$q = [];

					foreach (func_get_args() as $key => $value) {
						$q[] = 'select '. implode(', ', $value) ." from {$this->_table[$key]}";
					}

					$this->_sql = $q;
				}
				$this->_misc = $cols;
			}
		}
		return $this;
	}	
	
	public function join ($table, $pre, $type = '') {
		// error 
		if (is_array($table)) {
			$this->_error = '**Arg Error: String is required Array given. Method called Join!';
			return $this;
		} 
		$this->_sql .= " {$type} join {$table}" . $this->predicate($pre);
		return $this;
	}
	
	public function set ($cols = [], $vals) {
		if (!$this->_error) {
			$col = $value = ''; $i = 1;

			foreach($cols as $keys => $values){
				$col .= "{$values} = ?";
				
				if($i < count($cols)){
					$col .= ', ';
				}
				$i++;
			}

			$this->_query_value = $vals;
			$this->_sql = "update {$this->_table} set {$col} "; 
			//echo $this->_sql;
		}
		return $this;
	}

	public function rm () {
		if (!$this->_error) {
			$this->_sql = "delete from {$this->_table} ";
		}
		return $this;
	}

	public function find (...$where) {
		if (!$this->_error) {
			if (!empty($where)) {
				$form = $this->gen($this->form($where, true), 'or');
				$w = str_replace('=', 'like', $form[0]);

				function ff ($a) {
					return "%{$a}%";
				}

				$this->_query_value = array_map('ff', $form[1]);

				$this->_sql .= " $w";
			}
		}

		return $this;
	}
	
	public function where (...$id) {
		if (!$this->_error) {
			if (end($id) === true) {
				$args = $id[0];
			} else {
				$args = $id;
			}
			$gen = $this->gen($this->form($args), 'and');
			
			$this->queryValue($gen[1]);

			if ($this->_misc !== true) {
				$this->_sql .= $gen[0];
			} else {
				$this->_sql = substr($this->_sql, 0, -1);
				$this->_sql .= ' ' . $gen[0] . ')';
			}
		}
		return $this;
	}
	
	public function orWhere (...$id) {
		if (!$this->_error) {
			if (end($id) === true) {
				$args = $id[0];
			} else {
				$args = $id;
			}
			
			$gen = $this->gen($this->form($args), 'or');
			
			$this->queryValue($gen[1]);

			if ($this->_misc !== true) {
				$this->_sql .= $gen[0];
			} else {
				$this->_sql = substr($this->_sql, 0, -1);
				$this->_sql .= ' ' . $gen[0] . ')';
			}
		}
		return $this;
	}
	
	public function whereIn($col, $val = []) {
		if (empty($val)) {
			$this->_sql .= " where {$col} in ";
		} else {
			$new = implode(', ', array_fill(1, count($val), '?'));
			$this->_sql .= " where {$col} in ({$new})";
		}
		
		$this->queryValue($val);
		return $this;
	}
	
	// where a single value matches different columns
	public function whereManyToOne ($val, $col, $sign = []) {
		if (!$this->_error) {
			$q = '';
			foreach ($col as $n => $a) {
				if (empty($sign) || !is_array($sign)) {
					$sign = '=';
				} else {
					$sign = $sign[$n];
				}
				if ($n > 0) {
					$q .= ' and ';
				}
				
				$q .= $a. ' ' . $sign . ' ?';
			}
			
			$this->queryValue(array_fill(0, count($col), $val));
			$this->_sql .= ' where ' .$q;
		}
		return $this;
	}
	
	public function orWhereManyToOne ($val, $col, $sign = []) {
		if (!$this->_error) {
			$q = '';
			foreach ($col as $n => $a) {
				if (empty($sign) || !is_array($sign)) {
					$sign = '=';
				} else {
					$sign = $sign[$n];
				}
				if ($n > 0) {
					$q .= ' or ';
				}
				
				$q .= $a. ' ' . $sign . ' ?';
			}
			
			$this->queryValue(array_fill(0, count($col), $val));
			$this->_sql .= ' where ' .$q;
		}
		return $this;
	}
	

	public function pages ($ppage = 5, $url = '') {
		if (!$this->_error) {
			$this->query($this->_sql, $this->_query_value);
			$last = ceil($this->count() / $ppage);
			
			$v = new Validate();
			$v->autoValidate(['number' => true]);
			
			if ($v->error()) {
				$this->_error = $v->error();
			} else {
				if (req($url)) {
					$each = req($url);
				} elseif (!empty($_GET)) {
					foreach ($_GET as $key) {
						$each = $key;
					}
				} else {
					$each = 1;
				}
				
				if (is_numeric($each)) {
					if ($each >= $last) {
						$each = $last;
					}
				} else {
					$each = 1;
				}
				
				if ($last > 1) {
					if ($each < $last) {
						$next = $each + 1;
						$prev = $each - 1;
					} else if ($each >= $last) {
						$next = $last;
						$prev = $last - 1;
					}
					$this->paging = true;
					$this->next = $next;
					$this->prev = $prev;
				}
	
				$this->_sql .= ' limit ' . ($each - 1) * $ppage . ', ' . $ppage;
			}
		}
		return $this;
	}

	public function autopage($ppage = 5){
		$this->query($this->_sql, $this->_query_value);
		$this->get(['id'])->where();
		$total = $this->count();
		$last = ceil($total / $ppage);
		$t = $total - $ppage;

		if($t < 0) {
			$t = 0;
		}

		return $t . ', ' . $ppage;
	}
	
	public function sort ($col = 'id', $order = 'desc') {
		if (!$this->_error) {
			$this->_sql .= " order by {$col} {$order}";
		}
		return $this;
	}
	
	public function union ($tab, array $cols = []) {
		if ($this->error()) {
			$this->_sql .= ' union ';
			$nq = 'select '. implode(', ', $cols) . ' from '. $tab;
			$this->_sql .= $nq;
		}
		return $this;
	}

	public function sub ($t, $col = ['*']) {
		if (!$this->_error) {
			$this->_query_value = [];
			$s = ' (select ' . implode(', ', $col) . " from $t)";
			if (stristr($this->_sql, '= ?')) {
				$this->_sql = str_ireplace('= ?', 'in', $this->_sql);
			} else {
				$this->_sql = str_ireplace('?', '', $this->_sql);
			}
			$this->_sql .= $s;
			$this->_misc = true;
		}
		return $this;
	}

	public function res ($f = false) {
		if ($this->_error) {
			return $this->_error;
		} else {
			$this->_sort = null;
			$this->_misc = null;

			$this->query($this->_sql, $this->_query_value);
			$this->_query_value = null;

			if (stristr($this->_sql, 'insert')) {
				return $this->_lastid;
			} else {
				if ($f == false || !$this->count())
					return $this->_result;
				else 
					return $this->_result[0];
			}
		}
	}

	public function error(){
		return $this->_error;
	}
	
	public function count(){
		return $this->_count;
	}

	public function out () {
		return $this->_sql;
	}

	public function exp () {
		$this->query('explain ' . $this->_sql, $this->_query_value);
	}
	
	public function __tostring() {
		return $this->out() . '<br>' . @implode(',', $this->_query_value);
	}

	// protected methods

	// this method takes a multi-dimention array as arg.

	protected function form ($args) {
		if (count($args) == 1) {
			$arg = $args[0];

			if (!is_array($arg) && is_numeric($arg)) {
				$where = [['id', '=', $arg]];
			} elseif (is_array($arg)) {
				if(count($arg) == 2) {
					$where = [[$arg[0], '=', $arg[1]]];
				} else {
					$where = $arg;
					$where[1] = str_ireplace(['&lt;', '&gt;'], ['<', '>'], $where[1]);
					$where = [$where];
				}
			} else {
				$where = $args;
			}
		} elseif (count($args) > 1) {

			$where = [];
			for ($i = 0; $i < count($args); $i++) { 
				$arr = $args[$i];
				if(!empty($arr)) {
					if (is_numeric($arr)) {
						$w = ['id', '=', $arr];
						array_push($where, $w);
					} elseif(count($arr) == 2) {
						$w = [$arr[0], '=', $arr[1]];

						array_push($where, $w);
					} elseif (count($arr) > 2) {
						$w = $arr;
						$w[1] = str_ireplace(['&lt;', '&gt;'], ['<', '>'], $w[1]);
						array_push($where, $w);
					} else {
						$this->_error('**Error: This method expecs numeric character as args!');
						return false;
					}
				}
			}
		}
		return $where;
	}
	
	private function queryValue ($item) {
		if (!empty($this->_query_value)) {
			$this->_query_value = array_merge($this->_query_value, $item);
		} else {
			$this->_query_value = $item;
		}
		return $this;
	}

	protected function gen ($where = [], $cc) {
		if (is_array($where[0])){
			$op = $value = '';
			$where = @array_filter($where);
			for ($i = 0, $j = 1;$i < @count($where); $i++, $j++) {
				$v1 = $where[$i][0];
				$v2 = $where[$i][1];
				$v3 = $where[$i][2];
	
				if (is_array($v1)) {
					if (is_array($v2)) {
						$v2_con = $v2[0];
						$v2_sign = $v2[1];
					} else {
						$v2_con = 'or';
						$v2_sign = $v2;
					}
					$op .= "({$v1[0]} {$v2_sign} ?  {$v2_con} $v1[1] {$v2_sign} ?)";
					$value .= "{$v3}, {$v3}";
				} else {
					$op .= "{$v1} {$v2} ?";
					$value .= "{$v3}";
				}
				
				if($j < count($where)){
					$value .= ', ';
				 $op .= " {$cc} ";
				}
			}
	
			$value = explode(', ', $value);
			$op = " where {$op}";
			return [$op, $value];
		} else {
			$this->_error = '**Arg Error: This method expects a multi-dimentional array as parameter 1 gen';
			return false;
		}
	}

	// this method works for join

	protected function predicate ($pre, $concat = 'and') {
		if (!$pre) {
			$this->_error = '**Arg Error: No value given. Method called Predicate';
		} else {
			if (!is_array($pre)) return " using({$pre})";
			
			$ret = '';
			foreach ($pre as $p => $v) {
				if (is_array($v)) {
					if ($p > 0) {
						$ret .= " $concat ";
					}
					if (count($v) == 3) {
						$ret .= $v[0]. ' ' . $v[1]. ' ' .$v[2];
					} else {
						$ret .= $v[0]. ' = ' .$v[1];
					}
				} else {
					if (count($pre) == 3) {
						$ret = $pre[0]. ' ' . $pre[1]. ' ' .$pre[2];
					} else {
						$ret = $pre[0]. ' = ' .$pre[1];
					}
				}
			}
			return ' on ' . $ret;
		}
		return false;
	}
}