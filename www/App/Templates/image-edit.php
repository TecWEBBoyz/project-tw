<?php

use PTW\Models\ImageType;

if (is_null($TEMPLATE_DATA["images"])) {
    echo "<p>" . PTW\translation('no-images') . "</p>";
    echo "<a href='admin/upload-form' class='button'>" . PTW\translation('upload-image') . "</a>";
    return;
}
$images = $TEMPLATE_DATA["images"] ?? [];
foreach ($images as $image) {
    $imageArray = $image->ToArray();
    $imagePathResized = isset($imageArray[ImageType::path->value]) ?
        preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $imageArray[ImageType::path->value]) : '';

    $title = htmlspecialchars($imageArray[ImageType::title->value] ?? '');
    $alt = htmlspecialchars($imageArray[ImageType::alt->value] ?? '');
    $description = htmlspecialchars($imageArray[ImageType::description->value] ?? '');
    $place = htmlspecialchars($imageArray[ImageType::place->value] ?? '');
    $date = htmlspecialchars($imageArray[ImageType::date->value] ?? '');
    $visible = $imageArray[ImageType::visible->value] ?? false;

    echo "<form action='admin/update-image' method='POST'>";
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($imageArray[ImageType::id->value]) . "'>";
    echo "<img src='static/uploads/{$imagePathResized}' alt='{$alt}' class='image'>";
    echo "<div class='form-group'>";
    echo "<label for='title' class='caption'>" . PTW\translation('image-title') . "</label>";
    echo "<input type='text' id='title' name='title' maxlength='255' value='{$title}' required />";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='alt' class='caption'>" . PTW\translation('image-alt') . "</label>";
    echo "<input type='text' id='alt' name='alt' value='{$alt}' required>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='description' class='caption'>" . PTW\translation('image-description') . "</label>";
    echo "<textarea id='description' name='description' maxlength='512' required>{$description}</textarea>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='place' class='caption'>" . PTW\translation('image-place') . "</label>";
    echo "<input type='text' id='place' name='place' maxlength='255' required value='{$place}' />";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='date' class='caption'>" . PTW\translation('image-date') . "</label>";
    echo "<input 
        type='date' 
        id='date' 
        name='date' 
        value='{$date}' 
        min='1900-01-01' 
        max='" . date('Y-m-d') . "' 
        required 
        aria-label='" . PTW\translation('image-date-description') . "' 
    />";
    echo "</div>";
    echo '<label for="visible" class="toggle-button-label">';
    echo '<span>' . PTW\translation('image-visibility') . ':</span>';
    echo '<input type="checkbox" id="visible" name="visible" value="true"' . ($visible ? ' checked' : '') . ' class="toggle-button-checkbox">';
    echo '<span class="toggle-button-slider" aria-hidden="true"></span>';
    echo '</label>';
    echo "<button class='btn-outlined btn-image-edit button' type='submit'>";
    echo "<span>" . PTW\translation('image-save') . "</span>";
    echo "</button>";
    echo "</form>";
    echo "<form action='admin/delete-image' method='POST'>";
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($imageArray[ImageType::id->value]) . "'>";
    echo "<button class='btn-outlined btn-image-edit button button-danger' type='submit'>";
    echo "<span>" . PTW\translation('image-delete') . "</span>";
    echo "</button>";
    echo "</form>";
}
if (empty($images)) {
    echo "<p>" . PTW\translation('image-edit-no-image-to-edit') . "</p>";
}
?>
