<?php

/**
 * Rappresentation og a controller in the application.
 */

namespace PTW\Contracts;

use PTW\Modules\Auth\SessionManager;
use function PTW\config;

abstract class ControllerContract
{
    public SessionManager $sessionManager;
    public string $baseURL = "";

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
        $this->baseURL = config("router.baseURL");
    }
    public function locationReplace(string $url): void
    {
        header("Location: {$this->baseURL}{$url}");
    }
    public function previusPage()
    {
        $previusPage = $_SERVER['HTTP_REFERER'] ?? $this->baseURL;
        header("Location: $previusPage");
    }

    public function InPreviusPageFrom($isFrom)
    {
        $previusPage = $_SERVER['HTTP_REFERER'] ?? $this->baseURL;
        if (strpos($previusPage, $isFrom) !== false) {
            return true;
        }
        return false;
    }
    /**
     * Response to a GET request.
     *
     * @return void
     */
    public abstract function get(): void;

    /**
     * Response to a POST request.
     * @return void
     */
    public abstract function post(): void;

    /**
     * Response to a PUT request.
     * @return void
     */
    public abstract function put(): void;

    /**
     * Response to a DELETE request.
     * @return void
     */
    public abstract function delete(): void;
}