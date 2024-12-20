<?php

/**
 * This file contains all the helper functions that are used throughout the application
 */

namespace PTW;

use PTW\App;

if (!function_exists("config")) {

    /**
     * Return the required configuration. Accept dot notation to navigate config layer.
     * 
     * @param string $key
     * @return array|string|null
     */
    function config(string $key): array|string|null
    {
        if (!strpos($key, '.')) {
            return App::getConfig()[$key];
        }

        // Dot Notation
        $configs = explode(".", $key);
        $subConfig = App::getConfig();
        foreach ($configs as $config) {
            if (isset($subConfig[$config]))
                $subConfig = &$subConfig[$config];
            else
                return '';
        }

        return $subConfig;
    }

}

if (!function_exists("dd")) {

    /**
     * Print the data in a readable way and die.
     * @param mixed $data
     * @return void
     */
    function dd(mixed $data): void
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die();
    }

}