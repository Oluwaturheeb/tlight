<?php
require_once 'Autoload.php';
$v = new Validate();
$a = new Auth();

switch (req('type')) {
	case 'login':
		$res = $a->login(['required' => true, 'email' => true, 'wordcount' => 1, 'min' => 7], ['required' => true, 'wordcount' => 1, 'min' => 8])->set();
		
		//enter where to redirect to after successful login
		$res['redirect'] = '/';
		res($res);
		break;
	case 'register':
		$res = $a->reg(['required' => true, 'email' => true, 'wordcount' => 1, 'min' => 8, 'unique' => 'auth'], ['required' => true, 'wordcount' => 1, 'min' => 8])->set();

		//enter where to redirect to after successful login
		$res['redirect'] = '/';
		Http::res($res);
		break;
	case 'chpwd':
		Http::res($a->chpwd(['required' => true, 'match' => 'verify', 'min' => 8], ['required' => true, 'min' => 8]));
		break;
	case 'lpwd':
		// return email if found else returns error;
		Http::res($a->lpass());
		break;
}