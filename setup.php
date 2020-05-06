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
To start a project kindly review the settings for this project in /class/Config.php

Available commands:

rel \t->\t setup relations between 2 table syntax `rel parent-table/child-table parent-column/child-column delete-[restrict, null, cascade]/update-[restrict, null, cascade]` eg rel blogs/comment id/blog_id delete-null/update-restrict
-i \t->\t this setup the database and default tables
-h \t->\t show this helpzz
-v \t->\t show version

__here;

function init() {
	$c = new Config();

	if ($c->get('db/database') != "tlight") {
		$d = new mysqli(config::get("db/host"), Config::get("db/usr"), Config::get("db/pwd"));

		if ($d->query("create database if not exists {$c->get('db/database')}") === true) {
			$d->select_db($c->get('db/database'));

			$auth = "create table if not exists auth(id int auto_increment, email varchar(150) not null, password varchar(64) not null, last_log datetime, last_pc datetime default now()";
			
			$rel = "create table if not exists relation(id int auto_increment, tab varchar(100) not null, rel varchar(200) not null, primary key(id))";

			if (!$c->get("auth/single")) {
				$auth .= ", type varchar(50) null";
			}

			$auth .= ", primary key(id), unique(email));";
			$d->multi_query($auth . $rel);
			
			if (!$d->error)
				return "Setup completed!";
		}
	}

	return false;
}

if($_SERVER["DOCUMENT_ROOT"]) {
	if (init()) {
		echo nl2br(init());
	} else {
		echo nl2br($def);
	}
} else {
	if(strpos($argv[1], "-") === false) {
		if ($argv[1] == "rel") {
			$argv = array_slice($argv, 2);
			if (count($argv)) {
				$tab = explode("/", $argv[0]);
				$link = explode("/", $argv[1]);

				if (count($argv) > 2)
					$opt = explode("/", $argv[2]);
				else 
					$opt = [];

				$r = new Rel();

				$res = $r->set($tab, $link, $opt); 
			} else {
				$res = $GLOBALS["logo"] . $def;
			}
		}
		echo $res;
	} else {
		$arg = getopt("ivh");
		$res = $GLOBALS["logo"] . $def;

		foreach ($arg as $key => $value) {
			switch ($key) {
				case "i":
					if (!init())
						$res = $GLOBALS["logo"] . $def;
					else
						$res = init();
					break;
				case "v":
					$res = "Tlight v1.1.0";
					break;
				case "h":
					$res = $GLOBALS["logo"] . $def;
					break;
				default:
					$res = $GLOBALS["logo"] . $def;
					break;
			}
		}
		echo $res . "\n";
	}
}