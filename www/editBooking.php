<?php
require_once 'init.php';


use \PTW\Repositories\BookingRepository;
use \PTW\Repositories\ServiceRepository;
use \PTW\Services\AuthService;
use \PTW\Services\TemplateService;
use \PTW\Models\Booking;
use \PTW\Models\Service;

// Check authentication
if (!AuthService::isUserLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

$currentUser = AuthService::getCurrentUser();
$bookingRepo = new BookingRepository();
$serviceRepo = new ServiceRepository();

$errors = $_SESSION['errors'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_data']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old_data = [
        'id' => trim($_POST['id'] ?? ''),
        'numberOfPeople' => trim($_POST['numberOfPeople'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
    ];

    if (empty($old_data['id'])) {
        http_response_code(400);
        echo "ID della prenotazione mancante.";
        exit();
    }
    
    $booking = $bookingRepo->GetElementByID($old_data['id']);

    if (!$booking || $booking->getUserId() !== $currentUser->getId()) {
        http_response_code(403); 
        echo "Accesso non autorizzato a questa prenotazione.";
        exit();
    }

    $service = $serviceRepo->GetElementByID($booking->getServiceId());
    if (!$service) {
        http_response_code(404);
        echo "Servizio associato alla prenotazione non trovato.";
        exit();
    }

    if (empty($old_data['numberOfPeople'])) {
        $errors['numberOfPeople'] = 'Il numero di partecipanti è obbligatorio.';
    } elseif (!filter_var($old_data['numberOfPeople'], FILTER_VALIDATE_INT) || $old_data['numberOfPeople'] < 1) {
        $errors['numberOfPeople'] = 'Il numero di partecipanti deve essere almeno 1.';
    } elseif ($old_data['numberOfPeople'] > $service->getMaxPeople()) {
        $errors['numberOfPeople'] = 'Il numero di partecipanti supera il massimo consentito (' . $service->getMaxPeople() . ') per questo servizio.';
    }

    if (empty($old_data['date'])) {
        $errors['date'] = 'La data è obbligatoria.';
    } else {
        $selectedDate = DateTime::createFromFormat('Y-m-d', $old_data['date']);
        $today = new DateTime('today');
        if (!$selectedDate || $selectedDate->format('Y-m-d') !== $old_data['date']) {
            $errors['date'] = 'Il formato della data non è valido.';
        } elseif ($selectedDate < $today) {
            $errors['date'] = 'La data non può essere nel passato.';
        }
    }

    if (strlen($old_data['notes']) > 75) {
        $errors['notes'] = 'Le note non possono superare i 75 caratteri.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $old_data;
        header('Location: editBooking.php?id=' . urlencode($old_data['id']));
        exit;
    }

    $sanitized_notes = strip_tags($old_data['notes'], '<strong><em>');
    
    $bookingRepo->Update($booking->getId(), new Booking(array_merge($booking->toArray(), [
        "num_people" => (int)$old_data["numberOfPeople"],
        "date" => $old_data["date"],
        "notes" => $sanitized_notes
    ])));

    header("Location: userDashboard.php?success=booking_updated");
    exit();
}



if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "ID della prenotazione mancante.";
    exit();
}

$booking = $bookingRepo->GetElementByID($_GET["id"]);

if (!$booking || $booking->getUserId() !== $currentUser->getId()) {
    http_response_code(403);
    echo "Accesso non autorizzato a questa prenotazione.";
    exit();
}

$service = $serviceRepo->GetElementByID($booking->getServiceId());
if (!$service) {
    http_response_code(404);
    echo "Servizio associato non trovato.";
    exit();
}

$errorSummaryHtml = '';
if (!empty($errors)) {
    $errorSummaryHtml = '<div id="error-summary-container" class="error-summary" role="alert" tabindex="-1">';
    $errorSummaryHtml .= '<h2>Attenzione, sono presenti errori nel modulo:</h2><ul>';
    foreach ($errors as $key => $message) {
        $errorSummaryHtml .= '<li><a href="#' . htmlspecialchars($key) . '">' . htmlspecialchars($message) . '</a></li>';
    }
    $errorSummaryHtml .= '</ul></div>';
}

// Prepara i placeholder per il template
$replacements = [
    "[[bookingId]]" => $booking->getId(),
    "[[bookingService]]" => $service->getName(),
    "[[serviceMaxPeople]]" => $service->getMaxPeople(),
    "[[minDate]]" => (new DateTime("now"))->format("Y-m-d"),

    '[[oldNumberOfPeople]]' => isset($old_data['numberOfPeople']) ? htmlspecialchars($old_data['numberOfPeople']) : $booking->getNumberOfPeople(),
    '[[oldDate]]' => isset($old_data['date']) ? htmlspecialchars($old_data['date']) : $booking->getDate()->format("Y-m-d"),
    '[[oldNotes]]' => isset($old_data['notes']) ? htmlspecialchars($old_data['notes']) : $booking->getNotes(),
    
    '[[errorSummaryContainer]]' => $errorSummaryHtml,
    '[[numberOfPeopleError]]' => isset($errors['numberOfPeople']) ? '<p id="numberOfPeople-error" class="error-message" role="alert">' . htmlspecialchars($errors['numberOfPeople']) . '</p>' : '',
    '[[dateError]]' => isset($errors['date']) ? '<p id="date-error" class="error-message" role="alert">' . htmlspecialchars($errors['date']) . '</p>' : '',
    '[[notesError]]' => isset($errors['notes']) ? '<p id="notes-error" class="error-message" role="alert">' . htmlspecialchars($errors['notes']) . '</p>' : '',

    '[[numberOfPeopleInvalid]]' => isset($errors['numberOfPeople']) ? 'aria-invalid="true"' : '',
    '[[dateInvalid]]' => isset($errors['date']) ? 'aria-invalid="true"' : '',
    '[[notesInvalid]]' => isset($errors['notes']) ? 'aria-invalid="true"' : '',
];

$currentFile = basename(__FILE__, '.php') . '.html';
$htmlContent = TemplateService::renderHtml($currentFile, $replacements);
echo $htmlContent;

?>