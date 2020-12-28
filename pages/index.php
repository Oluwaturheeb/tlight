<?php 
require_once "Autoload.php";

$title = "Welcome";
require_once "inc/header.php"; ?>

	<div class="container">
	<?php
	$d = Db::instance();
	$d->table('blog')
	->get(['*'])
	->whereIn('id', [1,2,3,4])
	->pages(1, 'more')
	->res();
	print_r($d);
	
	die($d);
	?>
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
require_once "inc/footer.php";