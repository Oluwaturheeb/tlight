<?php 
require_once 'Autoload.php';
callfile('inc/header', 'Welcome');
?>

	<div class="container">
		<header>
			<h1>Tlyt</h1>
			<div><small>Already made and Swift</small></div>
		</header>
		<p>Setup your application using the <i>config</i> file at the root of this project and run:
		</p>
		<code>$ php tlyt -i</code>
	</div>
	<style type="text/css">
		.container {
			text-align: center;
		}
		
		h1 {
			font-family: candara;
			font-size: 4rem;
			color: var(--pry);
		}

		header div {
			font-style: italic;
			margin: -3rem 0 3rem 0;
			color: var(--sec);
		}
		
		code {
			border: 1px solid var(--text);
			margin: 1rem 10px;
			padding: 10px;
			color: var(--pry);
		}
	</style>

<?php
// this include the app js files
callfile('inc/footer');