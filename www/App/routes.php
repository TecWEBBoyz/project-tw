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

// Home Page
$router->get("/", \PTW\Controllers\HomeController::class . "::get");
$router->get("/home", \PTW\Controllers\HomeController::class . "::get");

// Utility Page
$router->get("/about", \PTW\Controllers\AboutController::class . "::get");
$router->get("/services", \PTW\Controllers\ServicesController::class . "::get");
$router->get("/contact", \PTW\Controllers\ContactController::class . "::get");

//$router->get("/about", \PTW\Controllers\AboutController::class . "::get");
//$router->get("/test", \PTW\Controllers\TestController::class . "::get");

// Register Page
$router->get("/register", \PTW\Controllers\RegisterController::class . "::get");
$router->post("/register", \PTW\Controllers\RegisterController::class . "::post");

// Login page
$router->get("/login", \PTW\Controllers\LoginController::class . "::get");
$router->post("/login", \PTW\Controllers\LoginController::class . "::post");

// Logout Page
$router->get("/logout", \PTW\Controllers\LogoutController::class . "::get");

// Admin page
$router->get("/admin", \PTW\Controllers\AdminController::class . "::get");
$router->get("/admin/justuploadedimage", \PTW\Controllers\AdminController::class . "::justUploadedImage");
$router->get("/admin/edit-image", \PTW\Controllers\AdminController::class . "::editSingleImage");
$router->post("/admin/edit-image-visibility", \PTW\Controllers\AdminController::class . "::editImageVisibility");
$router->get("/admin/upload", \PTW\Controllers\AdminController::class . "::uploadForm");
$router->post("/admin/upload", \PTW\Controllers\AdminController::class . "::uploadImage");
$router->post("/admin/update-image", \PTW\Controllers\AdminController::class . "::editImage");
$router->post("/admin/delete-image", \PTW\Controllers\AdminController::class . "::deleteImage");

// Dashboard
$router->get("/dashboard", \PTW\Controllers\DashboardController::class . "::get");

// Error Pages
$router->get("/500", \PTW\Controllers\ExceptionController::class . "::error_500");

// Gallery Page
$router->get("/gallerydetails", \PTW\Controllers\GalleryController::class . "::get");

// Booking page
$router->get("/book-service", \PTW\Controllers\BookingController::class . "::get");
$router->post("/book-service", \PTW\Controllers\BookingController::class . "::post");

// Profile Page
$router->get("/profile", \PTW\Controllers\ProfileController::class . "::get");

// Style Page
$router->get("/style", \PTW\Controllers\StyleController::class . "::test");

return $router;

