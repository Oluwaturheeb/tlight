<?php 
$GLOBALS['config'] = [
    "project" => [
        "name" => "Tlight",
        "lang" => "en",
        "region" => "Africa/Lagos"
    ],
    "db" => [
        "host" => "localhost",
        "database" => "mobile",
        "usr" => "root",
        "pwd" => ""
    ],
    "session" => [
        "name" => "Tlight_sessions", 
        "domain" => "tlight.com"
    ],
    "auth" => [
        "single" => true,
        "login_attempts" => 3,
        "last_pc" => 30
    ],
    "state" => [
    	"development" => true
    ],
    "blog" => [
		"personal" => true,
		"guest_post" => false,
		"comment" => false,
		"rating" => false,
	]
];