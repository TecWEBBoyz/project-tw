<?php

/**
 * Basic template configuration file.
 *
 * Create a new config.local.php file in the same directory and copy the contents of this file into it.
 * Replace the values as needed.
 */

/**
 * Template Configuration Array
 */
return [
    "database" => [
        "host" => "localhost",
        "username" => "default_user",
        "password" => "default_password",
        "database" => "default_database",
    ],
    "JWT" => [
        'JWT_SECRET_KEY' => '{JWT_SECRET_KEY}',"",
        'JWT_EXPIRATION' => 3600,
    ]
];