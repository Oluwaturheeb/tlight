<?php
require_once "Autoload.php";
$GLOBALS["logo"] = <<<__here
___________

   light
___________
    | |
    | |
    | |___
    |_____|

Tlight V1.1.0

__here;

$def = <<<__here
To start a project kindly review the settings for this project in Config.php in the root directory
Available commands:

rel \t->\t setup relations between 2 table syntax `rel parent-table/child-table parent-column/child-column delete-[restrict, null, cascade]/update-[restrict, null, cascade] `e.g rel blogs/comment id/blog_id delete-null/update-restrict´\n
template \t->\t this create a template folder with useful crud files\n
-i \t->\t this setup the database and default tables
-h \t->\t show this help
-v \t->\t show version

__here;

if($_SERVER["DOCUMENT_ROOT"]) {
	// Running setup via the browser
	if ($c->init()) {
		echo "**Success: Setup completed!";
	} else {
		echo nl2br($def);
	}
} else {
	// Running setup via cli
	require_once "console/console.php";
}