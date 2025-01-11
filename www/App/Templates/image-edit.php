<?php


use PTW\Models\ImageType;

if(is_null($TEMPLATE_DATA["images"])) {
    echo "<p>Nessuna immagine da modificare.</p>";
    echo "<a href='admin/upload-form' class='button'>Carica Immagini</a>";
    return;
}
$images  = $TEMPLATE_DATA["images"] ?? [];
foreach ($images as $image) {
    $imageArray = $image->ToArray();
    echo "<form action='admin/update-image' method='POST'>";
    echo "<label for='id'>ID:</label>";
    echo "<input type='text' id='id' name='id' value='{$imageArray[ImageType::id->value]}' readonly>";
    echo "<label for='alt'>Alt:</label>";
    echo "<input type='text' id='alt' name='alt' value='{$imageArray[ImageType::alt->value]}' required>";
    echo "<label for='description'>Descrizione:</label>";
    echo "<textarea id='description' name='description' maxlength='512' required>{$imageArray[ImageType::description->value]}</textarea>";
    echo "<label for='title'>Titolo:</label>";
    echo "<input type='text' id='title' name='title' maxlength='255' required>{$imageArray[ImageType::title->value]}</input>";
    echo "<label for='place'>Luogo:</label>";
    echo "<input type='text' id='place' name='place' maxlength='255' required>{$imageArray[ImageType::place->value]}</input>";
    echo "<label for='date'>Data:</label>";
    echo "<input type='date' id='date' name='date' value='{$imageArray[ImageType::date->value]}'></input>";
    echo "<label for='visible'>Visibile:</label>";
    echo "<input type='checkbox' id='visible' name='visible' value='true'></input>";
    echo "<button type='submit' class='button'>Salva Modifiche</button>";
    echo "</form>";
    echo "<form action='delete-image' method='POST'>";
    echo "<input type='hidden' name='id' value='{$imageArray[ImageType::id->value]}'></input>";
    echo "<button type='submit' class='button button-danger'>Elimina</button>";
    echo "</form>";
}
if (empty($images)) {
    echo "<p>Nessuna da modificare.</p>";
}
?>