<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");



// Render base html
$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);




// Render animals list
$animalsList = Database::getAllAnimals(['id', 'specie', 'image', 'name']);
$htmlAnimalsList = "<div id='animalsList'>" . PHP_EOL;
foreach ($animalsList as $animal) {
    $htmlAnimalsList .= "<div class='animalItem'>" . PHP_EOL;
    $htmlAnimalsList .= "<img src='" . htmlspecialchars($animal['image']) . "' alt='" . htmlspecialchars($animal['name']) . "' />" . PHP_EOL;
    $htmlAnimalsList .= "<h2><a href='animal.php?id=" . htmlspecialchars($animal['id']) . "'>" . htmlspecialchars($animal['specie']) . "</a></h2>" . PHP_EOL . "</div>" . PHP_EOL;
}
$htmlAnimalsList .= "</div>";

$htmlContent = str_replace("[[animalsList]]", $htmlAnimalsList, $htmlContent);




// Render error messages
$errorMessages = [
    'invalid_id' => 'Non ci è chiaro dove vuoi andare, prova una delle pagine di questa lista.',
    'not_found' => 'L\'animale che cerchi sembra essere scappato. Scegline un\'altro dalla lista.',
];
// Checks if there are errors in the URL parameters
$htmlError = '';
if (isset($_GET['error']) && !empty($_GET['error'])) {
    $errorMsg = $errorMessages[$_GET['error']] ?? 'Errore sconosciuto.';
    $htmlError .= '<div class="error-message" role="alert" aria-live="assertive" tabindex="-1" id="error-notification">' . PHP_EOL;
    $htmlError .= '<svg aria-hidden="true" class="error-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>' . PHP_EOL;
    $htmlError .= '<span>' . htmlspecialchars($errorMsg) . '</span>' . PHP_EOL;
    $htmlError .= '</div>' . PHP_EOL;
    $htmlError .= '<script>document.getElementById("error-notification").focus();</script>' . PHP_EOL; // TODO: trova soluzione migliore per il focus
}

$htmlContent = str_replace("[[errorMessage]]", $htmlError, $htmlContent);

echo $htmlContent;

?>

