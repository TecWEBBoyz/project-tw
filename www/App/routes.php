<?php

/**
 * This file contains all the routes for the application.
 * 
 * You can define any route using the following methods:
 * - get($uri, $controller) (Implemented)
 * - post($uri, $controller) (Not implemented yet)
 * - delete($uri, $controller) (Not implemented yet)
 * - put($uri, $controller) (Not implemented yet)
 */

use PTW\Modules\Router\Router;

$router = new Router();

$router->get("/", \PTW\Controllers\HomeController::class . "::get");
$router->get("/home", \PTW\Controllers\HomeController::class . "::get");
$router->get("/about", \PTW\Controllers\AboutController::class . "::get");
$router->get("/test", \PTW\Controllers\TestController::class . "::get");
$router->get("/gallerydetails", \PTW\Controllers\GalleryController::class . "::get");

return $router;

