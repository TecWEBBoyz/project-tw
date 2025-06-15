<?php

/**
 * Template Configuration Array
 */
return [
    "database" => [
        "host" => "localhost",      // Il nome del container definito in docker-compose.yml come hostname
        "username" => "default_user",
        "password" => "default_password",
        "database" => "default_database", // Definito in setup_apache.sh
    ],
    "JWT" => [
        'JWT_SECRET_KEY' => 'hfwuerkao&w09q3/%$ur4(32wdxò[e',
        'JWT_EXPIRATION' => 3600,
    ]
];