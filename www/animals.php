<?php
require_once 'init.php';

use PTW\Services\TemplateService;

// Render animals list
$animalRepo = new \PTW\Repositories\AnimalRepository();

$animalsList = $animalRepo->All();

$htmlAnimalsList = "<ul class=\"animal-list\">" . PHP_EOL;

foreach ($animalsList as $animal) {
    if (!$animal instanceof \PTW\Models\Animal) {
        continue;
    }

    $htmlAnimalsList .= "<a href=\"animal.php?id=" . htmlspecialchars($animal->getId()) . "\">" . PHP_EOL;

    $htmlAnimalsList .= "<li class='animal-item' data-image-url=\"".htmlspecialchars($animal->getImage()) . "\">" . PHP_EOL;

    $htmlAnimalsList .= "<div class=\"animal-item-content\">" . PHP_EOL;
    $htmlAnimalsList .= "<p class=\"animal-item-caption\">" . htmlspecialchars($animal->getSpecies()) . ", " . htmlspecialchars($animal->getHabitat()) .  "</p>" . PHP_EOL;
    $htmlAnimalsList .= "<h3 class=\"animal-item-title\">" . htmlspecialchars($animal->getName()) .", " . htmlspecialchars($animal->getSpecies()) .  "</h3>" . PHP_EOL;
    $htmlAnimalsList .= "<p class=\"animal-item-description\">" . htmlspecialchars(trim(preg_split("/\./", $animal->getDescription())[0]) . ".") . "</p>" . PHP_EOL;

    // ToDo(Luca): Capire come mostrare il link per scoprire di più
    // $htmlAnimalsList .= "<p class='learn-more'>Clicca per scoprire di più</p>" . PHP_EOL;

    $htmlAnimalsList .= "</div></li></a>" . PHP_EOL;
}
$htmlAnimalsList .= "</ul>";

// Render error messages
$errorMessages = [
    'invalid_id' => 'Non ci è chiaro dove vuoi andare, prova una delle pagine di questa lista.',
    'not_found' => 'L\'animale che cerchi sembra essere scappato. Scegline un\'altro dalla lista.',
];

// Checks if there are errors in the URL parameters
$htmlError = '';
if (!empty($_GET['error'])) {
    $errorMsg = $errorMessages[$_GET['error']] ?? 'Errore sconosciuto.';
    $htmlError .= '<div class="error-message" role="alert" aria-live="assertive" tabindex="-1" id="error-notification">' . PHP_EOL;
    $htmlError .= '<svg aria-hidden="true" class="error-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>' . PHP_EOL;
    $htmlError .= '<span>' . htmlspecialchars($errorMsg) . '</span>' . PHP_EOL;
    $htmlError .= '</div>' . PHP_EOL;
    $htmlError .= '<script>document.getElementById("error-notification").focus();</script>' . PHP_EOL; // TODO: trova soluzione migliore per il focus
}

// Render base html
$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[animalsList]]" => $htmlAnimalsList,
    "[[errorMessage]]" => $htmlError,
]);
echo $htmlContent;

?>