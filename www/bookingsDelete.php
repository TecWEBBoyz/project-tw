<?php
require_once 'init.php';

use PTW\Repositories\BookingRepository;
use PTW\Repositories\ServiceRepository;
use PTW\Services\AuthService;
use PTW\Services\TemplateService;

// Check authentication before allowing access
if (!AuthService::isUserLoggedIn()) {
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

header("Location: userDashboard.php");
