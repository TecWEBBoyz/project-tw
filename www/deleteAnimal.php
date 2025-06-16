<?php
require_once 'init.php';

session_start();

use PTW\Repositories\BookingRepository;
use PTW\Services\AuthService;

// Check authentication before allowing access
if (!AuthService::isAdminLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('ALLOW: POST');
    exit();
}

if (!array_key_exists('animal_id', $_POST)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data field."]);
    exit();
}

$animalRepo = new \PTW\Repositories\AnimalRepository();

if (!$animalRepo->ExistsId($_POST["animal_id"])) {
    http_response_code(404);
    exit();
}

$animalRepo->Delete($_POST["animal_id"]);

if ($animalRepo->ExistsId($_POST["animal_id"])) {
    http_response_code(500);
    exit();
}

header("Location: adminDashboard.php");

