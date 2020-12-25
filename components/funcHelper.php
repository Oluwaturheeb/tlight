<?php

function csrf () {
	return Validate::csrf();
}

function assets ($loc) {
	return 'assets/' + $loc;
}

function authId () {
	return Auth::authId();
}

function auth ($data = null) {
	return Auth::auth($data);
}

function authCheck ($loc = '/') {
	if (auth()) {
		return Redirect::to($loc);
	}
}