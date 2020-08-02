<?php
require_once "Autoload.php";
$title = "About";
require_once "inc/header.php";
?>
	<div class="container">
		<h2>Let's talk!</h2>
		<p>Wanna make a suggestion, work with me or hire me. Let's chat!</p>
		<div class="contact">
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
			padding: 1rem;
			border-radius: 0 0 10px 10px;
			line-height: 1.7;
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