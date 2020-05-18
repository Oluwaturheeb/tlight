<?php
/*

Author -> Abdul-Turyeeb Ibn Bello
Date -> 10th of May 20
File -> Relationship handler

*/

class Rel extends Db {

	private $_col, $_inp, $_rcol;

	public function table ($tab) {
		if (!is_array($tab)) {
			$this->_error = "**Arg Error: Expects an array for this method!";
		} else {
			parent::table($tab);
		}
		return $this;
	}

	public function fetch ($col) {
		$t = $this->_table;
		$q = $this->customQuery("select rel from relation where tab = ? or tab = ?", [$t[0] . '_' . $t[1], $t[1] . '_' . $t[0]])
		->res(1);
		$this->get($col)->use("join", ["left"])->match([$q->rel], true);
		$this->_rcol = $col;
		return $this;
	}

	public function where (...$where) {
		if (count($where)) {
			// over riding the request  
			parent::where($where, true);
		} else {
			$v = new Validate();

			if ($v->req()) {
				$r = $v->val_req();
				if ($v->error()) {
					$this->_error = $v->error();
				} else {
					list($this->_col, $this->_inp) = $r;
					parent::where(self::with_supp($this->_col, $this->_inp), true);
				}
			}
		}
		return $this;
	}

	public function with ($type, ...$var) {
		switch ($type) {
			case "append":
				list($col, $val) = $var;

				if (is_numeric($val))
					$val = [$val];

				$this->_col = array_merge($this->_col, $col);
				$this->_inp = array_merge($this->_inp, $val);
				break;
			case "remove":
				foreach($var[0] as $k) {
					$key = array_search($k, $this->_col, true);
						unset($this->_col[$key]);
						unset($this->_inp[$key]);
				}
				break;
			case "change":
				$this->_col = $var[0];
				break;
		}
		$ddd = self::with_supp($this->_col, $this->_inp);

		// starting over 

		$this->_sql = $this->_query_value = null;

		$this->get($this->_rcol)
		->use("join", ["left"])->match([$this->_result[0]->rel], true);
		parent::where($this->with_supp($this->_col, $this->_inp), true);
		return $this;
	}

	public static function with_supp ($col, $val) {
		$f = [];

		for ($i = 0; $i < count($col); $i++) {
			$f[] = [$col[$i], $val[$i]];
		}
		return $f;
	}

	public function exec ($c = false) {
		if ($this->error()) {
			return $this->error();
		}
		return $this->res($c);
	}

	
	/*
	option = [restrict, cascade, set null]
	*/
	
	public function set ($tab = [], $link = [], $option = []) {
		$opt = "";
		$check = ["delete", "update", "null", "restrict", "cascade"];
		if (!empty($option))
			foreach ($option as $key => $value) {
				$val = explode("-", $value);
				if (!in_array($val[0], $check)) {
					return "**Args Error: Invalid option supplied, got $val[0]/$val[1]!";
				} else {
					if (!in_array($val[1], $check)) {
						return "**Args Error: Invalid option supplied, got $val[0]/$val[1]!";
					} else {
						if ($val[1] == "null")
							$opt .= "on {$val[0]} set null ";
						else
							$opt .= "on {$val[0]} {$val[1]} ";
					}
				}
			}
		else
			$opt = "on update restrict on delete restrict";
			
	 	$alt = "alter table {$tab[1]} add constraint fk_{$tab[0]}_{$tab[1]} foreign key({$link[1]}) references {$tab[0]}({$link[0]}) {$opt}";
		$this->customQuery($alt)->res();
		
		if (!$this->_error) {
		 	return "**Note: Relationship has been established between ". implode(" and ", $tab) . "!";
		} else {
		 	$tabs = implode("_", $tab);
		 	$link = "{$tab[0]}.{$link[0]} = {$tab[1]}.{$link[1]}";
		 	parent::table("relation");
			$this->add(["tab", "rel"], [$tabs, $link])->res();

			return "**Success: Relationship has been created!";
		}
	}
}