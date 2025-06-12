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
        "username" => "{USERNAME}",
        "password" => "{DB_PASSWORD}",
        "database" => "{USERNAME}",
    ],
    "JWT" => [
        'JWT_SECRET_KEY' => '{JWT_SECRET_KEY}',"",
        'JWT_EXPIRATION' => 3600,
    ]
];