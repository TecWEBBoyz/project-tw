<?php

/**
 * This file rappresents the Application. Eneable the user to interact with every feature of the application.
 * 
 * Todo: 
 * - Turn into Singleton.
 */

namespace PTW;

use Exception;
use PTW\Modules\Database\DB;
use PTW\Utility\TranslationManager;

class App
{
    private static array $config = [];
    private static DB $database;

    private static TranslationManager $translationManager;

    public static function Init(): void
    {
        self::InitConfig();
        self::InitDatabase();
        self::InitRepository();
        self::InitTranslationManager();
    }

    public static function Destroy(): void
    {
    }

    public static function GetConfig(): array
    {
        return self::$config;
    }

    public static function GetDatabase(): DB
    {
        return self::$database;
    }

    public static function GetTranslationManager(): TranslationManager
    {
        return self::$translationManager;
    }

    public static function HandleRoute(): void
    {
        $router = require_once __DIR__ . "/routes.php";

        $uri = parse_url($_SERVER["REQUEST_URI"])['path'];
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';

        echo $router->route($uri, $method);
    }

    private static function InitConfig(): void
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

    private static function InitDatabase(): void
    {
        self::$database = new DB;
    }

    private static function InitRepository(): void
    {
    }

    private static function InitTranslationManager(): void
    {
        self::$translationManager = TranslationManager::getInstance('it');
        self::$translationManager->setDefaultLanguage('it');

        $loaded_en = self::$translationManager->loadTranslations('/translation/translations_en.json', 'en');
        $loaded_it = self::$translationManager->loadTranslations('/translation/translations_it.json', 'it');
    }
}