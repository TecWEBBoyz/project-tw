<?php
require_once 'init.php';

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
    header('Location: login.php?error=unauthorized');
    exit;
}
// Get current user
$currentUser = AuthService::getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);
    
    // Check if all fields (except for user_id are present)
    if (!array_key_exists('service_id', $_POST) 
        || !array_key_exists('numberOfPeople', $_POST) 
        || !array_key_exists('date', $_POST)) {
    
        http_response_code(400);
        echo json_encode(["message" => "Missing data field."]);
        exit();
    }

    // Create new booking
    $newBooking = new Booking([
        'id' => uniqid(),
        'user_id' => $currentUser->getId(),
        'service_id' => $_POST['service_id'],
        'num_people' => $_POST['numberOfPeople'],
        'date' => $_POST['date']
    ]);
    $bookingRepo->Create($newBooking);

    header("Location: userDashboard.php");
    exit();
}

// Check if service_id is provided through GET method
if (!array_key_exists('service_id', $_GET)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data field."]);
    exit();
}

//
$service = $serviceRepo->GetElementByID($_GET['service_id']);
if (!$service) {
    http_response_code(404);
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[serviceId]]" => $service->getId(),
    "[[serviceName]]" => $service->getName(),
    "[[serviceMaxPeople]]" => $service->getMaxPeople(),
    "[[minDate]]" => (new DateTime("now"))->format("Y-m-d"),
]);
echo $htmlContent;

?>