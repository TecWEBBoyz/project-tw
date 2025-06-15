<?php
require_once 'init.php';

use PTW\Services\TemplateService;
use PTW\Repositories\AnimalRepository;
use PTW\Models\Animal;

$animalRepo = new AnimalRepository();
$allAnimals = $animalRepo->All();

$featuredAnimals = array_slice($allAnimals, 0, 3);

$htmlFeaturedAnimals = '';
if (!empty($featuredAnimals)) {
    foreach ($featuredAnimals as $animal) {
        if (!$animal instanceof Animal) {
            continue;
        }

        $shortDescription = htmlspecialchars(trim(preg_split("/\./", $animal->getDescription())[0]) . ".");

        $htmlFeaturedAnimals .= '<article class="animal-card">' . PHP_EOL;
        $htmlFeaturedAnimals .= '    <img class="card-img-top" src="' . htmlspecialchars($animal->getImage()) . '" alt="Foto di ' . htmlspecialchars($animal->getName()) . ', ' . htmlspecialchars($animal->getSpecies()) . '">' . PHP_EOL;
        $htmlFeaturedAnimals .= '    <div class="card-body">' . PHP_EOL;
        $htmlFeaturedAnimals .= '        <h3 class="card-title">' . htmlspecialchars($animal->getName()) . '</h3>' . PHP_EOL;
        $htmlFeaturedAnimals .= '        <p class="muted">' . htmlspecialchars($animal->getSpecies()) . ', ' . htmlspecialchars($animal->getHabitat()) . '</p>' . PHP_EOL;
        $htmlFeaturedAnimals .= '        <p class="card-text">' . $shortDescription . '</p>' . PHP_EOL;
        $htmlFeaturedAnimals .= '    </div>' . PHP_EOL;
        $htmlFeaturedAnimals .= '    <div class="card-link">' . PHP_EOL;
        $htmlFeaturedAnimals .= '        <a class="light-link" href="animal.php?id=' . htmlspecialchars($animal->getId()) . '">Scopri di più</a>' . PHP_EOL;
        $htmlFeaturedAnimals .= '    </div>' . PHP_EOL;
        $htmlFeaturedAnimals .= '</article>' . PHP_EOL;
    }
}
$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[randomAnimals]]" => $htmlFeaturedAnimals,
]);
echo $htmlContent;
?>