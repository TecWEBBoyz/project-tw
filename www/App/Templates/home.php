<?php

use PTW\Models\ImageType;
use function PTW\config;

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

    $repo = new \PTW\Modules\Repositories\ImageRepository();
    $images = $repo->All();

    foreach ($images as $image) {

        $image = $image->ToArray();

        $detailPage = "gallerydetails?src=" . urlencode($image[ImageType::path->value]) . "&description=" . urlencode($image[ImageType::description->value]). "&location=" . urlencode($image[ImageType::place->value]) . "&alt=" . urlencode($image[ImageType::alt->value]);
        #replace image extension with _25percent.jpg manage upper and lower case extensions
        $imagePathResized = preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $image[ImageType::path->value]);
        echo "<div class='gallery-item' data-description='" . htmlspecialchars($image[ImageType::description->value], ENT_QUOTES, 'UTF-8') . "'>
        <a href='" . htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8') . "'>
            <div class='image-wrapper'>
                <img class='main-image' src='static/uploads/" . htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') . "' loading='lazy'>
            </div>
        </a>
        <div class='info'>
            <span>" . htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') . "</span>
            <span>Location: " . htmlspecialchars($image[ImageType::place->value], ENT_QUOTES, 'UTF-8') . "</span>
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