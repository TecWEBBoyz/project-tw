<?php

use PTW\Models\ImageType;

$images = $TEMPLATE_DATA['images'] ?? [];

?>

<noscript>
    <style>
        .gallery-item a {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .gallery-item img {
            cursor: default;
        }
    </style>
</noscript>
<div class="gallery">
    <?php

    foreach ($images as $image) {

        $image = $image->ToArray();

        $detailPage = "gallerydetails?src=" . (isset($image[ImageType::path->value]) ? urlencode($image[ImageType::path->value]) : '') .
            "&description=" . (isset($image[ImageType::description->value]) ? urlencode($image[ImageType::description->value]) : '') .
            "&location=" . (isset($image[ImageType::place->value]) ? urlencode($image[ImageType::place->value]) : '') .
            "&alt=" . (isset($image[ImageType::alt->value]) ? urlencode($image[ImageType::alt->value]) : '') .
            "&date=" . (isset($image[ImageType::date->value]) ? urlencode($image[ImageType::date->value]) : '');

        # Replace image extension with _25percent.jpg, manage upper and lower case extensions
        $imagePathResized = isset($image[ImageType::path->value]) ?
            preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $image[ImageType::path->value]) : '';

        # Add date handling
        $date = isset($image[ImageType::date->value]) ? htmlspecialchars($image[ImageType::date->value], ENT_QUOTES, 'UTF-8') : 'Unknown date';

        # Handle location based on date availability
        $location = isset($image[ImageType::place->value]) && $date !== 'Unknown date' ? htmlspecialchars($image[ImageType::place->value], ENT_QUOTES, 'UTF-8') : 'Unknown location';

        echo "<div class='gallery-item' data-description='" . (isset($image[ImageType::description->value]) ? htmlspecialchars($image[ImageType::description->value], ENT_QUOTES, 'UTF-8') : '') . "'>
        <a href='" . htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8') . "'>
            <div class='image-wrapper'>
                <img class='main-image' src='static/uploads/" . htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8') . "' alt='" . (isset($image[ImageType::alt->value]) ? htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') : '') . "' loading='lazy'>
            </div>
        </a>
        <div class='info'>
            <span>" . (isset($image[ImageType::title->value]) ? htmlspecialchars($image[ImageType::title->value], ENT_QUOTES, 'UTF-8') : 'Unknown title') . "</span>
            <span>Location: " . $location . "</span>
            <span>Date: " . $date . "</span>
        </div>
    </div>";
    }

    ?>
</div>

<div class="modal" id="image-modal">
    <img src="" alt="Zoomed image" id="modal-image">
    <div class="modal-description" id="modal-description"></div>
    <!-- loader -->
    <div class="loader">
        <div class="spinner"></div>
    </div>
</div>
