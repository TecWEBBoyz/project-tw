<?php
require_once 'vendor/autoload.php';

use PTW\Services\AuthService;
use PTW\Services\DBService;
use PTW\Services\TemplateService;

// Check authentication before allowing access
if (!AuthService::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$animals = DBService::getAllAnimals();

$animalRows = '';
foreach ($animals as $animal) {
    $name = htmlspecialchars($animal['name'] ?? '');
    $specie = htmlspecialchars($animal['specie'] ?? ''); // assuming 'type' is used instead of 'species'
    $description = htmlspecialchars($animal['description'] ?? '');

    $animalRows .= "<tr>
        <td>{$name}</td>
        <td>{$specie}</td>
        <td>{$description}</td>
    </tr>";
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);

$htmlContent = str_replace('[[animalTable]]', $animalRows, $htmlContent);

echo $htmlContent;
?>