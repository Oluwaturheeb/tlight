<?php 
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

// the header file needs a $title for title of the page
$title = "Update post";
require_once "inc/header.php";

$e = new Easy();

// the table
$e->table("post");

// this method takes an array of columns to select default to ["*"]
$e->fetch();

// execute query and get the first result
// $data = $e->exec(1);

// $data now holds the result of the query!

//print_r($data);

?>

	<div class="container">
		<!-- Your html form code goes here -->
	</div>

<?php 
// this include the app js files
require_once "inc/footer.php";