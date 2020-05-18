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
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="contact">
		<header><h2>Wanna hire me?</h2></header>
			<div><i>Phone</i><a href="tel:08121001052">Call 08121001052</a></div>
			<div><i>Phone</i><a href="tel:08076769165">Call 08076769165</a></div>
			<div><i>Whatsapp</i><a href="Whatsapp:08076769165">08076769165</a></div>
			<div><i>Email</i><a href="mail:oluwaturheeb@gmail.com">E-mail Oluwaturheeb@gmail.com</a></div>
		</div>
	</div>
	<style type="text/css">
		
		body {
			background: #000;
			color: var(--text);
			font-size: 1.3rem !important;
		}

		.contact {
			background: var(--bg);
			box-shadow: 2px 2px 5px var(--pry);
			padding: 3rem 1rem;
			position: relative;
			/*display: grid;
			justify-content: start;*/
			margin-top: 4rem;
			border-radius: 0 0 10px 10px;
			z-index: -1;
		}

		.contact header {
			position: absolute;
			top: -3rem;
			background: var(--pry);
			left: 5rem;
			border-radius: 10px 10px 0 0;
		}

		.contact h2 {
			margin: 10px;
			color: var(--text);
		}

		.contact div  {
			display: grid;
			grid-auto-flow: column;
			grid-gap: 10px;
			justify-content: start;
		}

		.container {
			display: grid;
			justify-content: center;
		}

		a {
			text-decoration: none;
			color: var(--link);
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
			display: none;
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
				display: grid !important;
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