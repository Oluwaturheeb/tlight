<?php

class User extends Db {
	
	public function profile ($col = ["*"], $user = 'user') {
		if (Session::get('profile')) {
			$pro = Session::get('profile');
		} else {
			$pro = $this->get($col)->where(['a_id', Session::get($user)])->res(true);
		}
		return $pro;
	}

	public static function img () {
		if ($p = Session::get('profile')) {
			if (Session::get('level') == 1) {
				if ($p->img) {
					return [$p->img, $p->company];
				} else {
					return [$p->company[0], $p->company];
				}
			} else {
				if ($p->img) {
					return [$p->img, $p->fullname];
				} else {
					return [$p->fullname[0], $p->fullname];
				}
			}
		}
		return false;
	}
	
	public static function imgdata ($data) {
		if (!empty($data->img)) {
			return '<img src="'. $data->img .'" alt="'. $data->company .'">';
		} else {
			return '<div class="def-img">'. $data->company[0] .'</div>';
		}
	}
}