<?php 
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

// this delete all active sessions
Session::del();

// and redirect class do the directing after all the session has been deleted
Redirect::to("/");