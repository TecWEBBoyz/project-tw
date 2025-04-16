<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");

//Checks if the id is set and is a UUID
if (!isset($_GET['id']) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $_GET['id'])) {
    header("Location: animals.php?error=invalid_id");
    exit();
}

$animal = Database::getAnimalByID($_GET['id']);
//Checks if the given id exists in the database
if (!$animal) {
    header("Location: animals.php?error=not_found");
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);

//Render the animal details ...

echo $htmlContent;

?>