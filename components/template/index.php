<?php 
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

// the header file needs a $title for title of the page
$title = "Welcome";
require_once "inc/header.php";

// choose from 1 to 7 headers and edit it to ur taste
require_once "inc/headers/header5.php";

// as the name suggest this relationship to safe time typing

$r = new Rel();

// give the tables
$r->table(["post", "profile"]);

// this takes the column to fetch for both tables and of cause relationship must have been established between this tables else the program will throw an error!
$data = $r->fetch(/* for post table */["title", "url", "views", "time"], /* for profile */ ["name"])
// calling the exec method see the query to be executed, and it takes 1 optinal args to get the first result e.g ->exec(1).
->exec();
// $data now holds the result of the query!

//print_r($data);


?>

	<div class="container">
		<!-- Your html code goes here -->
	</div>

<?php 
// this include the app js files
require_once "inc/footer.php";