<?php
require_once 'components/setting.php';

if (count($argv) > 1)
	if(strpos($argv[1], "-") === false) {
			$res = $GLOBALS["logo"] . $def;
			if ($argv[1] == "template") {
				$c = Utils::copy_r("components/template/", "./pages");
				$res = "**Success: Template created successfully!";
			} elseif ($argv[1] == "backup") {
				$u = $c->get('db/usr');$p = $c->get('db/pwd');$db = $c->get('db/database');
				
				if ($p)
					exec("mysqldump -u $u -p '$p $db' > $db.sql");
				else
					exec("mysqldump -u $u $db > $db.sql");
				$res = "**Success: Backup completed!";
			} elseif ($argv[1] == "import") {
				$u = $c->get('db/usr');$p = $c->get('db/pwd');$db = $c->get('db/database');
				
				if ($p)
					exec("mysql -u $u -p '$p $db' < $db.sql");
				else
					exec("mysql -u $u $db < $db.sql");
				$res = "**Success: Backup completed!";
			} elseif ($argv[1] == "header") {
				$header = @$argv[2];

				if (!$header) {
					$res = $def;
				} else {
					if (@copy("pages/inc/headers/{$header}.php", "pages/inc/defaultHeader.php")) {
						$res = "**Success: Default header is set!";
					} else {
						$res = "**Error: Unknown error!";
					}
				}
			}
			echo $res, "\n";
		} else {
			// this for initialition, version and help via cli
			$arg = getopt("ivh");
			$res = $GLOBALS["logo"] . $def;
	
			foreach ($arg as $key => $value) {
				switch ($key) {
					case "i":
						if (!$c->init())
							$res = "**Error: Are you sure mysql is running and that you have made changes to the config.php file?!";
						else
							$res = "**Success: Setup completed!";
						break;
					case "v":
						$res = "Tlight v1.8.0";
						break;
					case "h":
						$res = $GLOBALS["logo"] . $def;
						break;
					default:
						$res = $GLOBALS["logo"] . $def;
						break;
				}
			}
			echo $res, "\n";
		}
	else
	 echo $GLOBALS["logo"] . $def;