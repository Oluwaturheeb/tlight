<?php 
require_once "Autoload.php";

$title = "Welcome";
require_once "inc/header.php";
require_once "inc/headers/header1.php";

?>

	<div class="container">
		<header>
			<h1>Tlight</h1>
			<div><small>Already made and Swift</small></div>
		</header>
		Setup your application using the config file at <i>Config.php</i> and run:
		<br>
		<div>If (php in path):</div>
		<code>$ php setup.php -i</code>
		<div>else:<br>Click <a href="/setup">here</a> to setup.<br>endif</div>
	</div>
	<style type="text/css">
		body {
			background: #000;
			color: var(--text);
			font-size: 1.3rem !important;
		}

		.container {
			display: grid;
			justify-content: center;
			text-align: center;
		}

		a {
			text-decoration: none;
			color: var(--link);
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

		i {
			display: inline-block !important;
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