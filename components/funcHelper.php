<?php

function csrf () {
	echo Validate::csrf();
}

function assets ($loc) {
	return 'assets/'. $loc;
}

function authId () {
	return Auth::authId();
}

function auth ($data = null) {
	return Auth::auth($data);
}

function authCheck ($loc = '/') {
	if (!authId()) {
		return Redirect::to($loc);
	}
}

function res ($data = '', $status = 200) {
	return Http::res($data, $status);
}

function req ($data = '') {
	return Http::req($data);
}

function redirect ($to = '/') {
	Redirect::to($to);
}

function goBack() {
	(isset($_SERVER['HTTP_REFERER']))? Redirect::to($_SERVER['HTTP_REFERER']) : '';
}

function config ($data) {
	return Config::get($data);
}

function session ($data = '', $set = '', $c = false) {
	if (!$data) {
		return Session::get();
	} else {
		if (!$set) {
			return Session::get($data);
		} else {
			if (!$c) {
				Session::set($data, $set);
			} else {
				Session::set($data, $set, $c);
			}
		}
	}
}

function redirectLogin () {
	if (authId())
		if (stripos($_SERVER['SCRIPT_NAME'], 'login'))
			goBack();
}

function getCookie ($name = '') {
	return Session::getCookie($name);
}

function callfile ($file, $text = '') {
	$title = $text;
	require_once $file . '.php';
}

function logtext ($logText, $type = 'info') {
	Logger::log($logText, $type);
}

function isAjax () {
	return Http::is_ajax();
}