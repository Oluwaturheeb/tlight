<?php
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

// the header file needs a $title for title of the page
$title = "Login";
require_once "inc/header.php";

// choose from 1 to 7 headers and edit it to ur taste
require_once "inc/headers/header1.php"
?>
	<div class="container">
		<?php require_once "auth/login.php"; ?>
	</div>