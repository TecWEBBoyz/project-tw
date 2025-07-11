<?php
require_once 'init.php';


use PTW\Services\AuthService;
use PTW\Services\TemplateService;
use PTW\Repositories\AnimalRepository;
use PTW\Models\Animal;

if (!AuthService::isAdminLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

define('UPLOAD_DIR', __DIR__ . '/static/images/');
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

$errors = $_SESSION['errors'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_data']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image']['size'] > MAX_FILE_SIZE) {
            $errors['image'] = 'Il file è troppo grande (massimo 5MB).';
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES['image']['tmp_name']);
        
        if (!in_array($mime_type, ALLOWED_TYPES)) {
            $errors['image'] = 'Formato file non supportato (solo JPEG, PNG, WEBP).';
        }
    } else {
        $errors['image'] = 'L\'immagine è obbligatoria.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $old_data;
        header('Location: createAnimal.php');
        exit;
    }

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
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $old_data;
        header('Location: createAnimal.php');
        exit;
    }

$sanitized_description = strip_tags($old_data['description'], '<strong><em>');

$animalData = [
    'name'        => $old_data['name'],
    'species'     => $old_data['species'],
    'age'         => (int)$old_data['age'],
    'habitat'     => $old_data['habitat'],
    'dimensions'  => $old_data['dimensions'],
    'lifespan'    => $old_data['lifespan'],
    'diet'        => $old_data['diet'],
    'description' => $sanitized_description,
    'image'       => $image_path
];

try {
    $newAnimal = new Animal();
    $newAnimal->setDataFromForm($animalData);
    $animalRepo = new AnimalRepository();
    $success = $animalRepo->Create($newAnimal);

} catch (Exception $e) {
    $success = false;
    $_SESSION['errors']['general'] = 'Errore interno nella creazione dei dati: ' . $e->getMessage();
}

if ($success) {
    header('Location: adminDashboard.php?success=animal_added#animals-table');
    exit;
} else {
    if (!isset($_SESSION['errors']['general'])) {
        $_SESSION['errors']['general'] = 'Si è verificato un errore durante il salvataggio nel database. Riprova.';
    }
    $_SESSION['old_data'] = $old_data;
    if (file_exists($destination)) {
        unlink($destination);
    }
    header('Location: createAnimal.php');
    exit;
}
}

$errorSummaryListItems = '';
$errorSummaryHidden = 'hidden';

if (!empty($errors)) {
    $errorSummaryHidden = ''; 
    foreach ($errors as $key => $message) {
        if ($key === 'general') continue;
        $errorSummaryListItems .= '<li><a href="#' . htmlspecialchars($key) . '">' . htmlspecialchars($message) . '</a></li>';
    }
}

$base_replacements = [
    '[[errorSummaryListItems]]' => $errorSummaryListItems,
    '[[errorSummaryHidden]]' => $errorSummaryHidden,
];

$form_fields = ['name', 'species', 'age', 'habitat', 'dimensions', 'lifespan', 'diet', 'description', 'image'];

foreach ($form_fields as $field) {
    $uc_field = ucfirst($field);

    if ($form_fields == "description") {
        $base_replacements["[[old{$uc_field}]]"] = isset($old_data[$field]) ? htmlspecialchars($old_data[$field]) : '';
    } else {
        $base_replacements["[[old{$uc_field}]]"] = isset($old_data[$field]) ? 'value="' . htmlspecialchars($old_data[$field]) . '"' : '';
    }

    $base_replacements["[[{$field}Error]]"] = isset($errors[$field]) ? htmlspecialchars($errors[$field]) : '';
    $base_replacements["[[{$field}ErrorHidden]]"] = isset($errors[$field]) ? '' : 'hidden';
    $base_replacements["[[{$field}Invalid]]"] = isset($errors[$field]) ? 'aria-invalid="true"' : '';
}

$currentFile = basename(__FILE__, '.php') . '.html';
$htmlContent = TemplateService::renderHtml($currentFile, $base_replacements);

echo $htmlContent;
?>