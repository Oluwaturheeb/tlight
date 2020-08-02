<?php
require_once "Autoload.php";

$title = "Docs";
require_once "inc/header.php";
?>
<div class="container">
	<article class="">
		<?php
			$doc = <<<'here'
			<section>
 			<h2>Tlight Documentation</h2>
			<h3>Content</h3>
				<div class="content-table">
					<a href="/docs#dir">Directory listing</a>
					<a href="/docs#start">Starting a prohect</a>
					<a href="/docs#headers">Headers</a>
					<a href="/docs#front-end">Front-end validation and Ajax</a>
					<a href="/docs#file-upload">File upload</a>
					<a href="/docs#database">The Database class</a>
					<a href="/docs#utlities">Utilities</a>
				</div>
				<div id="dir">
					<h3>Directory listing</h3>
					<b>auth</b> (folder)
					<b>components</b> (folder)
					<b>pages</b> (folder)
					<i>.htaccess</i> (file)
					<i>tlight</i> (file);
					<i>config.php</i> (file)

					<h3>Auth folder</h3>
					The auth folder contains the default login system bundled with <b>Tlight</b>
					The login.php file can be included anywhere including a div tag or an entire page.
					<h3>Components folder</h3>
					The components folder contains all the classes that makes Tlight, <b>Tlight</b>.
					<h3>The files</h3>
					The <i>.htaccess</i> file and <i>tlight</i> file are not to be edited as it contains sensitive information that can destabilize the application.
					The config file should be reviewed as it holds important settings for the application.
					<h3>Pages folder</h3>
						This folder holds all the pages of the project to be created!
				</div>
			</section>
			<section>
				<div id="start">
					<h3>Starting a project</h3>
					To start an project we need to first edit the <i>config.php</i> file and change all that we needed to change.

					<h4>The config file</h4>
					The config files holds several import values that need to be reviewed such as 

					<h4>The project option</h4>
					<ul>
						<li>The project name</li>
						<li>The language for project</li>
						<li>The region to be used for server timezone</li>
					</ul>
					<h4>The db</h4>
					<ul>
						<li>The db option collects information needed to connect to the database(in this case mysql)</li>
					</ul>
					<h4>The session option</h4>
					<ul>
						<li>Session name to be use instead of having something like PHPSESS_ID its going to replaced w2ith whatever value was given</li>
						<li>The session domain need to point to the url of this project else session ai'nt gonna work and forms wont be sumitted too.</li>
						<li>Session</li>
					</ul>
					<h4>The auth option</h4>
					<ul>
						<li><b>single</b> this checks for weather the app is multi login application or not.</li>
						<li><b>login_attempts</b> This option returns a captcha alphanumeric character if the limit of tries exceed by the end user..</li>
						<li><b>last_pc</b> The last password change option checks for the last time the end user changed the password. if the given days have reached tlight prompt the user to change password.</li>
					</ul>
					<h4>The state option</h4>
					<ul>
						<li><b>development</b> this option checks for weather to display errors for debugging purposes.</li>
					</ul>
					<h3>The file upload option</h3>
					<ul>
						<li><b>max-file-upload</b> this set the maximum number of files to be uploaded at a go.</li>
						<li><b>rename file</b> this also checks weather to rename files b4 uploading.</li>
					</ul>
					<h3>Important constant</h3>
					<ul>
						<li><b>const PNAME</b> holds the application given name</li>
						<li><b>const jq</b> lead directly to the jquery file in assets/js/jquery folder(in case you want to use jQuery lib)</li>
						<li><b>const js</b> the path to the js file folder but needed a file appended to it as its only a path to the js folder</li>
						<li><b>const style</b></li> this also leads to the css folder
					</ul>
					After reviewing and or editing the <i>config.php</i> file run:
					<code> $ php tlight -i </code>
					With this command this create a database for you if you have not created one.
					If successful you get a "setup completed" message.
					What does this means!
					Tlight comes with its own login system so it creates a table named <i>auth</i> that helps in managing the default login.
					After you might want to use the default template to relieve you of creating files.
					I have created a project skeleton just for you, just run:
					<code>$ php tlight template</code>
					This will create some files in the <b>pages</b> folder. The files include <i>index.php, create.php, post.php, logout.php, admin.php, delete.php, update.php</i> and this files contains functionality to help you get familiar with <b>Tlight</b>.
					And any other files to be created should always start with requiring the <i>Autoload.php file</i>
				</div>
				<div id="headers">
					<h3>Headers</h3>
					<p>Tlight was built to help in completing project and to help in proficiency, tlight has 1 - 8 navigation bars and more to be added in the future.
					After choosing preferred header run 
					<code><i>// to set default header run </i>
					$ php tlight header {filename without the .php extension}
					
					<i>// This command will create a new file `defaultHeader.php` in pages/inc directory. You can now include it in header.php file to save time including it all the time for every page </i>
					
						&lt;?php require_once "defaultHeader.php"; ?&gt;
					</code>
					</p>
				</div>
				<div id="front-end">
					<h3>Front-end validation</h3>
					The javascript Validate class helps in form validation and submitting for either file upload or normal form (post/get), this class is totally independent on the javascript jQuery lib.
					<strong>Syntax</strong>
					<code>`html`
						&lt;form&gt;
							&lt;div&gt;
								&lt;label for="some_text"&gt; Some text&lt;/label&gt;
								&lt;input type="text" name="some_text" id="some_text"&gt;
							&lt;/div&gt;
							&lt;div&gt;
								&lt;label for="foo"&gt;Foo&lt;/label&gt;
								&lt;input type="text" name="foo" id="foo"&gt;
							&lt;/div&gt;
						&lt;/form&gt;

						`js`
						$('form').submit(function (e) {
							e.preventDefault();

							v.autoForm(this);

							if (v.check()) {
								console.log(v.thrower());
							} else {
								<i>// this returns the serialized form </i>
								console.log(v.auto);
								<i>// and also you can call Validate withAuto() method for ajax request for post/get/</i>
								v.withAuto();
							}
						});
					</code>

					And validation includes 

					<ul>
						<li>Text field</li>
						<li>File field</li>
						<li>Textarea</li>
						<li>Select</li>
						<li>checkbox / radio button</li>
					</ul>
					<h3>The Validate class</h3>
					The Validate class is heavily dependent on jQuery as mentioned earlier. So lets go straight to methods in this class.

					<ul>
						<li><b>autoForm()</b> &raquo; we have already walked through how this method works.</li>
						<li><b>withAuto</b> &raquo; this method takes 3 optional parameters
						1. an element with a given class to display some text like loading and the server response.
						2. an object to for error, success and where to display the result of the success message
						3. where to redirect to if ajax response is successful</li>
						<code>`e.g`
							&lt;form&gt;
								&lt;div&gt;
									&lt;label&gt;&lt;/label&gt;
									&lt;input name="tlight" id="tlight"&gt;
								&lt;/div&gt;
								&lt;div&gt;
									&lt;div class="info"&gt;&lt;/div&gt;
									&lt;button&gt;Submit&lt;/button&gt;
								&lt;/div&gt;
							&lt;/form&gt;
							&lt;div class="result"&gt;&lt;/div&gt;
							`js`
							$('form').submit(function(e) {
								e.preventDefault();

								v.autoForm(this);

								if (v.check()){
									<i>// error first method</i>
									console.log(v.thrower());
								} else {
									<i>// </i>
									v.withAuto('.info', {ok: "success", error: "something went wrong", data: ".result"});
								}
							})
						</code>
						The '.info' element shows all the messages the withAuto method returns including error, success and loading. Optionally it can also be told to alert all message by giving the "alert" as argument.
						On the other hand the object clearly shows the message and the data key once given overrides the redirection, so should be use only when necessary/
						The third parameter is ommitted because of the data object key but should be given if the data object key is not given else the default action is to reload!
						<li><b>form method</b> &raquo; This method is the parent to the autoForm method. To use this method </li>

						<code>`sample code`
						<i> /* after disabling the form submission
						form actually takes the exactly number of input, textarea and select in the form as argument
						*/ </i>
						form({
							<i>//input-id: {rules}</i>
							'#tlight': {
								require: bool,
								email: bool,
								number: bool,
								wordcount: int,
								min: int,
								max: int,
								match: 'id of the input to match',
								checkbox: bool,
								file: bool,
								fileMin: int,
								fileMax: int
							}
						});
						<i>// check if the validation is successful or throw error</i>
						if (v.check()){
							<i>// error first method</i>
							console.log(v.thrower());
						} else {
							<i>// do anything after successful validation</i>
						}
						</code>
						<li><b>capFirst</b> &raquo; This method capitalize the first letter of a string</li>
						<li><b>getInput</b> &raquo; This method returns the value of an html input element</li>
						<li><b>empty</b> &raquo; This method by default collects only the class or id of the input element and if given true as the argument it only work with the given string. return true if empty and false otherwise.</li>
						<li><b>checkbox</b> &raquo; This method makes sure at least one checkbox is selected in a form</li>
						<li><b>redirect</b> &raquo; This method redirect to the given destination if non is given it reloads the page</li>
						<li><b>store</b> &raquo; This method store data in the client localstorage. It takes 2 parameters.
						1. what you want i.e the key to the item you need
						2. what you want to do i.e rm, get, set(in this case this first argument takes an array [name, value])</li>
						<li><b>checkMatch</b> &raquo; This method takes 2 arguments
						1. the field to check
						2. the field to match</li>
						<li><b>fileCheck</b> &raquo; This method only takes the file input field</li>
						<li><b>thrower</b> &raquo; this method returns the error encounter during validation process</li>
						<li><b>check</b> &raquo; this method checks if there is error while validating. Returns true if there is error or false otherwise.</li></ul>
				</div>
				<div id="file-upload">
					<h3>File Upload</h3>
					<p>Tlight file uploader is easy and configurable to use. Files are automatically compressed on the fly during the file upload process applied to images only. To upload file(s) just a little twick to the HTML form that handles the file.
					<code>
						&lt;form action="" enctype="multipart/form-data"&gt;
							&lt;div&gt;
								&#09;&#09;&lt;label for="files"&gt;Files&lt;/label&gt;
								&#09;&#09;&#09;<i>&lt;!-- The name attributes will accept an array even for single file field --&gt;</i>
								&#09;&#09;&#09;<i>&lt;!-- You can also add the min max to help with file validation with the Validate class --&gt;</i>
								&#09;&#09;&lt;input type="file" name="files[]" id="files"&gt;
							&#09;&lt;/div&gt;
							&#09;&lt;div&gt;
								&#09;&#09;&lt;button type="submit" id="foo"&gt;
							&#09;&lt;/div&gt;
						&#09;&lt;/form&gt;

						`js`
						$('form').submit(function (e) {
							e.preventDefault();

							v.autoForm(this);

							if (v.check()) {
								console.log(v.thrower());
							} else {
								<i>// this returns the serialized form </i>
								console.log(v.auto);
								<i>// and also you can call Validate method withAuto() to submit the form</i>
								<i>// v.withAuto(); </i>
							}
						});

						`php`

						&lt;?php
						$v = new Validate();
						$v->uploader(); <i>// calling this method automatically validate all media file type(image, video, audio) and upload them.</i>
						<i>You can also collect the uploaded file by calling the preview method of the Validate class </i>
						$files_preview = $v->preview();

						<i>// And you can now move the files to any preferred location on the webserver</i>
						$upload = $v->complete_upload(""); <i>// this method accepts where to save the files as option and its required</i>
					</code>

					Accepted media files are 
					<code><i>// for images </i>
					['image/pjpeg', 'image/jpeg', 'image/gif', 'image/bmp', 'image/png']
					<i>// for videos </i>
					['video/mpeg', 'video/mp4', 'video/quicktime', 'video/mpg', 'video/x-msvideo', 'video/x-ms-wmv', 'video/3ggp'] 
					<i>// for audios </i>
					['audio/mid', 'audio/mp4', 'audio/mp3', 'audio/ogg', 'audio/wav', 'audio/3gpp', 'audio/mpeg']
					</code>
				</div>
				<div id="database">
					<h2>The database class</h2>
					To use this class you need to initialize it first
					<code>$d = Db::instance();</code>
					And to access the methods is a piece of cake!
					First set the table!
					<code>
						$d->table("user");
					</code>
					<u>To insert data into the database</u>
					<code>
						$d->add(["columns"], ["value"]);
					</code>
					<code>
						$new_data = $d->add(["username"], ["tlight"])->res();
					</code>
					I use res here to excute the query being generated by the add method. the point in assigning this to a variable is bcus the add method is going to return the inserted data id, but not necessary to assign. 
					<u>To get data</u>
					This get all rows from table user
					<code>$data = $d->get()->res();</code>
					<u>Specifying columns</u>
					<code>
						$data = $d->get(["id", "fullname"])->res();
						<i>// $data now holds the result of the query!</i>
					</code>
					<u>With where clause</u>
					<code>
						$data = $d->get(["id", "fullname"])
						->where(6)
						->res();
					</code>
					The above return object containing id and fullname of user id = 1
					We can also have something like this 
					<code>
						$data = $d->get(["id", "fullname"])
							->where(["name", "tlight"])
							->res();
							<i>// or</i>
						
						$data = $d->get(["id", "fullname"])
							->where(["id", ">", "2"])
							->res();

							<i>// or</i>
						
						$data = $d->get(["id", "fullname"])
							->concat("or")
							->where(2, ["fullname", "tlight"])
						
							<i>// or</i>
						
						->where(2, ["fullname", "=", "tlight"])
							->res();
						<i>
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
						</i>
					</code>
					<u>We can apply sorting</u>
					<code>
						$data = $d->get(['name'])->sort()
						$data = $d->get(['name'])->sort("col", "order");
					</code>
					By default the sort method is set to <i>sort("id", "desc");</i>
					so we can have something like the following */
					<code>
						<i>// this sort by id desc</i>
						
						$data = $d->get(['name'])->sort();
						
						<i>// this sort by name desc</i>
						
						$data = $d->get(['name'])->sort("name");
						
						<i>// this sort by name asc</i>
						
						$data = $d->get(['name'])->sort("name", "asc");
						
						<i>// in any case it should be use after the where method if the where method is going to be called</i>
						
						<i>// we also have pagination ready to use</i>
						
						$data = $d->get()->pages(10);
						
						<i>// the above load 10 per page</i>
					</code>
					<h3>Joins and Union</h3>
					
					<code>
						<i>// to use any of this two the table method will now accept and array instead of string!</i>
						
						$d->table(["user", "post"]); # and more tables
						
						$d->get(["name", "title"])
						->use("join", ['left']) 
						->match([["a.id", "user"]])
						->res();
					</code>
						<u>The use method</u>
						It can be use with joins and union.
						When using it with join it take 2 parameter 
						<ul>
							<li> specifying what to use in this case join<li>
							<li> then the type of join in an array</li>
						</ul>
						the match method handles the join predicate, it take a multi-dimensional array but if the column to match are the same it take a single array e.g
					<code>					
						match([["id", "user"]]) || match([["id", "!=", "user"]]) or match(["id"])
						<i>// and where, sort, pages method can also be attached to it</i>
					</code>
					<h3>Union</h3>
					The use method in this case takes a single argument which specify that we are using union!
					the get method also take multiple array according to the number of tables
					the where in this method takes a multi-dimentional array and d number of the table must correlate with the number of the array
					<code>
						$d->get(["name"], ['title'])
						->use("union")
						->where([], [["name", "tee"]])
						->res();

						
						<i>/*
						Note: in every case the where should be called for the union and if the query doesnt have a clause call the where method with empty arrays correlating with the number of table used
						*/
						</i>
						where([["name", "tlight"]], [["title", "post 1"]]);
					
					</code>
					<h3>Update</h3>
					The set method is use to update data and it acccepts the array of columns and array of values to set
					<code>
						set(['name', 'age'], ['tlight', 25]);
						<i>// you can call the where method to specify column</i>
						$d->set(['name', 'age'], ['tlight', 25])
						->where(10) <i>// this assumes id</i>
					</code>
					<h3>CustomQuery</h3>
					The customQuery accept 2 arguments the sql and the value, since we are using the pdo method for the db we need to change every assignment to
					<code>
						$d->customQuery("select * from profile where id = ? and name = ?", [2, "tlight"]);
					</code>
					<h3>Where</h3>
					Let's talk about the where method now 
					
					<code>
						where(2)
						<i>// this mean id = 2</i>
						where(['name', 'tlight'])
						<i>// this mean no assignment and the assignment fall backs to "=". so we have "name = tlight"</i>
						where(["name"])
						<i>// in this case it means you are to use this with sub method</i>
						where(['name', '=', 'tlight'])
						<i>// assignment given here so we have "name = tlight"</i>
						where([["name", "age"], "tlight"]);
						<i>// this is given in a situation where by we want to check a value against two column this is going to give us "(name = tlight or age = tlight)" by default we use 'or' to build the where but it can also be overwritten e.g</i>
						
						where([["name", "age"], ["and", "="], "tlight"])
						<i>//and the result is "(name = tlight and age = tlight)" the assignment operator provided is going to be used for both columns</i>
					</code>

					<h3>Subquery</h3>
					First we need to build a normal query by using the get method
					<code>
						$d->table("post")
						
						$data = $d->get(["title"])->where(["user"])
						<i>// the where should always look like this except if outer query</i>
					</code>
				</div>
				<div id="utlities">
					<h3>Table of content</h3>
					<div class="utlities-table">
						<a href="/docs#easy-class">The Easy class</a>
						<a href="/docs#auth-class">The Auth class</a>
						<a href="/docs#Validate-class">The Validate class</a>
						<a href="/docs#session-class">the Session class</a>
						<a href="/docs#utils-class">The Utilities class</a>
					</div>
					<div id="easy-class">
						<h1>The Easy class</h1>
						<i>Note this class extends class Db and all methods in this class have been validated since it works directly with user input</i>.

						Lets get started by initializing the class
						<code>$e = new Easy();</code>
						We need to set the table to work with!
						<code>$e->table("profile"); <i>// string< if only 1 table </i>
							$e->table(["profile", "post"]); <i>// for multiple tables</i></code>
						<h3>Create</h3>
						This method doesn't take any arguments.
						<code>$e->create();</code>
						<h3>Fetch</h3> 
						This method takes two argument
						<ul>
							<li><b>Columns to select</b> - Optional
							The default selection is to select all but this can be overridden by specifying the columns to select.
							</li>
							<li><b>Where clauses</b> - Optional
							There should be provided like in the db where method e.g ["user", "=", 10].
							And if where is more than 1, the default is to use "and" to join the clauses but it can also be overridden by add the the concat at the end of the clauses as a string e.g fetch(["*"], ["name", "=", "tlight"], ["id", "=", 2], "or");
							</li>
						</ul>
						<h3>Update</h3>
							This method only take the where clause
							<code>$e->update();
									<i>// or </i>
								$e->update(["id", "=", 1])
							</code>
						</p>
						<h3>Delete</h3>
						let say the url have
					
						** id=1&user=10
					
						breaking it to where id = 1 and user = 10;
						but the "and" can be overridden to have like eg
						<code><i>// no argument at all </i>
						
							$e->del();
							
							<i>// or</i>
							
							$e->del("or")
							<i>// other argument are optional</i>
							<i>// or </i>
							$e->del("or", ["id", "user"], ["<", ">"]);
						<i>/*
							in this case the column only means if the form/url names/keys doesnt match what is in the db 
							and operator means we can change the assignment to any operator we want
							*/</i>
						</code>
						<h3>With</h3>
						The with method is a blessing to this class, i can explain it only via code.
						This method takes 2 or 3 arguments with condition e.g
						<code><i>The first argument is a string defining why you want to use this method.
							The first argument option includes:
							pages, this makes the method to accept int value as the 2nd arg specifying how many per page, no default.</i>
							$e->fetch()->with("pages", 10);
							<i> // for fetch only
							append, in case you want to add to the incoming request e.g i use to turn title into url but i cant do that anymore since the request cant be edited, so the with method comes my aid.</i>
							$e->insert()->with("append", ["url"], [Utils::slug($_POST["title"])])->exec();
							<i> // this appended value is not validated by tlight, you have to do so yourself.
							// all methods can use it
							</i>
							<i>//remove, you also remove from the request object.</i>
							$e->(remove, ["id"])
							<i>// all methods
							// use, this option is only for update method, in case whereby the value to use for where clause is already appended to the form or url, just give the input/url name and thats it.</i>
							$e->update()->with("use", ["id"]);
							<i>change, to change the column in case the name/url doesnt match what you have in the db, provide all the col name according to how the input fields is listed.</i>
							$e->fetch()->with("change", [id, name]);
						</code>
						The with method can be called as many times as possible until it suites your needs.
						<h3>Exec</h3>
						This method execute all queries generated by insert, fetch, delete, update and takes option parameter true to return the first result.
				 	</div>
					<div id="auth-class">
						<h1>The Auth class</h1>
						<i>"I have build many login system but this, i am kinda proud of it!"</i>
						As part of the Auth class, there is an html file attached for login, register, lost password and change password all this forms are all in the auth/login.php file. And the <i>config.php</i> file has the the settings for login, it should be reviewed. The <i>login.php</i> can be included in a div tag or in a whole webpage the choice is yours. Ajax handles the submission and auth/request.php handles the request. assets/css/app.css for styles and assets/js/app.js for javascript.
						<code><i>// lets initialize our class</i>
						$a = new Auth();
						</code>
						<h3>Login</h3>
						Since protecting password is a big deal this days and changing password regularly is recommend by all security expert i implemented changing of password with the login method, so the <i>config.php</i> file under auth there is last_pc option default to 30 which means after 30days the user should change password. It can be set to 0 or false to disable password change option.
						
						Also its worth mentioning the login_attempts option can also be toggled, when login attempts reach the limit given the program shows a captcha for the user.
						<code>$a->login();
						<i>// or </i>
						$a->login(["random-email", "random-password"]);
						<i>// the column only if the input doesn't match the db column
							/*
							anytime using any method in this class its recommended to assign a variable to the method call!
							*/</i>
						</code>
						<h3>Reg</h3>
						<code>$reg = $e->reg(); 
							<i>// no argument or could be optional column</i>
							$reg = $a->reg(["some columns"])
							<i>// the column only if the input fields doesn't match the db column.</i>
						</code>
						<h3>Set</h3>
						The set method accepts a single optional value to set user session, default  to "user"
						The set method works only with login and reg
						It evaluates what the result of both method returns and calculate the last log indays and also set session
						It returns "ok" on success, "change" if the password change calculated is over the given value and also the variable assigned should be echoed for the error
						<code>$reg = $a->reg()->set();
							$log = $a->login()->set();
							<i>// in the wake one may like to use $result instead of set() method to return the result of the login or reg method.</i>
							$log = $a->login()->result;
						</code>				
						<h3>Change password</h3>
						This method is called to change or update password!
						// takes single optional value to get user session key default  to "user"
						<code>$ch = $a->chpwd();
						</code>
						<h3>Lost password</h3>
						Still working on this method, as it requires sending email not until i tested it i won't recommend using it.
						<code>$e->lpass();
						</code>
					</div>
					<div id="Validate-class">
						<h1>The validate class</h1>
						The validate class handles everything that has to do validation as the the name suggest. It also handles file uploads, generating and validating of the csrf token for forms.
						<h3>Validator method</h3>
						This method takes 2 arguments
						<ul>
							<li>The request object</li>
							<li>The set of rules for each request</li>
						</ul>
						<code><i>// lets intialize our class</i>
							$v = new Validate();
							<i>// now the validator method</i>
							$v->validator($_POST, [
								"name" => [
									"required" => true,
									"min" => 8,
									"max" => 100
								],
								"id" => [
									"required" => true,
									"number" => true
								]
							]);

							<i>/*
								Assuming that we have an incoming post request containing
								Array(name => tlight, id => 2)
								The rules given above will be applied and and if the request fails to meet up with rules then errors will be given.
								And the error can be collected via $v->error();
								*/
							</i>
						</code>
						The set of rules the validator takes is as follow:

						<ul>
							<li>Required
								This means the field given must not be empty.
							</li>
							<li>Csrf
								Validate the csrf included in the form
							</li>
							<li>Email
								Check if the input value is an email
							</li>
							<li>Match
								In case you want to check for password match i.e change password form
								this rule takes the other input name holding the input values e.g
								password => [
									"match" => v_password
								]
							</li>
							<li>Max
								This checks if the input is string and count every character it contains and if exceed the given rule, then error is emitted.
								And if the value is numeric and is greater than the give rule, error is emitted also.
							</li>
							<li>Min
								This checks if the input is string and count every character it contains and if less than the given rule, then error is emitted.
								And if the value is numeric and is less than the give rule, error is emitted also.
							</li>
							<li>Number
								This checks if the input value is number
							</li>
							<li>Wordcount
								count the words in an input and throw error if the result is less than the given rule
							</li>
							<li>Multiple
								This is useful in a situation whereby we have an input with name="file[]".
								The multiple rules checks if any of the input field is not empty!
							</li>
							<li>Unique
								This set of rules connect with the database and check if the value is available in the database, throws error is true.
							</li>
						</ul>
						<h3>The req method</h3>
						This method gives you whatever is coming in from the request may it GET or POST
						<h3>The val_req</h3>
						The val_req() method is similar to the to validator except that it helps remove the csrf and return to the request data.
						It takes optional arguments but we can also pass in to it our own set of rules for each request value
						<code><i>// request data Array(id => 10, name => tlight)</i>
							$v->val_req(["number", true, "required" => true], ["required" => true]);
						</code>
						The moment you passed in a rule for an input value the rest is required.
						<h3>The Uploader</h3>
						This methods handles file uploads and also validate and check if the file is actually a media file. The following is file type accepted by this method.
						<code>['image/pjpeg', 'image/jpeg', 'image/gif', 'image/bmp', 'image/png', 'video/mpeg', 'video/mp4', 'video/quicktime', 'video/mpg', 'video/x-msvideo', 'video/x-ms-wmv', 'video/3ggp', 'audio/mid', 'audio/mp4', 'audio/mp3', 'audio/ogg', 'audio/wav', 'audio/3gpp', 'audio/mpeg']
						</code>
						And it accepts by default the max of 5 files, this can be changes in the <i>config.php</i> file.
						You can check if there is any error in the upload process with the class pass() method. And if successful the file path is save in session name file and can be access by calling the session class Session::get("file");
						Alternatively, one can call the validate class complete_upload method to save the file, this method takes where to save the file as argument. And returns the full path to the file in case you want to save it in mysql database.
						<h3>Other useful methods</h3>
						<ul>
							<li>$v->error()
							Returns the error in validation process
							</li>
							<li>$v->pass()
								This check if there is error or not in the validation process
							</li>
							<li>$v->fetch()
								This fetch and validate any given value from the request e.g 
								<code>
									<i>// The request array(id => 2);</i>
									$v->fetch("id");
									<i> // this returns 2 </i>
								</code>
							</li>
							<li>$v->csrf()
							This is used to protect forms and to make sure that the request is from within the website.
							</li>
						</ul>
					</div>
					<div id="session-class">
						<h1>The Session class</h1>
						The session class contains static methods and can call with Sessio::get();
						<u>To get session</u>
						<code><i>// get all session</i>
							Session::get();
							<i>// get specific session</i>
							Session::get("user");
							<i>// returns the user session id</i>
						</code>
						<u>Setting session</u>
						<code>Session::set("user", 2);
						</code>
						<u>Check if session exists</u>
						<code><i>// all session</i>
							Session::check();
							<i>// specific session</i>
							Session::check("user")
							<i>// true on success false on failure</i>
						</code>
						<u>Deleting session</u>
						<code><i>// all session</i>
							Session::del();
							<i>// specific session</i>
							Session::del("user")
							<i>// no return value</i>
						</code>
					</div>
					<div id="utils-class">
						<h1>The Utils class</h1>
						This also contains static methods
						<u>Time</u>
						<code><i>// time</i>
							Utils::time();
							<i>// good for blog time</i>
							<i>time_to_ago</i>
							Utils::time_to_ago($time, $check = false);
							<i>
								/*
								The 2nd argument is optional in case you are using mysql default datetime the 2nd argument should be true
								*/
							</i>
						</code>
						<u>Slug</u>
						This replace all the spaces with "-" good for removing spaces from url and seo.
						<code>Utils::slug("hello world");
							<i>// returns hello-world</i>
						</code>
						<u>Wordcount</u>
						This method returns by default 10 words from a give string;
						<code><i>// $str = Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua.</i>
							Utils::wordcount($str);
							<i>// returns Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do...</i>
						</code>
						<u>arr_flat</u>
						This method takes 2 argument
						<ul>
							<li>The array</li>
							<li>what you want
								The values is default
								or the keys or both the value and the key
							</li>
						</ul>
						<u>json</u>
						This method changes array to json
						<u>djson</u>
						This method changes json to array and only array and not object. the normal behaviour of php json_decode();
						<u>get_type</u>
						This method takes a file path for argument and checks if the file is audio/video/image and returns the result.
						<u>media_html</u>
						Just like the get_type this collect a file full path and check if the file is audio/video/image and returns the html tags corresponding to it.
						<u>age</u>
						This method calculate age, it takes date as argument.
						<u>copy_r</u>
						This method copy files recursively even if the destination folder is not created!
				</div>
			</section>
here;
			echo nl2br($doc);
			?>
	</article>
</div>
	<style type="text/css">
		code {
			display: block;
			border: 1px inset var(--sec);
			color: #a80a0a;
			padding: 4px;
			overflow-x: auto;
		}

		code i {
			color: var(--sec);
			font-size: 16px;
		}

		u {
			display: block;
		}

		a {
			text-decoration: none;
			color: var(--link);
		}

		li b {
			background: var(--pry);
			color: var(--sec);
			padding: 5px;
			border-radius: 5px;
		}

		h1, h2, h3, h4 {
			margin: 0;
			color: var(--text);
		}

		ul {
			margin: 0;
		}

		body {
			color: var(--sec);
			font-size: 1.3rem !important;
			font-family: serif;
		}

		a {
			text-decoration: none;
			color: var(--link);
		}

		article {
			line-height: 1.5;
			margin: 0 1rem;
		}

		p {
			padding: 10px;
		}

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