<?php
require_once "../pages/Autoload.php";
$a = new Auth();

switch ($a->req()["type"]) {
	case "login":
		$res = $a->login()->set();
		
		//enter where to redirect to after successful login
		// in my case i use number to depict account type
		// but you can always change it to whatever d app is returning
		if (@$res["type"] < 3) {
			// change where to redirect to
			$res["type"] = "/admin";
		} else if (@$res["type"] == 3) {
			$res["type"] = "/dashboard";
		} else {
			// this can be used to redirect single login
			$res["type"] = "home";
		}
		
		echo json_encode($res);
		break;
	case "register":
		$res = $a->reg(["email", "password"])->set();
		echo json_encode($res);
		break;
	case "chpwd":
		echo json_encode($a->chpwd());
		break;
	case "lpwd":
		echo json_encode($a->lpass());
		break;
}