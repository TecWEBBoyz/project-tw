<?php
require_once 'init.php';

use PTW\Repositories\AnimalRepository;
use PTW\Services\TemplateService;
use function PTW\abort;

if (!isset($_GET['id']) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $_GET['id'])) {
    abort(404);
}

$animalRepo = new \PTW\Repositories\AnimalRepository();

/** @var \PTW\Models\Animal|null $animal */
$animal = $animalRepo->GetElementByID($_GET['id']);

if (empty($animal)) {
    abort(404);
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
    "[[description]]" => strip_tags($animal->getDescription(), '<strong><em>'),
    "[[image]]" => htmlspecialchars($animal->getImage())
]);

echo $htmlContent;

?>