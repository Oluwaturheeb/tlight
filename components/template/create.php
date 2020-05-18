<?php
// this Autoload require should always be the first line of code 
require_once "../Autoload.php";

// the header file needs a $title for title of the page
$title = "Welcome";
require_once "inc/header.php";

// choose from 1 to 7 headers and edit it to ur taste
require_once "inc/headers/header5.php";

// in case you are not a big fan of ajax as for me i always use ajax to submit my request

?>




	<div class="container">
		<!-- Your html form code goes here -->
	</div>

<?php 
// this include the app js files
require_once "inc/footer.php";