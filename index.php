<?php 
require_once "Autoload.php"; 
require_once "inc/header.php"; 

?>
	<div class="app">
		<header>
			<h1>Tlight</h1>
			<div><small>Already made and Swift</small></div>
		</header>
		Setup your application using the config file at /class/config.php and run:
		<br>
		<div>If (php in path):</div>
		<code>shell> php setup.php -i</code>
		<div>else:<br>Click <a href="/setup">here</a> to setup.<br>endif</div>
	</div>
	<style type="text/css">
		body {
			background: #000;
			color: #fff;
			font-size: 1.3rem !important;
			;
		}

		.app {
			display: grid;
			justify-content: center;
			text-align: center;
		}

		a {
			text-decoration: none;
			color: #21ef2b;
		}

		h1 {
			font-family: candara;
			font-size: 4rem;
			color: #21ef2b;
		}

		header div {
			font-style: italic;
			margin: -3rem 0 3rem 0;
		}

		code {
			border: 1px inset #ccc;
			margin: 1rem 0;
			padding: 10px;
		}

	</style>
<?php require_once "inc/footer.php";