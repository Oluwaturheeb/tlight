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
				<a href="#">Home</a>
				<a href="#">About</a>
				<a href="#">Contact</a>
				<a href="#" class="search">Search</a>
			</div>
			<div class="dp-link">
				<a href="#">Login</a>
				<a href="#">Register</a>
			</div>
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
			margin-bottom: -1px;
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
			nav {
				justify-content: stretch;
			}

			.logo {}

			.dp-menu {
				grid-column: 3;
				justify-self: end;
			}

			.dp-link {
				display: none;
			}

			.links,
			#search {
				justify-self: end;
				position: static !important;
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
			}
		}

	</style>
	<script type="text/javascript">
		$('.dp-menu').click(() => {
			if ($(window).innerWidth() < 576) {
				$('.links').slideToggle(500);
			} else {
				$('.dp-link').toggle();
			}
		});

		$('.search, span.close').click(function(e) {
			e.preventDefault();

			$('#search, .dp-menu, .links').toggle();
		});

	</script>
