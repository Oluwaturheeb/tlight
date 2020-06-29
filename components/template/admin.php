<?php
require_once "Autoload.php";

if (!Session::check("user"))
	Redirect::to("login");


$title = "Admin panel";

require_once "inc/header.php";

// do something here!