<?php

/**

Author -> Muhammad-Turyeeb Ibn Bello
Date -> 12th of May 2020 1:10 am

Config file for blog maker

*/


$GLOBAL["config"] = [
	"type" => [
		"personal" => true,
		"guest_post" => false,
	],
	"project" => [
        "name" => "Tlight",
        "lang" => "en",
        "region" => "Africa/Lagos"
    ],
    "session" => [
        "name" => "Tlight_sessions", 
        "domain" => "tlight.com"
    ],
    "state" => [
    	"development" => true
    ]
];