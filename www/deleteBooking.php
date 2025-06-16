<?php
require_once 'init.php';

session_start();

use PTW\Repositories\BookingRepository;
use PTW\Services\AuthService;

// Check authentication before allowing access
if (!(AuthService::isUserLoggedIn() || AuthService::isAdminLoggedIn())) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('ALLOW: POST');
    exit();
}

if (!array_key_exists('booking_id', $_POST)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data field."]);
    exit();
}

$bookingRepo = new BookingRepository();

if (!$bookingRepo->ExistsId($_POST["booking_id"])) {
    http_response_code(404);
    exit();
}

$bookingRepo->Delete($_POST["booking_id"]);

if ($bookingRepo->ExistsId($_POST["booking_id"])) {
    http_response_code(500);
    exit();
}

if (AuthService::isAdminLoggedIn()) {
    header("Location: adminDashboard.php");
    exit();
}

header("Location: userDashboard.php");
