<?php
require_once "../Autoload.php";
$a = new Auth();

switch ($a->req()["type"]) {
	case "login":
		echo $a->login()->set();
		break;
	case "register":
		echo $a->reg(["email", "password"])->set();
		break;
	case "chpwd":
		echo $a->chpwd();
		break;
	case "lpwd":
		echo $a->lpass();
		break;
}