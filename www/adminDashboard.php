<?php
require_once 'init.php';

use PTW\Services\AuthService;
use PTW\Services\TemplateService;

// Check authentication before allowing access
if (!AuthService::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$animalRepo = new \PTW\Repositories\AnimalRepository();
$animals = $animalRepo->All();

$animalRows = '';
foreach ($animals as $animal) {

    if (!$animal instanceof \PTW\Models\Animal) {
        continue;
    }

    $animalRows .= "<tr>
        <td>{$animal->getName()}</td>
        <td>{$animal->getSpecies()}</td>
        <td>{$animal->getDescription()}</td>
    </tr>";
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);

$htmlContent = str_replace('[[animalTable]]', $animalRows, $htmlContent);

echo $htmlContent;
?>