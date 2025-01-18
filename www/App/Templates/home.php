<?php

use PTW\Models\ImageType;

$images = $TEMPLATE_DATA['images'] ?? [];

$categories = [];

// Raggruppa le immagini per categoria
foreach ($images as $image) {
    $imageArray = $image->ToArray();
    $category = isset($imageArray[ImageType::category->value]) ? $imageArray[ImageType::category->value] : 'Uncategorized';

    if (!isset($categories[$category])) {
        $categories[$category] = [];
    }

    $categories[$category][] = $imageArray;
}

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

<!-- Navigazione per categorie -->
<div class="categories-navigation">
    <ul>
        <?php foreach (array_keys($categories) as $category): ?>
            <li><a href="#<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
                </a></li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if (empty($categories)) { ?>
    <p>No images found!</p>
<?php } ?>

<!-- Gallerie per ogni categoria -->
<?php foreach ($categories as $category => $imagesInCategory): ?>
    <div class="gallery-category" id="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
        <h2><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?></h2>
        <div class="gallery">
            <?php foreach ($imagesInCategory as $image): ?>

                <?php
                $detailPage = "gallerydetails?src=" . (isset($image[ImageType::path->value]) ? urlencode($image[ImageType::path->value]) : '') .
                    "&description=" . (isset($image[ImageType::description->value]) ? urlencode($image[ImageType::description->value]) : '') .
                    "&location=" . (isset($image[ImageType::place->value]) ? urlencode($image[ImageType::place->value]) : '') .
                    "&alt=" . (isset($image[ImageType::alt->value]) ? urlencode($image[ImageType::alt->value]) : '') .
                    "&date=" . (isset($image[ImageType::date->value]) ? urlencode($image[ImageType::date->value]) : '');

                $imagePathResized = isset($image[ImageType::path->value]) ?
                    preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $image[ImageType::path->value]) : '';

                $date = isset($image[ImageType::date->value]) ? htmlspecialchars($image[ImageType::date->value], ENT_QUOTES, 'UTF-8') : 'Unknown date';
                $location = isset($image[ImageType::place->value]) && $date !== 'Unknown date' ? htmlspecialchars($image[ImageType::place->value], ENT_QUOTES, 'UTF-8') : 'Unknown location';
                ?>

                <div style="min-height: 150px;" class="gallery-item" data-description="<?php echo isset($image[ImageType::description->value]) ? htmlspecialchars($image[ImageType::description->value], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <a href="<?php echo htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="image-wrapper">
                            <img class="main-image" src="static/uploads/<?php echo htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo isset($image[ImageType::alt->value]) ? htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') : ''; ?>" loading="lazy" onload="imageLoaded(this)" onerror="imageError(this)">
                        </div>
                    </a>
                    <div class="info">
                        <span><?php echo isset($image[ImageType::title->value]) ? htmlspecialchars($image[ImageType::title->value], ENT_QUOTES, 'UTF-8') : 'Unknown title'; ?></span>
                        <span>Location: <?php echo $location; ?></span>
                        <span>Date: <?php echo $date; ?></span>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="modal" id="image-modal">
    <img src="" alt="Zoomed image" id="modal-image">
    <div class="modal-description" id="modal-description"></div>
    <!-- loader -->
    <div class="loader">
        <div class="spinner"></div>
    </div>
</div>
