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
$router->get("/register", \PTW\Controllers\RegisterController::class . "::get");
$router->post("/register", \PTW\Controllers\RegisterController::class . "::post");
$router->get("/login", \PTW\Controllers\LoginController::class . "::get");
$router->post("/login", \PTW\Controllers\LoginController::class . "::post");
$router->get("/logout", \PTW\Controllers\LogoutController::class . "::get");
$router->get("/admin", \PTW\Controllers\AdminController::class . "::get");
$router->get("/admin/justuploadedimage", \PTW\Controllers\AdminController::class . "::justUploadedImage");
$router->get("/admin/edit-image", \PTW\Controllers\AdminController::class . "::editSingleImage");
$router->get("/admin/upload", \PTW\Controllers\AdminController::class . "::uploadForm");
$router->post("/admin/upload", \PTW\Controllers\AdminController::class . "::uploadImage");
$router->post("/admin/update-image", \PTW\Controllers\AdminController::class . "::editImage");
$router->post("/admin/delete-image", \PTW\Controllers\AdminController::class . "::deleteImage");
$router->get("/dashboard", \PTW\Controllers\DashboardController::class . "::get");
$router->get("/500", \PTW\Controllers\ExceptionController::class . "::error_500");
$router->get("/gallerydetails", \PTW\Controllers\GalleryController::class . "::get");

$router->get("/book-service", \PTW\Controllers\BookingController::class . "::get");
$router->post("/book-service", \PTW\Controllers\BookingController::class . "::post");


return $router;

