<?php

class User extends Db {
	public $table = 'profile', $auth = 'a_id', $img = 'picture';
	
	// c is telling this method not use session but rather to use the supplied id to get user data
	
	public function profile ($id = '', $c = false) {
		if ($c) goto fetch;
		if (Session::get('profile')) {
			$pro = Session::get('profile');
		} else {
			if (!$id) $id = authId();
			fetch:
			$pro = $this->table($this->table)->get()->
			where([$this->auth, $id])
			->res(true);
			if (!$c)
				Session::set('profile', $pro);
		}
		return $pro;
	}

	public function imgdata () {
		$def = $this->img;
		$p = $this->profile();
		if ($p->$def)
			return $p->$def;
		else
			return $p->fullname[0];
	}
	
	public function picture ($pic = '', $f = '') {
		if (!$f) $pic = $this->imgdata();
		if (strlen($pic) > 1) {
			return '<img src="'. $pic .'" alt="'. self::profile()->fullname .'"style="color: var(--sec); border-radius: 100%; width: 5rem; height: 5rem;">';
		} else {
			if (!$pic) $pic = $f[0];
			return '<div style="background: var(--pry); color: #fff; border-radius: 100%; width: 5rem; height: 5rem;text-align: center; font-size: 3rem; text-align-last: center;text-transform: capitalize">'. $pic .'</div>';
		}
	}
}