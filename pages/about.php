<?php 
require_once "Autoload.php";
$title = "About";
require_once "inc/header.php";
?>

	<nav class="">
		<div class="logo">
			<a href="/" class="logo">
				tlight
			</a>
		</div>
		<div class="dp-menu">
			<span></span>
			<span></span>
			<span></span>
		</div>
		<div class="links">
			<div class="menu-link">
				<a href="/">Home</a>
				<a href="/about">About</a>
				<a href="/contact">Contact</a>
			</div>
			<div class="dp-link">
				<a href="/docs">Doc</a>
				<a href="/help">Help</a>
			</div>
		</div>
	</nav>
	<article class="container">
		<h1>About Tlight</h1>
		<section>
			<p><b>Tlight</b> is a <b>PHP</b> module(i'll call it that) that i decided to build from scratch with no framework to test my knowledge of the language.</p>
			<p>So far, i can say for myself that with what i have achieved here, so far i have the level of a beginner and i see a whole lot of improvement yet to come.</p>
			
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
		
		nav {
			background: var(--pry);
			display: grid;
			grid-auto-flow: column;
			justify-content: space-between;
			grid-gap: 1rem;
			align-items: center;
			padding: 1rem;
			position: relative;
		}

		.nav .dp-menu {
			padding: 5px;
			display: inline-block;
			cursor: pointer;
		}

		nav .logo {
			font-size: 2.5rem;
		}

		nav .logo a {
			color: var(--sec);
			font-style: italic;
		}

		nav .dp-menu span {
			display: block;
			background: var(--sec);
			padding: 2px .9rem;
			margin: 5px;
		}

		nav .dp-menu span:nth-child(even) {
			margin: 5px 0px 5px 5px;
		}
		
		nav .links {
			position: absolute;
			right: 0;
			top: 5rem;
			background: var(--pry);
			justify-content: end;
			width: 150px; 
    }

		nav .links a {
			display: block;
			padding: 1rem;
			color: var(--sec);
		}

		.links a:hover,
		.links a:focus {
			border-bottom: 2px solid var(--sec);
			margin-bottom: -2px;
		}

		@media (min-width: 576px) {
			nav {
				grid-template-columns: auto 2fr auto
			}
			.dp-menu {
				display: none;
				grid-column: 3;
				justify-self: start;
			}

			.links {
				position: static !important;
				width: auto !important;
				justify-self: end !important;
				display: grid;
				grid-auto-flow: column;
			}

			.links .menu-link {
				display: grid;
				grid-auto-flow: column !important;
			}

			.dp-link {
				position: static;
				right: 0;
				top: 5.6rem;
				background: var(--pry);
				display: grid !important;;
				grid-auto-flow: column !important;
				grid-row: 1/1;
			}
		}
	</style>
	<script type="text/javascript">
		$('.dp-menu').click(() => {
			if ($(window).innerWidth() >= 576) {
				$('.dp-link').slideToggle(500);
			} else {
				$('.links').slideToggle(500);
			}
		});
	</script>
<?php require_once "inc/footer.php";