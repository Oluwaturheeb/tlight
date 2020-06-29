	<nav class="">
		<div class="logo">
			<div class="dp-menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="dp-link">
				<a href="/login">Login</a>
				<a href="/register">Register</a>
			</div>
			<a href="/" class="logo">
				<?php echo PNAME ?>
			</a>
		</div>
		<div class="links">
			<a href="/">Home</a>
			<a href="/about">About</a>
			<a href="/contact">Contact</a>
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
		.container {
			padding: 3rem 1rem 1rem 1rem;
			transition-duration: 1s;
		}

		nav {
			background: var(--pry);
			display: grid;
			grid-auto-flow: column;
			justify-content: space-between;
			grid-gap: 1rem;
			align-items: center;
			padding: .5rem;
			position: fixed;
			width: calc(100% - .5rem);
		}

		nav .logo {
			display: grid;
			grid-auto-flow: column;
			grid-gap: 1rem;
			align-items: center;
		}

		.logo .dp-menu {
			position: fixed;
			padding: 5px;
			display: inline-block;
			cursor: pointer;
			left: 12rem;
			bottom: 3rem;
			border-radius: 100%;
			opacity: .7;
			background: var(--pry);
			padding: 1rem 10px;
			transition-duration: 1s;
		}

		.logo .dp-menu:hover,
		.logo .dp-menu:focus {
			opacity: 1
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
			margin-left: -50rem;
			position: fixed;
			left: 0;
			top: 4.6rem;
			background: var(--pry);
			box-shadow: 2px 0px 5px var(--pry);
			width: 10rem;
			height: 100%;
			transition-duration: 1s;
			z-index: 100;
		}

		.logo .dp-link a {
			display: block;
			padding: 1rem;
			border-bottom: 2px solid var(--sec);
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

		nav .links a,
		nav .logo .dp-link a {
			color: var(--sec);
			display: block;
			padding: 1rem
		}

		.links a:hover,
		.links a:focus,
		.dp-link a:hover,
		.dp-link a:focus {
			border-bottom: 2px solid var(--sec);
			margin-bottom: -2px;
			font-weight: bold;
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

		.dp-link a:hover,
		.dp-link a:focus {
			background: var(--sec);
			color: var(--pry) !important;
		}

		.links a:nth-last-of-type(n + 2) {
			display: none;
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

			if ($('.dp-menu').hasClass('active')) {
				$('.dp-link').css({
					'margin-left': '-50rem'
				})
				$('.dp-menu').removeClass('active');
				$('.container').css({
					'margin-left': 0
				});
			} else {
				$('.dp-link').css({
					'margin-left': 0
				})
				$('.dp-menu').addClass('active')
				$('.container').css({
					'margin-left': '11rem'
				});
			}
		});

	</script>