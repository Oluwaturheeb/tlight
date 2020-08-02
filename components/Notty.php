<?php

class Notty {
	private $_db, $_msg, $_table, $_error;

	public function __construct ($t = 'notty') {
		$this->_db = new Db();
		$this->_db->table('notty');
		$this->_table = $t;
	}

	public function notty () {
		$this->_db->table($t);
		return $this;
	}

	public function init() {
		$sql = "create table if not exists notty(id int auto_increment, sender int, rec int, time datetime default now(), msg text not null, flag int dfault 0, primary key(id))";
		$this->_db->customQuery($sql)->res();
	}

	public function msg ($msg) {
		$this->_msg = $msg;
	}

	public function to ($to, $user = '') {
		$d = $this->_db;

		if (!$user)
			$user = Session::get('user');

		$d->add(['msg', 'rec', 'sender'], [$this->_msg, $to, $user]);
	}

	public function get ($user = '') {
		if (!$user)
			$user = Session::get('user');

		$d = $this->_db;
		$d->get(['sender', 'msg', 'flag', 'time', 'id'])->where(['rec', $user]);
		return $this;
	}

	public function flag ($check = 0, $user = '') {
		$d = $this->_db;
		$d->set(["flag"], [1]);

		if ($check) {
			$d->where($check);
		} else {
			if (!$user)
				$user = Session::get('user');
			$d->where(['rec', $user]);
		}

		return $this;
	}

	public function delete($to, $user = '') {
		$d = $this->_db;
		
		if (!$user)
			$u = Session::get("user");

		$d->del();

		if (is_numeric($to))
			$d->where($to);
		else 
			$d->where(["rec", $u]);

		return $this;

	}

	public function send () {
		if ($this->_error) {
			Redirect::to(500, $this->_error);
		} else {
			$this->_db->res();

			if ($this->_db->error()) {
				$this->_error = $this->_db->error();
			} else {
				$this->_msg = $_error = null;
				return true;
			}
		}
	}

	public function error() {
		return $this->_error;
	}
}