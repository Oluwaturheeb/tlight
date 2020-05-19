<?php 
require_once "Autoload.php";

$title = "Setup";
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
				<a href="/docs">Docs</a>
			</div>
		</div>
	</nav>
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
			font-size: 2rem;
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
			
			.logo {
				font-size: 2.5rem;
			}
			.dp-menu {
				display: none;
				grid-column: 3;
				justify-self: start;
			}

			.links {
				display: grid !important;
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