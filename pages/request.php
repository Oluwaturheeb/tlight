<?php
require_once "../pages/Autoload.php";
$a = new Auth();


function response ($data = "ok") {
	if (is_array($data)) {
		echo json_encode($data);
	} else {
		echo Utils::json(["msg" => $data]);
	}
}

switch ($a->req("type")) {
	case "login":
		$res = $a->login()->set();
		
		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		response($res);
		break;
	case "register":
		$res = $a->reg(["email", "password"])->set();

		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		response($res);
		break;
	case "chpwd":
		response($a->chpwd());
		break;
	case "lpwd":
		response($a->lpass());
		break;
}