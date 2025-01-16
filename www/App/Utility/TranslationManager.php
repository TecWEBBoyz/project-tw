<?php
namespace PTW\Utility;
use function PTW\dd;

class TranslationManager
{
    private $translations = [];
    private $defaultLanguage;
    private static $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     *
     * @param string $defaultLanguage The default language to use.
     */
    private function __construct($defaultLanguage = 'en')
    {
        $this->defaultLanguage = $this->detectBrowserLanguage($defaultLanguage);
    }

    /**
     * Get the singleton instance of the TranslationManager.
     *
     * @param string $defaultLanguage The default language to use if instance is not created yet.
     * @return TranslationManager The singleton instance.
     */
    public static function getInstance($defaultLanguage = 'en')
    {
        if (self::$instance === null) {
            self::$instance = new self($defaultLanguage);
        }

        return self::$instance;
    }

    /**
     * Prevent cloning of the instance.
     */
    public function __clone() {}

    /**
     * Prevent unserializing of the instance.
     */
    public function __wakeup() {}

    /**
     * Load translations from a JSON file.
     *
     * @param string $filePath The path to the JSON file containing translations.
     * @param string $language The language code for the translations.
     * @return bool True if the file was loaded successfully, false otherwise.
     */
    public function loadTranslations($filePath, $language)
    {
        $filePath = __DIR__ ."/../..". $filePath;
        if (!file_exists($filePath)) {
            return false;
        }

        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            return false;
        }
        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        if (!isset($this->translations[$language])) {
            $this->translations[$language] = [];
        }

        $this->translations[$language] = array_merge($this->translations[$language], $data);

        return true;
    }

    /**
     * Get a translation for a given key.
     *
     * @param string $key The translation key.
     * @param string|null $language The language code. If null, the default language will be used.
     * @return string The translation text or a fallback message if not found.
     */
    public function translate($key, $language = null)
    {
        $language = $language ?? $this->defaultLanguage;
        if (isset($this->translations[$language][$key])) {
            return $this->translations[$language][$key];
        }

        return "Translation not present";
    }

    /**
     * Set the default language.
     *
     * @param string $language The language code to set as default.
     */
    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;
        setcookie('user_language', $language, 0, "/"); // Save the language in a cookie with no expiration
    }

    /**
     * Get the current default language.
     *
     * @return string The current default language code.
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * Detect the browser language and set it as the default if not already set.
     *
     * @param string $fallbackLanguage The fallback language to use if detection fails.
     * @return string The detected or fallback language.
     */
    private function detectBrowserLanguage($fallbackLanguage)
    {
        if (isset($_COOKIE['user_language'])) {
            return $_COOKIE['user_language'];
        }

        $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        setcookie('user_language', $browserLanguage, 0, "/"); // Save the detected language in a cookie with no expiration

        return $browserLanguage ?: $fallbackLanguage;
    }
}
