<?php
require_once 'init.php';

use PTW\Services\TemplateService;
use PTW\Repositories\UserRepository;
use PTW\Models\User;
use PTW\Repositories\AnimalRepository;
use PTW\Models\Animal;

$userRepo = new UserRepository();
$replacements = []; 
$counter = 1;
$animalRepo = new AnimalRepository();
$featuredAnimals = $animalRepo->GetPage(1, 3);
$featuredAnimalsData = [];
if (!empty($featuredAnimals)) {
    foreach ($featuredAnimals as $animal) {
        if (!$animal instanceof Animal) {
            continue;
        }

        $featuredAnimalsData["animals"][] = [
            '[[id]]'          => htmlspecialchars($animal->getId()),
            '[[name]]'        => htmlspecialchars($animal->getName()),
            '[[species]]'     => htmlspecialchars($animal->getSpecies()),
            '[[habitat]]'     => htmlspecialchars($animal->getHabitat()),
            '[[image]]'       => htmlspecialchars($animal->getImage()),
            '[[description]]' => htmlspecialchars(trim(preg_split("/\./", $animal->getDescription())[0]) . "."),
            '[[alt_text]]'    => "Foto di " . htmlspecialchars($animal->getName()) . ", un esemplare di " . htmlspecialchars($animal->getSpecies()),
        ];
    }
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml(
    $currentFile, 
    [],
    $featuredAnimalsData
);
echo $htmlContent;

?>