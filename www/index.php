<?php
require_once 'init.php';

use PTW\Services\TemplateService;
use PTW\Repositories\ReviewRepository;
use PTW\Repositories\UserRepository;
use PTW\Models\Review;
use PTW\Models\User;

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

// Riempi i segnaposto vuoti per evitare che rimangano non sostituiti
for ($i = $counter; $i <= 3; $i++) {
    $replacements['[[review' . $i . 'Comment]]'] = 'Nessuna recensione disponibile.';
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, $replacements);
echo $htmlContent;

?>