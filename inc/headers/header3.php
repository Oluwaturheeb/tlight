	<nav class="">
		<div class="logo">
			<div class="dp-menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="dp-link">
				<a href="#">Login</a>
				<a href="#">Register</a>
			</div>
			<a href="/" class="logo">
				tlight
			</a>
		</div>
		<div class="links">
			<a href="#">Home</a>
			<a href="#">About</a>
			<a href="#">Contact</a>
			<a href="search" class="search">Search</a>
		</div>
		<div id="search">
			<form method="post">
				<div class="input-group">
					<span>
						<input type="search" name="keyword" class="form-control">
						<button>Search</button>
					</span>
				</div>
			</form>
			<span class="close">&times;</span>
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
			display: grid;
			grid-auto-flow: column;
			grid-gap: 1rem;
			align-items: center;
		}

		.logo .dp-menu {
			padding: 5px;
			display: inline-block;
			cursor: pointer;
		}

		.logo .dp-menu span {
			display: block;
			background: var(--sec);
			padding: 2px .9rem;
			margin: 5px;
		}

		.logo .dp-menu span:nth-child(even) {
			margin: 5px 0px 5px 5px;
		}

		.logo .dp-link {
			display: none;
			position: absolute;
			left: 0;
			top: 5rem;
			background: var(--pry);
			box-shadow: 2px 0px 5px var(--pry);
		}

		.logo .dp-link a {
			display: block;
			padding: 1rem;
		}

		nav .logo a[href='/'] {
			color: var(--sec);
			font-style: italic;
			font-size: 2.5rem;
		}

		nav .links {
			display: grid;
			grid-auto-flow: column;
			grid-gap: 1rem;
		}

		.links a:nth-last-of-type(n + 2) {
			display: none;
		}


		nav .links a,
		nav .logo .dp-link a {
			color: var(--sec);
		}

		.links a:hover,
		.links a:focus,
		.dp-link a:hover,
		.dp-link a:focus {
			border-bottom: 1px solid var(--sec);
		}

		#search {
			display: none;

		}

		#search span.close {
			padding: 5px;
			position: absolute;
			right: 0;
			top: 0;
			cursor: pointer;
		}

		@media (min-width: 576px) {
			.links a {
				display: block !important;
			}
		}

	</style>
	<script type="text/javascript">
		$('.search, span.close').click(function(e) {
			e.preventDefault();

			$('#search, .links').toggle();
		});

		$('.dp-menu').click(() => {
			$('.dp-link').slideToggle(500);
		});

	</script>
