<?php
require_once 'init.php';

session_start();

use PTW\Services\AuthService;
use PTW\Services\TemplateService;
use PTW\Repositories\AnimalRepository;
use PTW\Models\Animal;

if (!AuthService::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

define('UPLOAD_DIR', __DIR__ . '/static/images/');
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

$errors = $_SESSION['errors'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_data']);

$animalRepo = new AnimalRepository();
$animal = null;

// Recupera l'ID dell'animale
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['animal_id'])) {
    $animalId = $_GET['animal_id'];
    $animal = $animalRepo->GetElementById($animalId);

    if (!$animal instanceof Animal) {
        $_SESSION['errors']['general'] = 'Animale non trovato.';
        header('Location: adminDashboard.php?error=animal_not_found');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['animal_id'])) {
    // Gestione dell'aggiornamento
    $animalId = $_POST['animal_id'];
    $animal = $animalRepo->GetElementById($animalId);

    if (!$animal instanceof Animal) {
        $_SESSION['errors']['general'] = 'Animale non trovato.';
        header('Location: adminDashboard.php?error=animal_not_found');
        exit;
    }

    $old_data = [
        'name' => trim($_POST['name'] ?? ''),
        'species' => trim($_POST['species'] ?? ''),
        'age' => trim($_POST['age'] ?? ''),
        'habitat' => trim($_POST['habitat'] ?? ''),
        'dimensions' => trim($_POST['dimensions'] ?? ''),
        'lifespan' => trim($_POST['lifespan'] ?? ''),
        'diet' => trim($_POST['diet'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
    ];

    // Validazione dei dati
    if (empty($old_data['name'])) $errors['name'] = 'Il nome è obbligatorio.';
    if (empty($old_data['species'])) $errors['species'] = 'La specie è obbligatoria.';
    if (empty($old_data['age'])) {
        $errors['age'] = 'L\'età è obbligatoria.';
    } elseif (!filter_var($old_data['age'], FILTER_VALIDATE_INT) || $old_data['age'] < 0) {
        $errors['age'] = 'L\'età deve essere un numero intero non negativo.';
    }
    if (empty($old_data['habitat'])) $errors['habitat'] = 'L\'habitat è obbligatorio.';
    if (empty($old_data['dimensions'])) $errors['dimensions'] = 'Le dimensioni sono obbligatorie.';
    if (empty($old_data['lifespan'])) $errors['lifespan'] = 'L\'aspettativa di vita è obbligatoria.';
    if (empty($old_data['diet'])) $errors['diet'] = 'La dieta è obbligatoria.';
    if (empty($old_data['description'])) $errors['description'] = 'La descrizione è obbligatoria.';

    // Gestione immagine
    $image_path = $animal->getImage(); // Mantieni l'immagine esistente
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image']['size'] > MAX_FILE_SIZE) {
            $errors['image'] = 'Il file è troppo grande (massimo 5MB).';
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES['image']['tmp_name']);

        if (!in_array($mime_type, ALLOWED_TYPES)) {
            $errors['image'] = 'Formato file non supportato (solo JPEG, PNG, WEBP).';
        }

        if (empty($errors)) {
            $original_filename = preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['image']['name']);
            $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);

            $base_name = pathinfo($original_filename, PATHINFO_FILENAME);
            $counter = 1;
            $destination = UPLOAD_DIR . $original_filename;

            while (file_exists($destination)) {
                $new_filename = $base_name . '_' . $counter . '.' . $file_extension;
                $destination = UPLOAD_DIR . $new_filename;
                $counter++;
            }

            $final_filename = basename($destination);

            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'static/images/' . $final_filename;
            } else {
                $errors['image'] = 'Errore durante il caricamento del file.';
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $old_data;
        header('Location: editAnimal.php?animal_id=' . $animalId);
        exit;
    }

    // Aggiorna i dati dell'animale
    $animalData = [
        'name'        => $old_data['name'],
        'species'     => $old_data['species'],
        'age'         => (int)$old_data['age'],
        'habitat'     => $old_data['habitat'],
        'dimensions'  => $old_data['dimensions'],
        'lifespan'    => $old_data['lifespan'],
        'diet'        => $old_data['diet'],
        'description' => $old_data['description'],
        'image'       => $image_path,
    ];

    try {
        $animal->setDataFromForm($animalData);
        $success = $animalRepo->Update($animalId, $animal);
    } catch (Exception $e) {
        $success = false;
        $_SESSION['errors']['general'] = 'Errore interno nella modifica dei dati: ' . $e->getMessage();
        echo 'Errore interno nella modifica dei dati: ' . $e->getMessage();
    }

    if ($success) {
        header('Location: adminDashboard.php?success=animal_updated#animals-table');
        exit;
    } else {
        $_SESSION['errors']['general'] = 'Si è verificato un errore durante l\'aggiornamento nel database. Riprova.';
        $_SESSION['old_data'] = $old_data;
        header('Location: editAnimal.php?animal_id=' . $animalId);
        exit;
    }
}

// Precompila i campi del modulo con i dati esistenti
if ($animal instanceof Animal) {
    $old_data = [
        'animalId' => $animal->getId(),
        'name' => $animal->getName(),
        'species' => $animal->getSpecies(),
        'age' => (int)$animal->getAge(),
        'habitat' => $animal->getHabitat(),
        'dimensions' => $animal->getDimensions(),
        'lifespan' => $animal->getLifespan(),
        'diet' => $animal->getDiet(),
        'description' => $animal->getDescription(),
        'image' => $animal->getImage(),
        'imageAlt' => "Attuale foto di " . htmlspecialchars($animal->getName()) . ", un esemplare di " . htmlspecialchars($animal->getSpecies()),
    ];
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

$base_replacements = [
    '[[errorSummaryContainer]]' => $errorSummaryHtml,
    '[[animalId]]' => $old_data['animalId'],
];

$form_fields = ['name', 'species', 'age', 'habitat', 'dimensions', 'lifespan', 'diet', 'description', 'image'];

foreach ($form_fields as $field) {
    $uc_field = ucfirst($field);
    $base_replacements["[[old{$uc_field}]]"] = isset($old_data[$field]) ? htmlspecialchars($old_data[$field]) : '';
    $base_replacements["[[{$field}Error]]"] = isset($errors[$field]) ? htmlspecialchars($errors[$field]) : '';
    $base_replacements["[[{$field}Invalid]]"] = isset($errors[$field]) ? 'aria-invalid="true"' : '';
}


$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, $base_replacements);

echo $htmlContent;
?>