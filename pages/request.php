<?php
require_once "Autoload.php";
/*$v = new Validate();

$v->filter_str($v->req('email'));
*/
/*$v->validator($v->req(), [
'email' => ['required' => true,'email' => true, 'min' => 8, 'unique' => 'auth'],
'password' => ['required' => true, 'min' => 8]
]);*/


$a = new Auth();

switch (Http::req("type")) {
	case "Login":
		$res = $a->login()->set();
		
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
		Http::res($a->chpwd());
		break;
	case "Lpwd":
		// return email if found else returns error;
		Http::res($a->lpass());
		break;
}