<?php
require_once "Autoload.php";
$a = new Auth();

switch (Http::req("type")) {
	case "Login":
		$res = $a->login(['required' => true, 'email' => true, 'wordcount' => 1, 'min' => 7], ['required' => true, 'wordcount' => 1, 'min' => 8])->set();
		
		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		Http::res($res);
		break;
	case "Register":
		$res = $a->reg(['required' => true, 'email' => true, 'wordcount' => 1, 'min' => 8, 'unique' => 'auth'], ['required' => true, 'wordcount' => 1, 'min' => 8])->set();

		//enter where to redirect to after successful login
		$res["redirect"] = "/";
		Http::res($res);
		break;
	case "Chpwd":
		Http::res($a->chpwd(['required' => true, 'match' => 'verify', 'min' => 8], ['required' => true, 'min' => 8]));
		break;
	case "Lpwd":
		// return email if found else returns error;
		Http::res($a->lpass());
		break;
}