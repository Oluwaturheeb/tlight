<?php 
$GLOBALS['config'] = [
    "project" => [
        "name" => "tlight",
        "lang" => "en",
        "region" => "Africa/Lagos"
    ],
    "db" => [
        "host" => "localhost",
        "database" => "tlight",
        "usr" => "root",
        "pwd" => ""
    ],
    "session" => [
        "name" => "Tlight_sessions", 
        // as for me i use apache virtual host you change this to localhost
        "domain" => "tlight.com"
    ],
    "auth" => [
        "single" => true,
        "login_attempts" => 3
    ],
    "state" => [
    	"development" => true
    ],
    "file-upload" => [
        "max-file-upload" => 5,
        "rename-file" => false
    ]
];