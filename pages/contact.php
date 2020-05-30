<?php
require_once "Autoload.php";
$title = "About";
require_once "inc/header.php";
require_once "inc/headers/header1.php";
?>
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
	</style>