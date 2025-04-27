<?php
require_once("phplibs/authManager.php");
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");

// Check authentication before allowing access
if (!AuthManager::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$animals = Database::getAllAnimals();

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
$htmlContent = Templating::renderHtml($currentFile);

$htmlContent = str_replace('[[animalTable]]', $animalRows, $htmlContent);

echo $htmlContent;
?>