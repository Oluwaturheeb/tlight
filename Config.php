<?php 
$GLOBALS['config'] = [
    "project" => [
        "name" => "Tlight",
        "lang" => "en",
        "region" => "Africa/Lagos"
    ],
    "db" => [
        "host" => "localhost",
        "database" => "dvs",
        "usr" => "root",
        "pwd" => ""
    ],
    "session" => [
        "name" => "Tlight_sessions", 
        // as for me i use apache virtual host you change this to localhost
        "domain" => "tlight.com"
    ],
    "auth" => [
        "single" => false,
        "login_attempts" => 3
    ],
    "state" => [
    	"development" => true
    ],
    "file-upload" => [
        "max-file-upload" => 5
    ]
];