<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");


$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);

$animalsList = Database::getAllAnimals(['id', 'specie', 'image', 'name']);
$htmlAnimalsList = "<div id='animalsList'>" . PHP_EOL;
foreach ($animalsList as $animal) {
    $htmlAnimalsList .= "<div class='animalItem'>" . PHP_EOL;
    $htmlAnimalsList .= "<img src='" . htmlspecialchars($animal['image']) . "' alt='" . htmlspecialchars($animal['name']) . "' />" . PHP_EOL;
    $htmlAnimalsList .= "<h2><a href='animal.php?id=" . htmlspecialchars($animal['id']) . "'>" . htmlspecialchars($animal['specie']) . "</a></h2>" . PHP_EOL . "</div>" . PHP_EOL;
}
$htmlAnimalsList .= "</div>";

$htmlContent = str_replace("[[animalsList]]", $htmlAnimalsList, $htmlContent);

echo $htmlContent;

?>

