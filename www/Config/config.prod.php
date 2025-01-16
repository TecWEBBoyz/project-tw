<?php

/**
 * Basic template configuration file.
 * 
 * Create a new config.php file in the same directory and copy the contents of this file into it.
 * Replace the values as needed.
 */

/**
 * Template Configuration Array
 */
return [
    "router" => [
        "baseURL" => "",    // Base URL for the application (Nome utente su server, / in locale)
        "app" => [
            "debug" => false   // Debug mode
        ]
    ],
    "database" => [
        "host" => "localhost",
        "username" => "default_user",
        "password" => "default_password",
        "database" => "default_database",
    ]
];