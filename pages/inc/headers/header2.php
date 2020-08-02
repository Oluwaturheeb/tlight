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
				<a href="#" class="search">Search</a>
			</div>
			<div class="dp-link">
				<a href="/login">Login</a>
				<a href="/register">Register</a>
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
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 1rem;
			position: relative;
		}

		nav .dp-menu {
			padding: 5px;
			display: inline-block;
			cursor: pointer;
		}

		nav .logo {
			font-size: 1.8rem;
			font-weight: bold;
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
			top: 4.3rem;
			background: var(--pry);
			width: 150px;
			display: none;
			z-index: 100;
		}


		nav .links a {
			display: block;
			padding: 1rem;
			color: var(--sec);
		}

		.links a:hover,
		.links a:focus {
			margin-bottom: -2px;
			border-bottom: 2px solid var(--sec);
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
			.dp-menu {
				order: 3;
				justify-self: end;
			}

			.dp-link {
				display: none;
			}

			.links,
			#search {
				flex-grow: 5;
				justify-content: end;
				width: auto !important;
				position: static !important;
			}

			.links, .links .menu-link {
				display: flex !important;
				flex-flow: row !important;
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