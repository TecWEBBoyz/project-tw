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
        "baseURL" => "/{USERNAME}",    // Base URL for the application (Nome utente su server, / in locale)
        "app" => [
            "debug" => false
        ]
    ],
    "database" => [
        "host" => "localhost",
        "username" => "{USERNAME}",
        "password" => "{DB_PASSWORD}",
        "database" => "{USERNAME}",
    ],
    "url" => "http://localhost/{USERNAME}",
];