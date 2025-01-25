<?php

use PTW\Models\ImageType;

$images = $TEMPLATE_DATA['images'] ?? [];

$categories = [];

// Funzione per trovare il ratio più vicino agli standard
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
    $aspectRatio = '1'; // Default ratio in caso di errore

    if (file_exists($imagePath)) {
        $imageSize = getimagesize($imagePath);
        if ($imageSize && isset($imageSize[0], $imageSize[1])) {
            $aspectRatioValue = $imageSize[0] / $imageSize[1]; // Larghezza divisa per altezza
            $aspectRatio = getClosestAspectRatio($aspectRatioValue); // Ottieni l'aspect ratio standard più vicino
        }
    }

    $imageArray['aspect_ratio'] = $aspectRatio; // Aggiungi l'aspect ratio standard ai dati dell'immagine
    $categories[$category][] = $imageArray;
}

?>

<section class="hero-section">
    <div class="hero-text">
        <p class="caption">/photographer</p>
        <h1>Filippo Rizzato</h1>

        <div class="hero-quote">
            <p>Benvenuti nel mio mondo, dove fotografia significa catturare il crudo ordinario.</p>
        </div>
    </div>
    <div class="image-me"></div>
</section>


<nav class="categories-navigation">
    <h2>Latest Work</h2>
    <ul>
        <?php foreach (array_keys($categories) as $category): ?>
            <li id="<?php echo $category . "-element" ?>"><a href="#<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
                </a></li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php if (empty($categories)) { ?>
    <p>No images found!</p>
<?php } ?>

<!-- Gallerie per ogni categoria -->
<?php foreach ($categories as $category => $imagesInCategory): ?>
    <section class="gallery-category" id="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
        <header>
            <p class="caption">/photoshoot</p>
            <h3><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?></h3>
        </header>

        <ul class="gallery">
            <?php foreach ($imagesInCategory as $image): ?>

                <?php
                // ToDo(Luca): Teniamo una GET?
                $detailPage = "gallerydetails?src=" . (isset($image[ImageType::path->value]) ? urlencode($image[ImageType::path->value]) : '') .
                    "&description=" . (isset($image[ImageType::description->value]) ? urlencode($image[ImageType::description->value]) : '') .
                    "&location=" . (isset($image[ImageType::place->value]) ? urlencode($image[ImageType::place->value]) : '') .
                    "&alt=" . (isset($image[ImageType::alt->value]) ? urlencode($image[ImageType::alt->value]) : '') .
                    "&date=" . (isset($image[ImageType::date->value]) ? urlencode($image[ImageType::date->value]) : '');

                $imagePathResized = isset($image[ImageType::path->value]) ?
                    preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.jpg', $image[ImageType::path->value]) : '';

                $date = isset($image[ImageType::date->value]) ? htmlspecialchars($image[ImageType::date->value], ENT_QUOTES, 'UTF-8') : 'Unknown date';
                $location = isset($image[ImageType::place->value]) && $date !== 'Unknown date' ? htmlspecialchars($image[ImageType::place->value], ENT_QUOTES, 'UTF-8') : 'Unknown location';

                $aspectRatio = $image['aspect_ratio'];
                ?>

                <li class="gallery-item"
                     data-description="<?php echo isset($image[ImageType::description->value]) ? htmlspecialchars($image[ImageType::description->value], ENT_QUOTES, 'UTF-8') : ''; ?>"
                >
                    <a href="<?php echo htmlspecialchars($detailPage, ENT_QUOTES, 'UTF-8'); ?>">
                        <img class="gallery-item-image aspect-ratio-<?php echo str_replace('.', '-', str_replace('/', '-', $aspectRatio)); ?>"
                             src="static/uploads/<?php echo htmlspecialchars($imagePathResized, ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo isset($image[ImageType::alt->value]) ? htmlspecialchars($image[ImageType::alt->value], ENT_QUOTES, 'UTF-8') : ''; ?>"
                             loading="lazy"
                             onerror="window.imageError(this)">
                    </a>
                    <div class="gallery-item-info">
                        <p class="caption"><?php echo htmlspecialchars($location) . ", " . htmlspecialchars($date); ?></p>
                        <h4><?php echo isset($image[ImageType::title->value]) ? htmlspecialchars($image[ImageType::title->value], ENT_QUOTES, 'UTF-8') : 'Unknown title'; ?></h4>
                    </div>
                </li>

            <?php endforeach; ?>
        </ul>
    </section>
<?php endforeach; ?>


<div class="modal hidden" id="image-modal">
    <button class="close-button" id="close-modal">&times;</button>
    <img src="" id="modal-image" alt="">
    <div class="modal-description" id="modal-description"></div>
    <!-- loader -->
    <div class="loader">
        <div class="spinner"></div>
    </div>
</div>