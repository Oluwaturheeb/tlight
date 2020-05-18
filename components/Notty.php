<?php

class Notty {
	private $_db;

	public function __construct () {
		$this->_db = new Db();
		$this->_db->table("notty");
	}

	public function init() {
		$sql = "create table if not exists notty(id int auto_increment, sender int, rec int, time datetime default now(), content text not null, flag int, primary key(id))";
		$this->_db->customQuery($sql);
	}

	public function get_user ($tab, $name, $user = "user") {
		$this->_db->table($tab);

		if (is_array($name)) 
			$name = implode(", ", $name);
		
		if ($user == "user")
			$user = Session::get("user");

		$d = $this->_db->get($name)->where($user);
		return $d[0][$name];
	}

	public function send ($rec, $text) {
		$d = $this->_db;
		$d->add(["rec", "content"], func_get_args());

		if (!$d->error())
			return true;

		return $d->error();
	}

	public function get ($user = "", $def = 5) {
		if ($user == "")
			$u = Session::get("user");
		else 
			SESSION::get($user);

		$d = $this->_db;


		$d->get(["*"])
		->where(["rec", $u], ["flag", 0]);

		if(is_numeric($def)) {
			$d->pages(5);
		}

		$d->res();
	}

	public function set_flag ($check , $user = "user") {
		$d = $this->_db;
		$d->set(["flag"], [1]);

		if($check) 
			$d->where($check);
		else 
			$d->where(["rec", Session::get($user)]);

		return $d->res();
	}

	public function delete($to, $user = "user") {
		$u = Session::get("user");
		$d = $this->_db;

		$d->del();

		if (is_numeric($to)) 
			$d->where($to);
		else 
			$d->where(["rec", $u]);

	}
}