<?php 
require_once "Autoload.php";
print_r($_SERVER);

$title = "Welcome";
require_once "inc/header.php";
?>

	<div class="container">
		<header>
			<h1>Tlight</h1>
			<div><small>Already made and Swift</small></div>
		</header>
		<p>Setup your application using the <i>config.php</i> file at the root of this project and run:
		</p>
		<code>$ php tlight -i</code>
	</div>
	<style type="text/css">
		.container {
			text-align: center;
		}
	
		h1 {
			font-family: candara;
			font-size: 4rem;
			color: var(--text);
		}

		header div {
			font-style: italic;
			margin: -3rem 0 3rem 0;
			color: var(--sec);
		}
		
		code {
			border: 1px inset var(--text);
			margin: 1rem 0;
			padding: 10px;
			color: red;
		}
	</style>

<?php 
// this include the app js files
require_once "inc/footer.php";