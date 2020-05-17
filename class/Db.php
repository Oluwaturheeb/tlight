<?php

class Db {
	protected $_pdo, $_error = false, $_instance, $_table, $_sql, $_query_value = [], $_con, $_misc,  $_sort, $_result = null, $_count, $_lastid;

	public $paging = false, $next = 0, $prev;

	public function __construct () {
		try{
			$this->_pdo = new PDO("mysql:host=". config::get("db/host") .";dbname=" . config::get("db/database"), Config::get("db/usr"), Config::get("db/pwd"));
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

	public function query ($sql, $clauses = []) {
		$this->_error = false;

		if ($this->_query = $this->_pdo->prepare($sql)) {
			if (@count($clauses)) {
				$i = 1;
				foreach ($clauses as $clause) {
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
			$this->_error = "This method expect an array for second parameter";
		}
		return $this;
	}

	public function add ($col, $val) {
		$i = 0;	$p = [];
		
		for ($i = 0; $i < count($col); $i++) { 
			array_push($p, "?");
		}

		$this->_sql = "insert into {$this->_table}(" . implode(",", $col). ") values(" . implode(",", $p) . ")";
		$this->_query_value = $val;
		return $this;
	}

	public function get ($cols = ["*"]) {
		if (!is_array($this->_table)) {
			$this->_sql = "select " . implode(",", $cols) . " from ";
			$this->_sql .= $this->_table;
		} else {
			// this little code is use for union
			$q = [];
			
			foreach (func_get_args() as $key => $value) {
				
				$abc = range("a", "e");
				array_push($q, "select " . implode(",", $value) . " as {$abc[$key]} from " . $this->_table[$key]);
			}
			$this->_sql = $q;
			# using this variable for method use() when the join method is being called and it serve as the column list!
			$this->_misc = $cols;
		}
		
		return $this;
	}	

	public function match ($arg) {
		$args = func_get_args();
		$this->_sql = $this->join($this->_table, $this->_misc, $args, $this->_sort, $this->_con);
		$this->_misc = null;
		return $this;
	}
	
	public function join ($table = [], $cols = [], $match = [], $type = [], $concat = ["and"]) {
		$sub = ""; $once = ""; $on  = ""; $con = ""; $o = "";
		$col = implode(", ", $cols);
		
		for ($i = 0, $j = 1; $i < count($table); $i++, $j++) {
			if($j < count($table)) {
				if($type) {
					$join = " {$type[$i]} join ";
				} else {
					$join = " left join ";
				}
				$on = $this->predicate($match, $concat)[$i];
			}
			$tab = "{$table[$i]}";
			if($j == 1) {
				$o .= $on;
				$once .= $tab . $join;
			} else {
				if($o){
					$once .= $tab . $o;
					$o = "";
				} else {
					$con .= $join . $tab . $on;
				}
			}
		}
		$sub =  $once . $con;
		$query = "select {$col} from {$sub}";
		return $query;
	}

	public function unite ($table = []) {
		$this->_query_value = true;
		return $this;
	}

	public function use ($use, $type = ["inner"]) {
		switch ($use) {
			case 'join':
				$this->_sort = $type;
				break;
			case 'union':
				$this->unite($this->_table);
				break;
		}
		return $this;
	}

	public function set ($cols = [], $vals) {
		$col = $value = ""; $i = 1;

		foreach($cols as $keys => $values){
			$col .= "{$values} = ?";
			
			if($i < count($cols)){
				$col .= ", ";
			}
			$i++;
		}

		$this->_query_value = $vals;
		$this->_sql = "update {$this->_table} set {$col} "; 
		return $this;
	}

	public function rm () {
		$this->_sql = "delete from {$this->_table} ";
		return $this;
	}

	public function search ($cols, $to = array()) {
		if (is_array($to)) {
			/* Where clause */

			$field = $val = $col = "";

			for ($i = 0, $j = 1; $i < count($to); $i++, $j++) {
				$field .= "{$to[$i][0]} {$to[$i][1]} ?";
				$val .= "%{$to[$i][2]}%";
				if ($j <= count($cols)) {
					$col .= $cols[$i];
					if($j < count($cols)){
						$col .= ", ";
					}
				}
				if ($j < count($to)) {
					$field .= " or ";
					$val .= ",,,,, ";
				}
			}
			$sel = $col;
			$this->_query_value = explode(",,,,, ", $val);
			$this->_sql = "select {$sel} from {$this->_table} where {$field} {$limit}";
			return $this;
		}
	}

	public function concat ($join) {
		$this->_con = $join;
		return $this;
	}

	public function where (...$id) {
		if (end($id) === true) {
			$args = $id[0];
		} else {
			$args = $id;
		}
		
		if($this->_query_value !== true) {
			// the this is normal query 
			if (count($args) > 1 && !$this->_con) {
				$this->_con = ["and"];
			}
			$gen = $this->gen($this->form($args), $this->_con);

			if (!empty($this->_query_value)) {
				$this->_query_value = array_merge($this->_query_value, $gen[1]);
			} else {
				$this->_query_value = $gen[1];
			}

			if ($this->_misc !== true) {
				$this->_sql .= " " . $gen[0];
			} else {
				$this->_sql = substr($this->_sql, 0, -1);
				$this->_sql .= " " . $gen[0] . ")";

			}
		} else {
			$this->_query_value = [];
			$q = "";
			foreach ($args as $key => $value) {
				// this condition adds union to the querys

				if($key > 0) {
					$q .= " union ";
				}

				// this condition checks if the where concat is called or not

				if (!$this->_con) {
					$con = ["and"];
				} else {
					$con = $this->_con;
				}

				// this checks if the query needs a where clause attached or not

				if(count($value)) {
					$gen = $this->gen($this->form($value), $con);
					if (!empty($this->_query_value)) {
						$this->_query_value = array_merge($this->_query_value, $gen[1]);
					} else {
						$this->_query_value = $gen[1];
					}
					$where = $gen[0];
				} else {
					$where = "";
				}
				$q .= $this->_sql[$key] . " " . $where . " ";

			}
			$this->_sql = $q;
		}
		# using this var for to return the args for pages() method!
		// __$this->_args = $args;
		return $this;
	}

	public function pages ($ppage = 5, $url = "") {
		$this->query($this->_sql, $this->_query_value);
		$last = ceil($this->count() / $ppage);

		if (!empty($_GET[$url])) {
			$each = $_GET[$url];
		}elseif (!empty($_GET)) {

			foreach ($_GET as $key) {
				$each = $key;
			}
		} else {
			$each = 1;
		}

		if (is_numeric($each)) {
			if ($each == $last) {
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

		$this->_sql .= " limit " . ($each - 1) * $ppage . ", $ppage";
		return $this;
	}

	public function autopage($ppage = 5){
		$this->query($this->_sql, $this->_query_value);
		$this->get(["id"])->where();
		$total = $this->count();
		$last = ceil($total / $ppage);
		$t = $total - $ppage;

		if($t < 0) {
			$t = 0;
		}

		return $t . ", " . $ppage;
	}
	
	public function sort ($col = "id", $order = "desc") {
		$this->_sql .= " order by {$col} {$order}";
		return $this;
	}

	public function sub ($t, $col = ["*"]) {
		$this->_query_value = [];
		$s = " (select " . implode(", ", $col) . " from $t)";
		if (stristr($this->_sql, "= ?")) {
			$this->_sql = str_ireplace("= ?", "in", $this->_sql);
		} else {
			$this->_sql = str_ireplace("?", "", $this->_sql);
		}
		$this->_sql .= $s;
		$this->_misc = true;
		return $this;
	}

	public function res ($f = false) {
		$this->_sort = null;
		$this->_con = null;
		$this->_misc = null;

		$this->query($this->_sql, $this->_query_value);
		$this->_query_value = null;

		if (stristr($this->_sql, "insert")) {
			return $this->_lastid;
		} else {
			if ($f == false || !$this->count())
				return $this->_result;
			else 
				return $this->_result[0];
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
		$this->query("explain " . $this->_sql, $this->_query_value);
	}

	// protected methods

	// this method takes a multi-dimention array as arg.

	protected function form ($args) {
		if (count($args) == 1) {
			$arg = $args[0];

			if (!is_array($arg) && is_numeric($arg)) {
				$where = [["id", "=", $arg]];
			} elseif (is_array($arg)) {
				if(count($arg) == 2) {
					$where = [[$arg[0], "=", $arg[1]]];
				} else {
					$where = $arg;
					$where[1] = str_ireplace(["&lt;", "&gt;"], ["<", ">"], $where[1]);
					$where = [$where];
				}
			} else {
				$where = $args;
			}
		} elseif (count($args) > 1) {

			$where = [];
			for ($i = 0; $i < count($args); $i++) { 
				if(!empty($args[$i])) {
					if(count($args[$i]) == 2) {
						$w = [$args[$i][0], "=", $args[$i][1]];

						array_push($where, $w);
					} elseif (count($args[$i]) > 2) {
						$w = $args[$i];
						$w[1] = str_ireplace(["&lt;", "&gt;"], ["<", ">"], $w[1]);
						array_push($where, $w);
					}
				}
			}
		}
		return $where;
	}

	protected function gen ($where = [], $concat = "and") {
		if (is_array($where[0])){
			$op = $value = "";
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
						$v2_con = "or";
						$v2_sign = $v2;
					}
					$op .= "({$v1[0]} {$v2_sign} ?  {$v2_con} $v1[1] {$v2_sign} ?)";
					$value .= "{$v3}, {$v3}";
				} else {
					$op .= "{$v1} {$v2} ?";
					$value .= "{$v3}";
				}
				
				if($j < count($where)){
					$value .= ", ";
					if(is_array($concat)) {
						$cc = $concat[$i];
					} else {
						$cc = $concat;
					}
	
				    $op .= " {$cc} ";
				}
			}
	
			$value = explode(", ", $value);
			$op = "where {$op}";
			return [$op, $value];
		} else {
			$this->_error = "**Arg Error: This method expects a multi-dimentional array as parameter 1 -> @gen";
			return false;
		}
	}

	// this method works for join

	protected function predicate ($match, $concat) {
		$pref = []; $pre = "";

		if (end($match) === true)
			$end = array_pop($match);

		foreach ($match as $key => $val) {
			if (is_array($val[0])) {
				foreach ($val as $n => $m) {
					if ($n > 0) {
						$pre .= $concat[$n - 1];
					}
					if(empty($pre)) {
						$on = "on";
					} else {
						$on = "";
					}
					if (count($m) == 1) {
						$pre .= " using({$m[0]}) ";
					} elseif (count($m) == 2) {
						$pre .= " $on {$m[0]} = {$m[1]} ";
					} elseif (count($m) == 3) {
						$pre .= " $on {$m[0]} {$m[1]} {$m[2]} ";
					}
				}
				$pref[] = $pre;
			} else {
				if ($end)
					$pref[] = " on {$val[0]}";
				else
					$pref[] = " using({$val[0]}) ";
			}
		}

		return $pref;
	}
}