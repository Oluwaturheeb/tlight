	<?php 
	/*
	Author - Muhammad-Turyeeb Bello 
	Date and time 12:43am 4/5/2020
	*/

	require_once "Autoload.php";
	$title = "Docs";
	require_once "inc/header.php";
	?>

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
				<a href="/">Home</a>
				<a href="/about">About</a>
				<a href="/contact">Contact</a>
			</div>
			<div class="dp-link">
				<a href="/docs">Doc</a>
				<a href="/help">Help</a>
			</div>
		</div>
	</nav>
	<article class="container">
		<?php
			$doc = <<<'here'
	 		<h1>Tlight Doc</h1>

				<h2>Creating an app</h2>
				<p>Unlike some documentation i'll like to start by creating a project with <b>Tlight</b>.
				
				Folder structure
				
				auth(folder)
				class(folder)
				.htaccess(file)
				setup.php(file);
				
				
				
				to start an application we need to first edit the config file and change all that we needed to change.
				
				Now to app entry point which is `index.php` there are some constants we need to highlight
				
				const PNAME -> holds the application given name
				const jq -> lead directly to the jquery file in assets folder(in case you want to use jQuery lib)
				const js -> the path to the js file folder but needed a file appended to it aas its only a path to the js folder
				const style -> this also leads to the css folder
				
				the rest is up to you!!!
				
				Locate and open the <i>Config.php</i> file, you don't need to create a database it will create one for you the moment you run the setup.php after that you have the settings in the config file.
				After doing so if you have php in your system path then run:
				
				<code>$ php setup.php -i</code>
				
				If successful you get a "setup completed" message.
				
				What does this means
				
				Tlight comes with its own login system so it creates a table named <i>auth and relation</i> that helps in managing inter table relationship.
				</p>
				
				
				// this is Docs for Db class
				
				// to use this class you need to initialize it first
				
				// this way!
				
				<code>$d = Db::instance();</code>
				
				// and to access the methods is a piece of cake!
				
				First set the table!
				<code>$d->table("user");</code>
				
				// To insert data into the database
				<code>$d->add(["columns"], ["value"]);</code>
				
				<code>
					$new_data = $d->add(["username"], ["tlight"])->res();
					// i use res here to excute the query being generated by the add method. the point in assigning this to a variable is bcus the add method is going to return the inserted data id, but not necessary to assign. 
				</code>
				
				
				// to get data 
				
				// this get all rows from table user
				<code>$data = $d->get()->res();</code>
				
				// specifying columns
				
				<code>$data = $d->get(["id", "fullname"])->res();</code>
				
				// $data now holds the result of the query!
				
				// with where clause
				
				<code>
					$data = $d->get(["id", "fullname"])
					->where(1)
					->res();
				</code>
				
				// the above return object containing id and fullname of user id = 1
				
				// we can also have something like this 
				<code>
				$data = $d->get(["id", "fullname"])
					->where(["name", "tlight"])
					->res();
					// or
				
				$data = $d->get(["id", "fullname"])
					->where(["id", ">", "2"])
					->res();

					// or
				
				$data = $d->get(["id", "fullname"])
					->concat("or")
					->where(2, ["fullname", "tlight"])
				
					// or
				
				->where(2, ["fullname", "=", "tlight"])
					->res();
				
				/*
				
				1) the where results to "name = tlight";
				
				2) the where results to "id > 2"
				
				3) this mays look complicated of some sort but lets break it down.
				
				a) i added a concat method to the initial methods this is because the argument passed to where method is more than 1 and the concat method has a default of "and" 
				
				so if not called we are going to have something like "id = 2 and fullname = tlight" but in this case we have "id = 2 or fullname = tlight"
				
				b) anytime we are passing an array to the where method note
				
				where(['name', 'tlight']) ---> gives us "name = tlight"
				where(['name', "!=" 'tlight']) ---> gives us "name != tlight"
				
				there are still some more complicated where parameters but for now its ok
				
				*/
				</code>
				
				<code>
				// we can apply sorting 
				
				$data = $d->get(['name'])->sort()
				$data = $d->get(['name'])->sort("col", "order");
				
				/* by default the sort method is set to sort("id", "desc");
				so we can have something like the following */
				
				// this sort by id desc
				
				$data = $d->get(['name'])->sort();
				
				// this sort by name desc
				
				$data = $d->get(['name'])->sort("name");
				
				// this sort by name asc
				
				$data = $d->get(['name'])->sort("name", "asc");
				
				// in any case it should be use after the where method if the where method is going to be called
				
				// we also have pagination ready to use
				
				$data = $d->get()->pages(10);
				
				// the above load 10 per page
				
				</code>
				
				
				
				<h2>Joins and Union</h2>
				
				<code>
					// to use any of this two the table method will now accept and array instead of string!
					
					$d->table(["user", "post"]); # and more tables
					
					$d->get(["name", "title"])
					->use("join", ['left']) 
					->match([["a.id", "user"]])
					->res();
					
					/*
					introducing the use method
					
					it can be use with joins and unions
					
					when using it with join it take 2 parameter like about but with 
					1) specifying what to use in this case join
					2) then the type of join in an array
					
					the match method handles the join predicate
					
					it take a multi-dimensional array but if the column to match are the same it take a single array e.g
					*/
					
					match([["id", "user"]]) || match([["id", "!=", "user"]]) or match(["id"])
					
					// and where, sort, pages method can also be attached to it
					
					/* join */
					
					$d->get(["name"], ['title'])
					->use("union")
					->where([], [["name", "tee"]])
					->res();

				</code>
				
				<h2>Union</h2>
				
				<code>
					/*
					the use method in this case takes a single argument which specify that we are using union!
					the get method also take multiple array according to the number of tables
					the where in this method takes a multi-dimentional array and d number of the table must correlate with the number of the array
					
					e.g
					*/
					
					where([["name", "tlight"]], [["title", "post 1"]]);
					
					// and operators can be assigned to the arrays also and
					
					/*
					Note: in every case the where should be called for the union and if the query doesnt have a clause call the where method with empty arrays correlating with the number of table used
					
					*/
				
				</code>
				<h2>Update</h2>
				

				<code>
					// The set method is use to update data and it acccepts the array of columns and array of values to set
					
					set(['name', 'age'], ['tlight', 25]);
					
					// you can call the where method to specify column e.g
					
					$d->set(['name', 'age'], ['tlight', 25])
					->where(10) // this assumes id
				</code>
				
				<h2>CustomQuery</h2>
				
				<code>
				// the customQuery is accept 2 arguments the sql and the value, since we are using the pdo method for the db we need to change every assignment to ? e.g
				
				$d->customQuery("select * from profile where id = ? and name = ?", [2, "tlight"]);
				</code>
				
				<h2>Where</h2>
				
				<code>
				// so lets talk about the where method now 
				
				where(2)	// this mean id = 2
				
				where(['name', 'tlight'])	// this mean no assignment and the assignment fall backs to "=". so we have "name = tlight"
				
				where(["name"]) // in this case it means you are to use this with sub method
				
				where(['name', '=', 'tlight'])	// assignment given here so we have "name = tlight"
				
				where([["name", "age"], "tlight"]);		// this is given in a situation where by we want to check a value against two column this is going to give us "(name = tlight or age = tlight)" by default we use 'or' to build the where but it can also be overwritten e.g
				
				where([["name", "age"], ["and", "="], "tlight"])	//and the result is "(name = tlight and age = tlight)" the assignment operator provided is going to be used for both columns
				</code>

				<h2>Subquery</h2>
				<code>
				
				//first we need to build a normal query by using the get method
				
				$d->table("post") // string
				
				$data = $d->get(["title"])->where(["user"]) // the where should always look like this except if outer query
				</code>
				
				
				<h1>The Easy class</h1>
				
				<i>Note this class works class validate and all methods in this class uses the Validate::req() method to ease writing of code</i>
				
				<code>
				// lets get started by initializing it
				$e = new Easy();
				
				// we need to set the table to work with!
				
				$e->table("profile") // string
				</code>
				
				<h2>Create</h2>
				<p>
					this method as it is dont need any arguments as it works with the request object but it takes an optional columns only if the names in the form doesn't match with the db columns. So the column should be provided according to how the form is listed!
				
				</p>
				<code>$e->create();</code>
				
				<h2>Fetch</h2>
				<p>
					the default selection is to select all from the given table!
				
					but this can be overridden by specifying the columns to select
				
				
					This also doesnt take any argument except only if the form or the url that holds the input doesn't not match with the keys of the url or names of the form.
				
					example
				
					db column is user but the url holds id=10
					so for this reason it should be specified as in the normal where for the db class e.g ["user", "=", 10];
				
					** args
					fetch(cols, [id, 1], [user, 10], "or") // same as the url example except on can override the join to be "or" and in this case all argument must be given like above
				</p>
				<h2>Update</h2>
				
				<p>	
					this method collect the data to be updated in the request method and accept a where clause thats mandatory  and an optional column names only if the form/url names/keys doesnt match what is in the db
					
					*/
				
					<code>$e->update($where, $col = []);</code>
				</p>
				
				<h2>Delete</h2>
					
				<p>
					
					let say the url have
				
					** id=1&user=10
				
					breaking it to where id = 1 and user = 10;
				
					but the "and" can be overridden to have like eg
				
					*/
					<code>
						$e->del("or")
					
						// other argument is optional e.g
					
						$e->del("or", $col = [], $op = []);
					
						/*
						in this case the column only means if the form/url names/keys doesnt match what is in the db 
					
						and operator means we can change from the assignment to any operator we want
					
						*/
						
						$e->del("or", ["id", "user"], ["<", ">"]); // anyhow you want it
					
						// or no argument at all
					
						$e->del(); // and its a go!
					</code>
				</p>


				<h1>The Auth class</h1>
				
				<p>
					I have build many login system but this i am kinda proud of!
					<br>
					As part of the Auth class, there is an html attach file attached for login, register, lost password and change password all this forms are all in the auth/login.php file. It's only a form and can be included anywhere. Ajax handles the submission and auth/request.php handles the request. assets/css/app.css for styles and assets/js/app.js for javascript.
					<code>
					// lets initialize our class
					$a = new Auth();
					</code>
				</p>
				
				<h2>Login</h2>
				
				<p>there is already a form ready made for this method. Although it mayb awfully style but that can't be a problem as the styles can be changed!
				
				since protecting password is a big deal this days and changing password regularly is recommend by all security expert i implemented changing of password with the login method

				<code>
				// calling the login
				$a->login();
				</code>
				<h3>Args</h3>
				
				all arguments are optional
				
				<code> 
					$col(array) // the column only if the input doesn't match the db column
				
					$check(bool) // to weather the method should call the last password change or not default to true
				
					$e->login($col = [], $c = true); // include last password change default
				
					/*
					anytime using any method in this class its recommended to assign a variable to the method call!
					*/
				</code>
				
				$log = $e->login();
				
				</p>
				
				<h2>Reg</h2>
				<code>
					$reg = $e->reg(); // no argument or could be optional column
					$col(["array of columns"]) // the column only if the input fields doesn't match the db column
				</code>
				
				<h2>Set</h2>
				<p>
				// and it accepts a single optional value to set user session, default  to "user"
				
				// the set method works only with login and reg;
				// it evaluates what the result of both method returns and calculate the last log indays and also set session
				
				// it returns "ok" on success
				//  "change" if the password change calculated is over 30days
				// and the variable assigned should be echo for the error
				<code>
					$reg = $e->reg()->set();
					
					// in this wake one may like to use $result instead of set() method to return the result of the login
					
					$log = $a->login()->result;
				</code>
				</p>
				
				<h2>Change password</h2>
				<p>
				// this method is called to change or update password!
				// takes single optional value to get user session key default  to "user"
				
				<code>
					$ch = $a->chpwd();
				</code>
				</p>
				<h2>Lost password</h2>
				<p>
				methods still needs to be worked on as it requires sending email not until i tested it i won't recommend using it
				
				<code>
					$e->lpass();
				</code>
				</p>
here;
			echo nl2br($doc);
			?>
	</article>
	s<style type="text/css">
		body {
			background: #000;
			color: var(--text);
			font-size: 1.3rem !important;
		}

		.container {
			display: grid;
			justify-content: center;
		}

		code {
			display: block;
			border: 1px inset var(--sec);
			color: var(--sec);
			padding: 4px;
		}

		a {
			text-decoration: none;
			color: var(--link);
		}

		h1,
		h2,
		h3 {
			color: var(--text);
		}

		p {
			margin-left: 10px;
		}

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
			justify-content: end;
			width: 150px;
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
				grid-template-columns: auto 2fr auto
			}

			.dp-menu {
				display: none;
				grid-column: 3;
				justify-self: start;
			}

			.links {
				position: static !important;
				width: auto !important;
				justify-self: end !important;
				display: grid;
				grid-auto-flow: column;
			}

			.links .menu-link {
				display: grid;
				grid-auto-flow: column !important;
			}

			.dp-link {
				position: static;
				right: 0;
				top: 5.6rem;
				background: var(--pry);
				display: grid !important;
				;
				grid-auto-flow: column !important;
				grid-row: 1/1;
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
	<?php require_once "inc/footer.php";