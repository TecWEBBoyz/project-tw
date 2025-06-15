<?php

namespace PTW;

use Exception;

if (!function_exists("config")) {

    /**
     * Return the required configuration. Accept dot notation to navigate config layer.
     *
     * @param string $key
     * @return array|string|null|bool
     */
    function config(string $key): array|string|null|bool
    {
        $config = [];

        try {
            $config = require(__DIR__ . "/../Config/Config.php");
        }
        catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        if (!strpos($key, '.')) {
            return $config[$key];
        }

        // Dot Notation
        $configsKeys = explode(".", $key);

        foreach ($configsKeys as $configKey) {
            if (isset($config[$configKey]))
                $config = &$config[$configKey];
            else
                return '';
        }

        return $config;
    }

}

if (!function_exists('PTW\abort')) {
    function abort(int $code): void
    {
        http_response_code($code);

        $errorPagePath = __DIR__ . "/../{$code}.php";

        if (file_exists($errorPagePath)) {
            require $errorPagePath;
        } else {
            echo "<h1>Error {$code}</h1>";
        }

        exit();
    }
}
