<?php 
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

// the header file needs a $title for title of the page
$title = "Update post";
require_once "inc/header.php";

// choose from 1 to 7 headers and edit it to ur taste
require_once "inc/headers/header5.php";

// the easy class that makes everything easy
$e = new Easy();

// the table
$e->table("post");

// this method takes an array of columns to select default to ["*"]
$data = $e->fetch();

// $data now holds the result of the query!

//print_r($data);

?>

	<div class="container">
		<!-- Your html form code goes here -->
	</div>

<?php 
// this include the app js files
require_once "inc/footer.php";