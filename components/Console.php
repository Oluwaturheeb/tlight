<?php
require_once 'app.php';
require_once 'cmd.php';

if (count($argv) > 1)
	if(strpos($argv[1], '-') === false) {
			$res = $cmd_icon. $def;
			$arg = $argv[1];
			if ($arg == 'template') {
				// template
				$c = Utils::r_copy('components/template/', './pages');
				$res = '**Success: Template created successfully!';
			} elseif ($arg == 'backup') {
				// creating backup of db
				$u = config('db/usr');
				$p = config('db/pwd');
				$db = config('db/database');
				
				if ($p)
					exec(escapeshellcmd("mysqldump -u {$u} -p {$p} {$db} > {$db}.sql"));
				else
					exec(escapeshellcmd("mysqldump -u {$u} {$db}"));
					///exec();
				$res = '**Success: Backup completed!';
			} elseif ($arg == 'import') {
				// import the db onced backed up
				$u = config('db/usr');
				$p = config('db/pwd');
				$db = config('db/database');
				
				if ($p)
					exec(escapeshellcmd("mysql -u {$u} -p {$p} {$db} < {$db}.sql"));
				else
					exec(escapeshellcmd("mysql -u {$u} {$db} < {$db}.sql"));
				$res = '**Success: Backup completed!';
			} elseif ($arg == 'header') {
				$header = @$argv[2];

				if (!$header) {
					$res = $cmd_icon.$def;
				} else {
					if (copy(@'pages/inc/headers/'. $header .'.php', 'pages/inc/defaultHeader.php')) {
						$text = '
						require_once \'defaultHeader.php\';';
						file_put_contents('pages/inc/header.php', $text, FILE_APPEND);
						$res = '**Success: Default header is set!';
					} else {
						$res = '**Error: Unknown error!';
					}
				}
			} else if ($arg == 'clearcache') {
				// clear cache
				Caching::clearCache();
				$res = '**Success: Cache cleared successfully!';
				
			}
			echo $res, "\n";
		} else {
			// this for initialition, version and help via cli
			$arg = getopt('ivh');
			$res = $cmd_icon . $def;
	
			foreach ($arg as $key => $value) {
				switch ($key) {
					case 'i':
						if ((new Config())->init())
							$res = '**Error: Are you sure mysql is running and that you have mysql in your exec?';
						else
							$res = '**Success: Setup completed!';
						break;
					case 'v':
						$res = 'Tlight v1.8.0';
						break;
					case 'h':
						$res = $cmd_icon . $def;
						break;
					default:
						$res = $cmd_icon . $def;
						break;
				}
			}
			echo $res, "\n";
		}
	else
	 echo $cmd_icon . $def;