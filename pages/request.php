<?php
require_once "../pages/Autoload.php";
$a = new Auth();

switch (Http::req("type")) {
	case "login":
		$res = $a->login()->set();
		
		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		Http::res($res);
		break;
	case "register":
		$res = $a->reg(["email", "password"])->set();

		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		Http::res($res);
		break;
	case "chpwd":
		Http::res($a->chpwd());
		break;
	case "lpwd":
		// return email if found else returns error;
		Http::res($a->lpass());
		break;
}