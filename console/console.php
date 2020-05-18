<?php

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