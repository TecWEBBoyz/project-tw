<?php
require_once 'init.php';

session_start();

use \PTW\Repositories\BookingRepository;
use \PTW\Repositories\ServiceRepository;
use \PTW\Services\AuthService;
use \PTW\Services\TemplateService;
use \PTW\Models\Booking;

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
        'service' => trim($_POST['service'] ?? ''),
        'numberOfPeople' => trim($_POST['numberOfPeople'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
        'service_id' => $_POST['service_id'] ?? ''
    ];
    
    if (empty($old_data['service'])) {
        $errors['service'] = 'Il servizio è obbligatorio.';
    }

    $service = null;
    if (!empty($old_data['service'])) {
        $service = $serviceRepo->GetElementByUnique('name', $old_data['service']);
        if (!$service) {
            $errors['service'] = 'Il servizio selezionato non è valido.';
        }
    }

    if (empty($old_data['numberOfPeople'])) {
        $errors['numberOfPeople'] = 'Il numero di partecipanti è obbligatorio.';
    } elseif (!filter_var($old_data['numberOfPeople'], FILTER_VALIDATE_INT) || $old_data['numberOfPeople'] < 1) {
        $errors['numberOfPeople'] = 'Il numero di partecipanti deve essere almeno 1.';
    } elseif ($service && $old_data['numberOfPeople'] > $service->getMaxPeople()) {
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
        header('Location: createBooking.php?service_id=' . urlencode($old_data['service_id']));
        exit;
    }

    $sanitized_notes = strip_tags($old_data['notes'], '<strong><em>');

    $newBooking = new Booking([
        'id' => uniqid(),
        'user_id' => $currentUser->getId(),
        'service_id' => $service->getId(),
        'num_people' => (int)$old_data['numberOfPeople'],
        'date' => $old_data['date'],
        'notes' => $sanitized_notes
    ]);

    $bookingRepo->Create($newBooking);

    header("Location: userDashboard.php?success=booking_created");
    exit();
}



if (!isset($_GET['service_id'])) {
    header('Location: services.php?error=missing_service'); 
    exit();
}

$service = $serviceRepo->GetElementByID($_GET['service_id']);
if (!$service) {
    http_response_code(404);
    echo "Servizio non trovato.";
    exit();
}

$errorSummaryHtml = '';
if (!empty($errors)) {
    $errorSummaryHtml = '<div id="error-summary-container" class="error-summary" role="alert" tabindex="-1">';
    $errorSummaryHtml .= '<h2>Attenzione, sono presenti errori nel modulo:</h2><ul>';
    foreach ($errors as $key => $message) {
        if ($key === 'service_id') continue;
        $errorSummaryHtml .= '<li><a href="#' . htmlspecialchars($key) . '">' . htmlspecialchars($message) . '</a></li>';
    }
    $errorSummaryHtml .= '</ul></div>';
}

$replacements = [
    "[[serviceId]]" => $service->getId(),
    "[[serviceName]]" => $service->getName(),
    "[[serviceMaxPeople]]" => $service->getMaxPeople(),
    "[[minDate]]" => (new DateTime("now"))->format("Y-m-d"),

    '[[errorSummaryContainer]]' => $errorSummaryHtml,
    '[[oldService]]' => isset($old_data['service']) ? htmlspecialchars($old_data['service']) : $service->getName(),
    '[[oldNumberOfPeople]]' => isset($old_data['numberOfPeople']) ? htmlspecialchars($old_data['numberOfPeople']) : '1',
    '[[oldDate]]' => isset($old_data['date']) ? htmlspecialchars($old_data['date']) : '',
    '[[oldNotes]]' => isset($old_data['notes']) ? htmlspecialchars($old_data['notes']) : '',
    
    '[[serviceError]]' => isset($errors['service']) ? '<p id="service-error" class="error-message" role="alert">' . htmlspecialchars($errors['service']) . '</p>' : '',
    '[[numberOfPeopleError]]' => isset($errors['numberOfPeople']) ? '<p id="numberOfPeople-error" class="error-message" role="alert">' . htmlspecialchars($errors['numberOfPeople']) . '</p>' : '',
    '[[dateError]]' => isset($errors['date']) ? '<p id="date-error" class="error-message" role="alert">' . htmlspecialchars($errors['date']) . '</p>' : '',
    '[[notesError]]' => isset($errors['notes']) ? '<p id="notes-error" class="error-message" role="alert">' . htmlspecialchars($errors['notes']) . '</p>' : '',

    '[[serviceInvalid]]' => isset($errors['service']) ? 'aria-invalid="true"' : '',
    '[[numberOfPeopleInvalid]]' => isset($errors['numberOfPeople']) ? 'aria-invalid="true"' : '',
    '[[dateInvalid]]' => isset($errors['date']) ? 'aria-invalid="true"' : '',
    '[[notesInvalid]]' => isset($errors['notes']) ? 'aria-invalid="true"' : '',
];

$selected_service = $replacements['[[oldService]]'];
$replacements['[[safariSelected]]'] = ($selected_service === 'Safari') ? 'selected' : '';
$replacements['[[ingressoSelected]]'] = ($selected_service === 'Ingresso') ? 'selected' : '';
$replacements['[[visitaGuidataSelected]]'] = ($selected_service === 'Visita guidata') ? 'selected' : '';

$currentFile = basename(__FILE__, '.php') . '.html';
$htmlContent = TemplateService::renderHtml($currentFile, $replacements);
echo $htmlContent;