	<nav class="">
		<div class="logo">
			<a href="/" class="logo">
				<?php echo PNAME ?>
			</a>
		</div>
		<div class="links">
			<a href="/">Home</a>
			<a href="/about">About</a>
			<a href="/contact">Contact</a>
		</div>
	</nav>
	<style type="text/css">
		nav {
			display: flex;
			flex-flow: column;
		}

		nav .logo {
			text-align: center;
			margin: 10px 0;
		}

		nav .logo a {
			color: var(--pry);
			font-style: italic;
			font-size: 2.5rem;
		}

		nav .logo a:hover,
		nav .logo a:focus {
			color: #f44646;
		}

		nav .links {
			background: var(--pry);
			display: flex;
			justify-content: center;
			grid-gap: 1rem;
			padding-bottom: 10px !important;
		}

		nav .links a {
			color: var(--sec);
			padding: 1rem;
		}

		.links a:hover,
		.links a:focus {
			border-bottom: 2px solid var(--sec);
			margin-bottom: -2px;
		}
	</style>