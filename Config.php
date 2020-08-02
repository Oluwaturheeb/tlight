<?php 
$GLOBALS['config'] = [
    "project" => [
        "name" => "tlight",
        "lang" => "en",
        "region" => "Africa/Lagos"
    ],
    "db" => [
        "host" => "localhost",
        "database" => "tlight_app",
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
    "file-upload" => [
        "max-file-upload" => 5,
        "rename-file" => false,
        "mime" => []
    ]
];