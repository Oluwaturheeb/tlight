<?php 
require_once "Autoload.php";
$title = "About";
require_once "inc/header.php";
?>

	<article class="container">
		<h1>About Tlight</h1>
		<section>
			<p><b>Tlight</b> is a <b>PHP</b> module(i'll call it that) that i decided to build from scratch with no framework to test my knowledge of the language.</p>
			<p>So far, i can say to myself that with what i have achieved here, so far i have slight pass the level of an intermediate developer and i see a whole lot of improvements yet to come.</p>
			<p>My name is <b>Bello Turyeeb</b> and you can imagine where the name of this project/module come from.</p>
			<p>I initial wanted to learn a new framework namely laravel and i search for developers opinion on the framework. Laravel is widely used framework built with security in mind and have its own templating engine called Laravel blade. Then i heard its slow.</p>
			<p>So i looked into myself and say if laravel is heavy let me come up with something light and fast enough and wont have the convensional name called framework so i called mine a module.</p>
			<p>As little as my experience is, is how i build this module.</p>
			<p>Tlight is built with OOP(Object Oriented Programming) and uses the built-in PHP PDO(PHP Data Object). And i also added a documention file so as to help in getting familiar with the module.</p>
		</section>
	</article>
		<style type="text/css">
		body {
			background: #000;
			color: var(--text);
			font-size: 1.3rem !important;
		}

		.container {
			display: grid;
			justify-content: center;
		}

		a {
			text-decoration: none;
			color: var(--link);
		}

		h1 {
			color: var(--text);
		}
		p {
			margin-left: 10px;
		}
	</style>
<?php require_once "inc/footer.php";