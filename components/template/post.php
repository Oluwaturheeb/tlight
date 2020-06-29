<?php 
// this Autoload require should always be the first line of code 
require_once "Autoload.php";

$e = new Easy();
$e->table("post");
$post = $e->fetch();

// if there is any error the Redirect class ends the execution of the current script and include a 404 error

if ($e->error())
	Redirect::to(404);


$title = $p->title;
require_once "inc/header.php";

?>
	<div class="container">
		<!-- Your html code goes here -->
	</div>

<?php 
// this include the app js files
require_once "inc/footer.php";