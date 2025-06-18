<?php
require_once 'init.php';

session_start();

use \PTW\Repositories\BookingRepository;
use \PTW\Repositories\ServiceRepository;
use \PTW\Services\AuthService;
use \PTW\Services\TemplateService;

use \PTW\Models\Booking;
use \PTW\Models\Service;

$bookingRepo = new BookingRepository();
$serviceRepo = new ServiceRepository();

// Check authentication before allowing access
if (!AuthService::isUserLoggedIn()) {
    // Salva l'URL corrente nella sessione
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);
    
    if (!array_key_exists('id', $_POST) 
        || !array_key_exists('numberOfPeople', $_POST) 
        || !array_key_exists('date', $_POST)
        || !array_key_exists('notes', $_POST)) {
    
        http_response_code(400);
        echo json_encode(["message" => "Missing data field."]);
        exit();
    }

    $booking = $bookingRepo->GetElementByID($_POST["id"]);

    if (!$booking instanceof Booking) {
        http_response_code(404);
        exit();
    }
    $sanitized_notes = strip_tags($_POST['notes'], '<strong><em>');
    $bookingRepo->Update($_POST['id'], new Booking(array_merge($booking->toArray(), [
        "num_people" => $_POST["numberOfPeople"],
        "date" => $_POST["date"],
        "notes" => $sanitized_notes
    ])));

    http_response_code(200);
    header("Location: userDashboard.php");
    exit();
}

if (!array_key_exists('id', $_GET)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data field."]);
    exit();
}

$booking = $bookingRepo->GetElementByID($_GET["id"]);

if (!$booking instanceof Booking) {
    http_response_code(404);
    exit();
}

$service = $serviceRepo->GetElementByID($booking->getServiceId());

if (!$service instanceof Service) {
    http_response_code(404);
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[bookingId]]" => $booking->getId(),
    "[[bookingPartecipants]]" => $booking->getNumberOfPeople(),
    "[[bookingService]]" => $service->getName(),
    "[[bookingDate]]" => $booking->getDate()->format("Y-m-d"),
    "[[minDate]]" => (new DateTime("now"))->format("Y-m-d"),
    "[[notes]]" => $booking->getNotes(),
    "[[serviceMaxPeople]]" => $service->getMaxPeople()
]);
echo $htmlContent;

?>