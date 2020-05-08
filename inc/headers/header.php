
	<nav class="">
		<div class="logo">
			<a href="/" class="logo">
				tlight
			</a>
		</div>
		<div class="links">
			<a href="#">Home</a>
			<a href="#">About</a>
			<a href="#">Contact</a>
			<div class="dp-menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="dp-link">
				<a href="#">Login</a>
				<a href="#">Register</a>
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

		nav .logo {
			font-size: 2.5rem;
		}

		nav .logo a {
			color: var(--sec);
			font-style: italic;
		}

		nav .links {
			display: grid;
			grid-auto-flow: column;
			grid-gap: 1rem;
			align-items: center;
		}

		nav .links a {
			color: var(--sec);
		}

		.links .dp-menu {
			padding: 5px;
			display: inline-block;
			cursor: pointer;
		}

		.links .dp-menu span {
			display: block;
			background: var(--sec);
			padding: 2px .9rem;
			margin: 5px;
		}

		.links .dp-menu span:nth-child(even) {
			margin: 5px 0px 5px 5px;
		}

		.links .dp-link {
			display: none;
			position: absolute;
			right: 0;
			top: 5rem;
			background: var(--pry);
			box-shadow: 2px 0px 5px var(--pry);
		}

		.links .dp-link a {
			display: block;
			padding: 1rem;
		}

		.links a:hover, .links a:focus {
			border-bottom: 1px solid var(--sec);
		}
	</style>
	<script type="text/javascript">
		$('.dp-menu').click(() => {
		  $('.dp-link').slideToggle(500);
		});
	</script>