<?php

/**
 * This file contains all the routes for the application.
 * 
 * You can define any route using the following methods:
 * - get($uri, $controller) (Implemented)
 * - post($uri, $controller) (Implemented)
 * - put($uri, $controller) (Implemented)
 * - delete($uri, $controller) (Implemented)
 */

use PTW\Modules\Router\Router;

$router = new Router();

$router->get("/", \PTW\Controllers\HomeController::class . "::get");
$router->get("/home", \PTW\Controllers\HomeController::class . "::get");
//$router->get("/about", \PTW\Controllers\AboutController::class . "::get");
//$router->get("/test", \PTW\Controllers\TestController::class . "::get");
$router->get("/login", \PTW\Controllers\LoginController::class . "::get");
$router->post("/login", \PTW\Controllers\LoginController::class . "::post");
$router->get("/logout", \PTW\Controllers\LogoutController::class . "::get");
$router->get("/admin", \PTW\Controllers\AdminController::class . "::get");
$router->get("/dashboard", \PTW\Controllers\DashboardController::class . "::get");
$router->get("/500", \PTW\Controllers\ExceptionController::class . "::error_500");
$router->get("/gallerydetails", \PTW\Controllers\GalleryController::class . "::get");

return $router;

