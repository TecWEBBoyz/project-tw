<?php

use PTW\Models\ImageCategory;
use PTW\Models\ImageType;

$images = $TEMPLATE_DATA['images'] ?? [];

$categories = [];

// Funzione per trovare il ratio più vicino agli standard
function getClosestAspectRatio($ratio)
{
    $standardRatios = [
        '1/1'   => 1,
        '4/3'   => 4 / 3,
        '3/2'   => 3 / 2,
        '16/9'  => 16 / 9,
        '21/9'  => 21 / 9,
        '9/16'  => 9 / 16,
        '5/4'   => 5 / 4,
        '2/1'   => 2 / 1,
        '3/1'   => 3 / 1,
        '1.85/1' => 1.85 / 1, // Cinema standard
        '2.39/1' => 2.39 / 1, // Cinema widescreen
        '1.91/1' => 1.91 / 1, // Social media (Instagram/Twitter landscape)
        '4/5'   => 4 / 5,     // Instagram portrait
        '3/4'   => 3 / 4,     // Mobile portrait
        '2/3'   => 2 / 3,     // Photography portrait
    ];

    $closest = '1/1';
    $smallestDifference = PHP_FLOAT_MAX;

    foreach ($standardRatios as $label => $value) {
        $difference = abs($ratio - $value);
        if ($difference < $smallestDifference) {
            $smallestDifference = $difference;
            $closest = $label;
        }
    }

    // Se il ratio calcolato è molto vicino a 1:1, scartarlo come opzione predefinita
    $tolerance = 0.05;
    if (abs($ratio - 1) < $tolerance && $closest !== '1:1') {
        return $closest; // Ritorna l'altro rapporto più vicino
    }

    return $closest;
}


// Raggruppa le immagini per categoria
foreach ($images as $image) {
    $imageArray = $image->ToArray();
    $category = isset($imageArray[ImageType::category->value]) ? $imageArray[ImageType::category->value] : 'Uncategorized';

    if (!isset($categories[$category])) {
        $categories[$category] = [];
    }

    // Calcola l'aspect ratio dell'immagine
    $imagePath = isset($imageArray[ImageType::path->value]) ? 'static/uploads/' . $imageArray[ImageType::path->value] : '';
    $ext = strtolower(pathinfo($imageArray[ImageType::path->value], PATHINFO_EXTENSION));
    $imagePath = isset($imagePath) ?
        preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.'.$ext.'', $imagePath) : '';
    $aspectRatio = '1'; // Default ratio in caso di errore

    if (file_exists($imagePath)) {
        $imageSize = getimagesize($imagePath);
        if ($imageSize && isset($imageSize[0], $imageSize[1])) {
            $aspectRatioValue = $imageSize[0] / $imageSize[1]; // Larghezza divisa per altezza
            $imageArray['aspect_ratio_best'] = $aspectRatioValue;
            $aspectRatio = getClosestAspectRatio($aspectRatioValue); // Ottieni l'aspect ratio standard più vicino
        }
    }

    $imageArray['aspect_ratio'] = $aspectRatio; // Aggiungi l'aspect ratio standard ai dati dell'immagine
    $categories[$category][] = $imageArray;
}

?>

<section class="hero-section">
    <div class="hero-text">
        <p class="caption"><?php echo \PTW\translationWithSpan('home-caption'); ?></p>
        <h1><?php echo \PTW\translationWithSpan('home-title'); ?></h1>

        <div class="hero-quote">
            <p><?php echo \PTW\translationWithSpan('home-quote'); ?></p>
        </div>
    </div>
    <div class="image-me"></div>
</section>


<nav class="categories-navigation">
    <h2><?php echo \PTW\translationWithSpan('home-latest-work'); ?></h2>
    <ul>
        <?php
        $index = 0;
        foreach (ImageCategory::cases() as $category):
            if ($category->value == ImageCategory::no_category->value) continue;
            $index++;
            $categoryName = PTW\translation('image-category-' . $index);
            ?>
            <li id="<?php echo $category->value . "-element" ?>">
                <a class="category-navigation-link" href="#<?php echo htmlspecialchars($category->value, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</nav>

<?php if (empty($categories)) { ?>
    <p><?php echo \PTW\translationWithSpan('home-images-not-found') ?></p>
<?php } ?>

<!-- Gallerie per ogni categoria -->
<?php
$index = 0;
foreach (ImageCategory::cases() as $category_):
    if ($category_->value == ImageCategory::no_category->value) continue;
    $category = $category_->value;
    $imagesInCategory = $categories[$category] ?? [];
    ?>
    <section class="gallery-category" id="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
        <header>
            <p class="caption"><?php echo \PTW\translationWithSpan('home-photoshoot-caption'); ?></p>
            <h3><?php echo \PTW\translation('image-category-' . ++$index); ?></h3>
        </header>

        <ul class="gallery">
            <?php foreach ($imagesInCategory as $image): ?>

                <?php
                // ToDo(Luca): Teniamo una GET?
                $ext = strtolower(pathinfo($image[ImageType::path->value], PATHINFO_EXTENSION));
                $imagePathResized = isset($image[ImageType::path->value]) ?
                    preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.'.$ext.'', $image[ImageType::path->value]) : '';
                $detailPage = "gallerydetails?src=" . (isset($image[ImageType::path->value]) ? urlencode($imagePathResized) : '') .
                    "&description=" . (isset($image[ImageType::description->value]) ? urlencode($image[ImageType::description->value]) : '') .
                    "&location=" . (isset($image[ImageType::place->value]) ? urlencode($image[ImageType::place->value]) : '') .
                    "&alt=" . (isset($image[ImageType::alt->value]) ? urlencode($image[ImageType::alt->value]) : '') .
                    "&date=" . (isset($image[ImageType::date->value]) ? urlencode($image[ImageType::date->value]) : '');



                $date = isset($image[ImageType::date->value]) ? htmlspecialchars($image[ImageType::date->value], ENT_QUOTES, 'UTF-8') : \PTW\translationWithSpan('image-unknown-date');
                $location = isset($image[ImageType::place->value]) && $date !== \PTW\translationWithSpan('image-unknown-date') ? htmlspecialchars($image[ImageType::place->value], ENT_QUOTES, 'UTF-8') : \PTW\translationWithSpan('image-unknown-location');

                $aspectRatio = $image['aspect_ratio'];
                ?>

                <li class="gallery-item"
                     data-description="<?php echo isset($image[ImageType::description->value]) ? htmlspecialchars($image[ImageType::description->value], ENT_QUOTES, 'UTF-8') : ''; ?>"
                     data-title="<?php echo isset($image[ImageType::title->value]) ? htmlspecialchars($image[ImageType::title->value], ENT_QUOTES, 'UTF-8') : ''; ?>"
                >
                    <a href="<?php echo htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8'); ?>">
                        <img
                            class="gallery-item-image aspect-ratio-<?php echo str_replace('.', '-', str_replace('/', '-', $aspectRatio)); ?>"
                             src="static/uploads/<?php echo htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo isset($image[ImageType::alt->value]) ? htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') : ''; ?>"
                             loading="lazy"
                             onerror="window.imageError(this)">
                    </a>
                    <div class="gallery-item-info">
                        <p class="caption"><?php echo htmlspecialchars($location);?>, <time datetime="<?php echo $date ?>"><?php echo \PTW\Utility\DateFormatterUtility::Format($date)?></time></p>
                        <h4><?php echo $image[ImageType::title->value] ?? 'Unknown title'; ?></h4>
                    </div>
                </li>

            <?php endforeach; ?>
        </ul>
    </section>
<?php endforeach; ?>

<!-- Custom Confirmation Modal -->
<div id="image-modal" class="modal home-modal" role="dialog" aria-labelledby="modal-title" aria-describedby="modal-description" aria-hidden="true">
    <button class="close-button" id="close-modal" aria-label="<?php echo \PTW\translation("home-close-btn"); ?>">&times;</button>

    <div class="modal-content">
        <img src="" id="modal-image" alt="">
        <div>
            <h2 id="modal-title"></h2>
            <p id="modal-description"></p>
        </div>
    </div>

    <div class="loader">
        <div class="spinner"></div>
    </div>
</div>