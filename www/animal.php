<?php
require_once 'init.php';

use PTW\Services\TemplateService;

if (!isset($_GET['id']) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $_GET['id'])) {
    header("Location: animals.php?error=invalid_id");
    exit();
}

$animalRepo = new \PTW\Repositories\AnimalRepository();

/** @var \PTW\Models\Animal|null $animal */
$animal = $animalRepo->GetElementByID($_GET['id']);

if (empty($animal)) {
    header("Location: animals.php?error=not_found");
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[specie]]" => htmlspecialchars($animal->getSpecies()),
    "[[name]]" => htmlspecialchars($animal->getName()),
    "[[age]]" => htmlspecialchars($animal->getAge()),
    "[[habitat]]" => htmlspecialchars($animal->getHabitat()),
    "[[dimensions]]" => htmlspecialchars($animal->getDimensions()),
    "[[lifespan]]" => htmlspecialchars($animal->getLifespan()),
    "[[diet]]" => htmlspecialchars($animal->getDiet()),
    "[[description]]" => htmlspecialchars($animal->getDescription()),
    "[[image]]" => htmlspecialchars($animal->getImage())
]);

echo $htmlContent;

?>