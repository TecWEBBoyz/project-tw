<?php

/**
 * This is the Router module.
 */

namespace PTW\Modules\Router;

use function PTW\config;
use Closure;

class Router
{
    private array $routes = [];
    private string $baseURI;

    public function __construct()
    {
        $this->baseURI = config("router.baseURI");
    }

    public function get(string $uri, string|Closure $controller): void
    {
        $this->routes[] = [
            'uri' => $this->baseURI . $uri,
            'controller' => $controller,
            'method' => 'GET'
        ];
    }

    public function post(string $uri, string|Closure $controller): void
    {
        $this->routes[] = [
            'uri' => $this->baseURI . $uri,
            'controller' => $controller,
            'method' => 'POST'
        ];
    }

    public function delete(string $uri, string|Closure $controller)
    {
        $this->routes[] = [
            'uri' => $this->baseURI . $uri,
            'controller' => $controller,
            'method' => 'DELETE'
        ];
    }

    public function put(string $uri, string|Closure $controller): void
    {
        $this->routes[] = [
            'uri' => $this->baseURI . $uri,
            'controller' => $controller,
            'method' => 'PUT'
        ];
    }


    /**
     * Redirect the request to the right controller.
     * 
     * If the route is not found, the method will return a 404 error.
     * 
     * @param string $uri
     * @param string $method
     * @return string
     */
    public function route(string $uri, string $method)
    {
        $controller = null;

        foreach ($this->routes as $route) {
            if (
                $route['uri'] === $uri &&
                $route['method'] === strtoupper($method)
            ) {
                $controller = $route["controller"];
            }
        }

        if (is_string($controller)) {
            $splits = explode("::", $controller);
            if (is_array($splits)) {
                $classname = array_shift($splits);
                $instance = new $classname;

                $method = array_shift($splits);
                $controller = [$instance, $method];
            }
        }

        if (!$controller) {
            http_response_code(404);

            $instance = new \PTW\Controllers\ExceptionController;
            $controller = [$instance, "get"];

            return (string) call_user_func_array($controller, [
                "404"
            ]);
        }

        return (string) call_user_func_array($controller, [
            array_merge($_GET, $_POST)
        ]);
    }
}