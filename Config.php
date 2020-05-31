<?php 
$GLOBALS['config'] = [
    "project" => [
        "name" => "Tlight",
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
        "single" => false,
        "login_attempts" => 3,
        // use 0 to disable last password change
        "last_pc" => 30
    ],
    "state" => [
    	"development" => true
    ],
    "file-upload" => [
        "max-file-upload" => 5
    ]
];