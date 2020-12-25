<?php 
require_once "Autoload.php";
// enter wherr to redirect to if there is an active login
authCheck('/');

$title = "Welcome";
require_once "inc/header.php";

?>

	<div class="container">
		<?php require_once "auth/login.php"; ?>
	</div>
<?php
require_once "inc/footer.php";