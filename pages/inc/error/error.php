<?php list($code, $codeText, $msg) = $title; ?>
<html>
	<head>
	
	</head>
	<body>
		<div class="container">
			<div class="mini">
				<h2>< Tkode ></h2>
				<p style="align-self: start">In need of an expert for managing and building your brand webApp, ☎ <a href="tel:08121001052">contact</a> tkode.<br>Services includes</p>
				<ul style="align-self: start">
					<li>Code management</li>
					<li>Great SEO</li>
					<li>PWA apps</li>
					<li>Server Management</li>
					<li>Class base & Readable code</li>
					<li>Reusable code with versioning </li>
				</ul>
			</div>
			<div class="main">
				<h1><?php echo $code ?>&nbsp;<small><?php echo $codeText ?></small></h1>
				<p><?php echo $msg ?></p>
			</div>
			<style>
				body {
					margin: 0;
				}
				.container {
					display: grid;
					grid-template-columns: 25% 70%;
					grid-gap: 5%;
				}
				
				.mini {
					text-align: center;
					background: #FB5454;
					height: 100vh;
					color: #E1CECE;
					padding: 1rem;
				}
				
			</style>
		</div>
	</body>
</html>