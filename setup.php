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
	if (count($argv) > 1)
		if(strpos($argv[1], "-") === false) {
			// settin relations
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
				// setting template
			} elseif ($argv[1] == "template") {
				$c = Utils::copy_r("class/template/", "./pages");
				$res = "**Success: Template created successfully!";
			}
			echo $res, "\n\n";
		} else {
			// this for initialition, version and help via cli
			$arg = getopt("ivh");
			$res = $GLOBALS["logo"] . $def;
	
			foreach ($arg as $key => $value) {
				switch ($key) {
					case "i":
						if (!$c->init())
							$res = "**Error: Are you sure mysql is running?!";
						else
							$res = "**Success: Setup completed!";
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
			echo $res, "\n\n";
		}
	else
	 echo $GLOBALS["logo"] . $def;
}