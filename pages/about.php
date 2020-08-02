<?php 
require_once "Autoload.php";
$title = "About";
require_once "inc/header.php";
?>
	<div class="container">
		<article>
			<h1>About Tlight</h1>
			<section>
				<p><b>Tlight</b> is a <b>PHP</b> module(i'll call it that) that i decided to build from scratch to test my knowledge of the language(PHP).</p>
				<p>Tlight is built with OOP(Object Oriented Programming) and uses the built-in PHP PDO(PHP Data Object).</p>
				<p>Tlight has many functionality that includes:
					<ul>
						<li>Login (single and multiple login system).</li>
						<li>Registration (simple)</li>
						<li>Lost password</li>
						<li>Added 7 headers for quick development</li>
						<li>The Validate javascript class for front-end validation</li>
						<li>File upload with compressionion</li>
						<li>CLI Import/Export of database(mysql)</li>
						<li>Rich php components and utilities</li>
					</ul>
					<p>
						<b>Login</b> &raquo; The login system was built with security and proper validation in mind. The login aspect features both single and multiple login system. With additional security if enabled in the config file, tlight checks for login attempts and return with a captcha request every the limit exceeded and also on the other hand tlight checks for the last time a user changed his/her password according to the configuration file.
					</p>
					<p>
						<b>Registration</b> and <b>Lost password</b> &raquo; And of cause the registration and lost pasword is really a simple form with email and password but this can be extended to suit your needs.
					</p>
					<p>
						<b>Headers</b> &raquo; 7 different type of headers are included to hasten development and leave you to face the logic of the project at hand.<br>
					</p>
					<p>
						<b>Validate class</b> &raquo; The javascript validate class id dependent on jQuery lib. It also helps save time from handling form validation. And if you are a big fan of ajax like me there is also a jquery ajax method to help you after successful validation to help send request in form of REST API.
					</p>
					<p>
						<b>File upload</b> &raquo; Tlight also help in handling file upload in a very simple and fast way with file validation and image compression level of 70% reduction in file size and the image not loosing its quality.
					</p>
					<p>
						<b>Import and Export</b> &raquo; Tlight also uses the cli to make it easier for import and export of the database given for the project, this only support MYSQL database for now.
					</p>
					<p>
						<b>Components and Utilities</b> &raquo; 
						Tlight also has some really simple database class that include (simple, join, union and custom query statement execution).
						And also there is a class helper that helps in mapping incoming requests(GET/POST) into crud(CREATE, READ, UPDATE, DELETE). And some other utilities to be covered in documentation section.
					</p>
					<p>And there is a handy documention file in place for to help in using this module.</p>
				</p>
			</section>
		</article>
	</div>
	<style type="text/css">
		body {
			background: #000;
			color: var(--sec);
			font-size: 1.3rem !important;
			font-family: serif;
		}

		a {
			text-decoration: none;
			color: var(--link);
		}

		h1 {
			color: var(--text);
		}

		article {
			line-height: 1.5;
			margin: 0 1rem;
		}

		p {
			padding: 10px;
		}
/*
		p::before {
			content: " ";
			padding: 1rem;
		}
*/
		p b, b {
			color: var(--text)
		}
				
		@media (min-width: 576px) {
			article {
				margin: 0 5rem;
			}
		}
	</style>
<?php require_once "inc/footer.php";