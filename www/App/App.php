<?php

/**
 * This file rappresents the Application. Eneable the user to interact with every feature of the application.
 * 
 * Todo: 
 * - Turn into Singleton.
 */

namespace PTW;

use Exception;

class App
{
    private static array $config = [];

    public static function Init(): void
    {
        self::LoadConfig();
    }

    public static function Destroy(): void
    {
    }

    public static function GetConfig(): array
    {
        return self::$config;
    }

    public static function HandleRoute(): void
    {
        $router = require_once __DIR__ . "/routes.php";

        $uri = parse_url($_SERVER["REQUEST_URI"])['path'];
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';

        echo $router->route($uri, $method);
    }

    private static function LoadConfig(): void
    {
        try {
            if (!file_exists(__DIR__ . "/../Config/config.php"))
                throw new Exception('config.php is missing');
            else
                self::$config = require_once(__DIR__ . "/../Config/config.php");
        }
        catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}