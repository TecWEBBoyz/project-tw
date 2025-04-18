<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");

if (!isset($_GET['id']) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $_GET['id'])) {
    header("Location: animals.php?error=invalid_id");
    exit();
}

$result = Database::getAnimalByID($_GET['id']);
if (!$result || empty($result)) {
    header("Location: animals.php?error=not_found");
    exit();
}

$animal = $result[0];

$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile, [
    "[[specie]]" => htmlspecialchars($animal['specie'] ?? ''),
    "[[name]]" => htmlspecialchars($animal['name'] ?? ''),
    "[[age]]" => htmlspecialchars($animal['age'] ?? ''),
    "[[habitat]]" => htmlspecialchars($animal['habitat'] ?? ''),
    "[[dimensions]]" => htmlspecialchars($animal['dimensions'] ?? ''),
    "[[lifespan]]" => htmlspecialchars($animal['lifespan'] ?? ''),
    "[[diet]]" => htmlspecialchars($animal['diet'] ?? ''),
    "[[description]]" => htmlspecialchars($animal['description'] ?? ''),
    "[[image]]" => htmlspecialchars($animal['image'] ?? '')
]);

echo $htmlContent;

?>