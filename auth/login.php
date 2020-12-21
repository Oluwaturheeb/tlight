
<div class="auth">
	<header class="auth-links">
		<div class="login active">Login</div>
		<div class="register">Register</div>
	</header>
	<div class="auth-content">
		<div id="login">
			<h2>Login</h2>
			<form method="post" action="">
				<div>
					<label>Email</label>
					<input type="email" name="email" placeholder="Email" class="input-line">
				</div>
				<div>
					<label>Password</label>
					<input type="password" name="password" placeholder="Password" class="input-line">
				</div>
				<?php echo csrf(); ?>
				<div id="captcha"></div>
				<input type="hidden" name="type" value="login">
				<div>
					<div class="info"></div>
					<button class="button">Login</button>
					<small><a href="lpwd" class="lpwd">Forgot password?</a></small>
				</div>
				<div class="opt"><a href="register" class="register">Register</a></div>
			</form>
		</div>
		<div id="register">
			<h2>Register</h2>
			<form method="post" action="">
				<div>
					<label for="">Email</label>
					<input type="email" name="email" placeholder="Email" class="input-line">
				</div>
				<div>
					<label>Password</label>
					<input type="password" name="password" placeholder="Password" class="input-line">
				</div>
				<div id="captcha"></div>
				<?php echo csrf(); ?>
				<input type="hidden" name="type" value="register">
				<div>
					<div class="info"></div>
					<button class="button">Register</button>
				</div>
				<div class="opt"><a href="login" class="login">Login</a></div>
			</form>
		</div>
		<div id="lpwd">
			<h2>Lost password</h2>
			<form method="post" action="">
				<div>
					<label>Email</label>
					<input type="email" name="email" placeholder="Email" class="input-line">
				</div>
				<div id="captcha"></div>
				<?php echo csrf(); ?>
				<input type="hidden" name="type" value="lpwd">
				<div>
					<div class="info"></div>
					<button class="button-block">Reset</button>
				</div>
				<div class="opt"><a href="login" class="login">Login</a> | <a href="register" class="register">Register</a></div>
			</form>
		</div>
		<div id="chpwd">
			<h2>Change password</h2>
			<div class="days"></div>
			<form method="post" action="">
				<div>
					<label>New password</label>
					<input type="password" name="password" placeholder="New password" class="input-line">
				</div>
				<div>
					<label>Verify password</label>
					<input type="password" name="verify" placeholder="Verify password" class="input-line">
				</div>
				<div id="captcha"></div>
				<?php echo csrf(); ?>
				<input type="hidden" name="type" value="chpwd">
				<div>
					<div class="info"></div>
					<button class="button-block">Reset</button>
				</div>
				<div class="opt">
					<a href="skip" class="skip">Skip</a>
				</div>
			</form>
		</div>
	</div>
</div>
<style type="text/css">
	
	/*when starting a project this can be included in your css files*/
	
	.auth {
/* 		display: grid;
		justify-content: center;
		grid-gap: 10px; */
		
	}

	.auth-links {
		display: grid;
		grid-template-columns: 1fr 1fr;
		background: var(--pry);
	}

	.auth-links div {
		text-align: center;
		color: var(--sec);
		cursor: pointer;
		padding: 1rem 0 !important;;
		-moz-user-select: none;
	}

	.auth-links .active {
		border-bottom: 5px solid var(--sec);
		color: var(--sec);
	}

	.auth-links div {
		font-weight: bold;
	}

	.auth-content >  div:nth-child(n + 2) {
		display: none;
	}

	.auth form div {
		padding: 10px 0;
	}

	.auth-content label {
		display: none;
		font-size: 12px;
		font-style: oblique;
	}

	::placeholder {
		font-style: oblique;
	}

	.input-line, .input-round {
		display: block;
		width: 100%;
		background: transparent;
		padding: 10px;
		transition: 2s;
		font-size: inherit;
		color: var(--pry);
		margin: 10px 0;
		border-style: groove;
		color: inherit;
	}

	.input-round {
		border-radius: 1rem;
	}

	.input-line {
		border-left: none;
		border-top: none;
		border-right: none;
		border-bottom: 2px groove var(--pry);
	}

	.auth input:hover {
		border-color: var(--hover);
	}

	.auth div small {
		float: right;
	}

	.info {
		padding: 0 !important;
	}

	.auth .button, .button-block {
		padding: 1rem;
		background: var(--pry);
		font-weight: bold;
		color: var(--sec);
		border: none;
		border-radius: 5px;
	}

	.auth .button-block {
		width: 100%;
	}

	.button:hover, .button-block:hover {
		background: var(--hover);
	}

	.auth .opt {
		display: grid;
		grid-auto-flow: column;
		justify-content: space-around;
		font-weight: bold;
		border-top: 1px solid var(--sec);
		width: 100%;
		margin: 1rem 0;
	}

	.auth #captcha {
		background: #eee;
		display: inline-block;
		font-size: 2rem;
		font-style: oblique;
		word-spacing: 10px;
	}
</style>