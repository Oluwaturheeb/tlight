<?php
Autoload();
if (Session::check("user")) {
	$title = "Login";
} else {
	$title = "something else";
}

require_once "inc/header.php";
require_once "inc/headers/header3.php";

if(!Session::check("user"))
	require_once "auth/login.php";