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
$reviews = $reviewRepo->GetPage(1, 3);
$replacements = []; 
$counter = 1;

foreach ($reviews as $review) {
    if (!$review instanceof Review) continue;
    $user = $userRepo->GetElementByID($review->getUserId());
    if (!$user instanceof User) continue;

    $replacements['[[reviewer' . $counter . 'Name]]'] = htmlspecialchars($user->getUsername());
    $replacements['[[review' . $counter . 'Comment]]'] = htmlspecialchars($review->getComment());
    $replacements['[[review' . $counter . 'Rating]]'] = htmlspecialchars($review->getRating());
    $counter++;
}

for ($i = $counter; $i <= 3; $i++) {
    $replacements['[[reviewer' . $i . 'Name]]'] = '<abbr title="Non disponibile">N.d.</abbr>';
    $replacements['[[review' . $i . 'Comment]]'] = 'Nessuna recensione disponibile.';
    $replacements['[[review' . $i . 'Rating]]'] = '<abbr title="Non disponibile">N.d.</abbr>';
}

$animalRepo = new AnimalRepository();
$allAnimals = $animalRepo->All();
$featuredAnimals = array_slice($allAnimals, 0, 3);

$featuredAnimalsData = [];
if (!empty($featuredAnimals)) {
    foreach ($featuredAnimals as $animal) {
        if (!$animal instanceof Animal) {
            continue;
        }

        $featuredAnimalsData[] = [
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

// --- RENDERIZZAZIONE TEMPLATE ---
$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml(
    $currentFile, 
    $replacements,
    $featuredAnimalsData
);
echo $htmlContent;

?>