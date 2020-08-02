	<nav class="">
		<div class="logo">
			<a href="/" class="logo">
				<?php echo PNAME ?>
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
				<a href="/login">Login</a>
			</div>
		</div>
	</nav>
	<style type="text/css">
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
			font-size: 1.8rem;
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
			top: 4.5rem;
			background: var(--pry);
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
				grid-template-columns: auto 2fr auto;
			}
			.dp-menu {
				grid-column: 3;
				justify-self: start;
			}

			.links {
				display: grid !important;
				position: static !important;
				width: auto !important;
				justify-self: end !important;
			}

			.links .menu-link {
				display: grid;
				grid-auto-flow: column !important;
			}

			.dp-link {
				position: absolute;
				right: 0;
				top: 5.6rem;
				background: var(--pry);
				width: 150px;
				display: none;
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