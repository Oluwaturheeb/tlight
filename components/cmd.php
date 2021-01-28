<?php
$cmd_icon = <<<__here

         ´     `
       ` (     ) ´
     ` (         ) ´
    ` (           ) ´
    ` (   _____   ) ´
     ` (  _____  ) ´
      ` (  | |  ) ´
       ` ( | | ) ´
         \     /
          |___|
          |___|

Tlight V 1.8.0

__here;

$def = <<<__here
To start a project kindly review the settings for this project in config.php in the root directory
Available commands:

backup
    this command create a backup of the database of the app\n
clearcache
    this command clears the application cache
header
    this set the default header file. Takes file name as argument without the .php extension\n
import
    this import the previously backed up database file into the app db\n
template
    this create useful crud files\n
-i    this setup the database and default auth table
-h    show this help
-v    show version

__here;