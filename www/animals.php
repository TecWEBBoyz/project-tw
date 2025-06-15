<?php
require_once 'init.php';

use PTW\Services\TemplateService;

$animalRepo = new \PTW\Repositories\AnimalRepository();
$animalsList = $animalRepo->All();

$animalsDataForTemplate = [];
foreach ($animalsList as $animal) {
    if (!$animal instanceof \PTW\Models\Animal) {
        continue;
    }

    $animalsDataForTemplate[] = [
        '[[id]]'          => htmlspecialchars($animal->getId()),
        '[[name]]'        => htmlspecialchars($animal->getName()),
        '[[species]]'     => htmlspecialchars($animal->getSpecies()),
        '[[habitat]]'     => htmlspecialchars($animal->getHabitat()),
        '[[image]]'       => htmlspecialchars($animal->getImage()),
        '[[description]]' => htmlspecialchars(trim(preg_split("/\./", $animal->getDescription())[0]) . "."),
        '[[alt_text]]'    => "Foto di " . htmlspecialchars($animal->getName()) . ", un esemplare di " . htmlspecialchars($animal->getSpecies()),
    ];
}

// Render error messages
$errorMessages = [
    'invalid_id' => 'Non ci è chiaro dove vuoi andare, prova una delle pagine di questa lista.',
    'not_found'  => 'L\'animale che cerchi sembra essere scappato. Scegline un\'altro dalla lista.',
];

$htmlError = '';
if (!empty($_GET['error'])) {
    $errorMsg = $errorMessages[$_GET['error']] ?? 'Errore sconosciuto.';
    $htmlError .= '<div class="error-message" role="alert" aria-live="assertive" tabindex="-1" id="error-notification">' . PHP_EOL;
    $htmlError .= '<svg aria-hidden="true" class="error-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>' . PHP_EOL;
    $htmlError .= '<span>' . htmlspecialchars($errorMsg) . '</span>' . PHP_EOL;
    $htmlError .= '</div>' . PHP_EOL;
    $htmlError .= '<script>document.getElementById("error-notification").focus();</script>' . PHP_EOL; // TODO: trova soluzione migliore per il focus
}


// Render base html using TemplateService with repeated blocks
$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml(
    $currentFile,
    [
        "[[errorMessage]]" => $htmlError, // Base replacements
    ],
    $animalsDataForTemplate // Repeated replacements
);
echo $htmlContent;

?>