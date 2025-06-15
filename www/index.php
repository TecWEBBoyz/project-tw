<?php
require_once 'init.php';

use PTW\Services\TemplateService;
use PTW\Repositories\ReviewRepository;
use PTW\Repositories\UserRepository;
use PTW\Models\Review;
use PTW\Models\User;
use PTW\Repositories\AnimalRepository;
use PTW\Models\Animal;

$reviewRepo = new ReviewRepository();
$userRepo = new UserRepository();

// Recupera le prime 3 recensioni
$reviews = $reviewRepo->GetPage(1, 3);

$replacements = []; 
$counter = 1;

foreach ($reviews as $review) {
    if (!$review instanceof Review) {
        continue;
    }

    $user = $userRepo->GetElementByID($review->getUserId());
    if (!$user instanceof User) {
        continue;
    }

    // Aggiungi i segnaposto per ogni recensione
    $replacements['[[reviewer' . $counter . 'Name]]'] = htmlspecialchars($user->getUsername());
    $replacements['[[review' . $counter . 'Comment]]'] = htmlspecialchars($review->getComment());
    $replacements['[[review' . $counter . 'Rating]]'] = htmlspecialchars($review->getRating());

    $counter++;
}
$replacements["[[randomAnimals]]"] = $htmlFeaturedAnimals;
// Riempi i segnaposto vuoti per evitare che rimangano non sostituiti
for ($i = $counter; $i <= 3; $i++) {
    $replacements['[[reviewer' . $i . 'Name]]'] = '<abbr title="Non disponibile">N.d.</abbr>';
    $replacements['[[review' . $i . 'Comment]]'] = 'Nessuna recensione disponibile.';
    $replacements['[[review' . $i . 'Rating]]'] = '<abbr title="Non disponibile">N.d.</abbr>';
}

$animalRepo = new AnimalRepository();
$allAnimals = $animalRepo->All();

$featuredAnimals = array_slice($allAnimals, 0, 3);

#TODO: va fatto meglio
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

$replacements["[[randomAnimals]]"] = $htmlFeaturedAnimals;

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, $replacements);
echo $htmlContent;
?>