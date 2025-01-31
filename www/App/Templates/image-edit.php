<?php

use PTW\Models\ImageCategory;
use PTW\Models\ImageType;
use function PTW\dd;

$errors = $TEMPLATE_DATA['error'] ?? [];
$fields = $TEMPLATE_DATA['form_fields'] ?? [];

$id = $fields[ImageType::id->value] ?? '';
$title = htmlspecialchars($fields[ImageType::title->value] ?? '');
$alt = htmlspecialchars($fields[ImageType::alt->value] ?? '');
$description = htmlspecialchars($fields[ImageType::description->value] ?? '');
$place = htmlspecialchars($fields[ImageType::place->value] ?? '');
$date = htmlspecialchars($fields[ImageType::date->value] ?? '');
$visible = $fields[ImageType::visible->value] ?? false;
$actionType = $TEMPLATE_DATA['action_type'] ?? 'single-image';

if (is_null($TEMPLATE_DATA["images"])) {
    echo "<p>" . PTW\translation('no-images') . "</p>";
    echo "<a href='admin/upload-form' class='button'>" . PTW\translation('upload-image') . "</a>";
    return;
}

$images = $TEMPLATE_DATA["images"] ?? [];
foreach ($images as $image) :
    $imageArray = $image->ToArray();
    $ext = strtolower(pathinfo($imageArray[ImageType::path->value], PATHINFO_EXTENSION));
    $imagePathResized = isset($imageArray[ImageType::path->value]) ?
        preg_replace('/\.(jpg|jpeg|png)$/i', '_25percent.'.$ext.'', $imageArray[ImageType::path->value]) : '';

    $id = htmlspecialchars($imageArray[ImageType::id->value] ?? $id);
    $title = htmlspecialchars($imageArray[ImageType::title->value] ?? $title);
    $alt = htmlspecialchars($imageArray[ImageType::alt->value] ?? $alt);
    $description = htmlspecialchars($imageArray[ImageType::description->value] ?? $description);
    $place = htmlspecialchars($imageArray[ImageType::place->value] ?? $place);
    $date = htmlspecialchars($imageArray[ImageType::date->value] ?? $date);
    $visible = $imageArray[ImageType::visible->value] ?? $visible;

    ?>

    <img src='<?php echo "static/uploads/{$imagePathResized}" ?>' alt="<?php echo "{$alt}"; ?>" class='image' loading='lazy'>

    <form action='admin/update-image' method='POST'>
        <input type='hidden' name='id' value="<?php echo $id ?>">
        <input type='hidden' name='action-type' value="<?php echo $actionType; ?>">
        <div>
            <?php if (array_keys($errors) === ['form']) : ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['form'] ?></p>
                </div>
            <?php endif; ?>

            <div class='form-group'>
                <?php if (array_keys($errors) === ['title']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['title'] ?></p>
                    </div>
                <?php endif; ?>

                <label for='title' class='caption'><?php echo PTW\translation('image-title') ?></label>
                <input type='text' id='title' name='title' maxlength='255' value="<?php echo "{$title}" ?>" required/>
            </div>
            <div class='form-group'>
                <?php if (array_keys($errors) === ['alt']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['alt'] ?></p>
                    </div>
                <?php endif; ?>

                <label for='alt' class='caption'><?php echo PTW\translation('image-alt') ?></label>
                <input type='text' id='alt' name='alt' value="<?php echo "{$alt}" ?>" required>
            </div>
            <div class='form-group'>
                <?php if (array_keys($errors) === ['description']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['description'] ?></p>
                    </div>
                <?php endif; ?>

                <label for='description' class='caption'><?php echo PTW\translation('image-description') ?></label>
                <textarea id='description' name='description' maxlength='512'
                          required><?php echo "{$description}" ?></textarea>
            </div>

            <div class="form-group">
                <?php if (array_keys($errors) === ['category']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['category'] ?></p>
                    </div>
                <?php endif; ?>

                <label for='category' class='caption'><?php echo PTW\translation('image-category') ?></label>
                <select id='category' name='category' required>
                    <?php
                    $index = -1;
                    foreach (ImageCategory::cases() as $category) {
                        $index++;
                        echo "<option value='{$category->value}'" . ($category->value == $imageArray[ImageType::category->value] ? ' selected' : '') . ">" . PTW\translation('image-category-' . $index) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class='form-group'>
                <?php if (array_keys($errors) === ['place']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['place'] ?></p>
                    </div>
                <?php endif; ?>

                <label for='place' class='caption'><?php echo PTW\translation('image-place') ?></label>
                <input type='text' id='place' name='place' maxlength='255' required value="<?php echo "{$place}" ?>"/>
            </div>
            <div class='form-group'>
                <?php if (array_keys($errors) === ['caption']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['caption'] ?></p>
                    </div>
                <?php endif; ?>

                <label class='caption' for='date'>Data</label>
                <input type='date' class='custom-date-input' id='date' name='date' value="<?php echo "{$date}"; ?>" required>
            </div>
            <div class='form-group'>
                <?php if (array_keys($errors) === ['visible']) : ?>
                    <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                        <p><?php echo $errors['visible'] ?></p>
                    </div>
                <?php endif; ?>

                <label for="visible" class="toggle-button-label">
                    <span><?php echo PTW\translation('image-visibility') ?>:</span>
                    <input type="checkbox" id="visible" name="visible"
                           <?php echo($visible ? ' checked' : '') ?>
                           class="toggle-button-checkbox">
                    <span class="toggle-button-slider" aria-hidden="true"></span>
                </label>
            </div>
        </div>
        <button class='btn-outlined btn-image-edit' type='submit'>
            <span><?php echo PTW\translation('image-save') ?></span>
            <?php echo file_get_contents("static/images/paper-plane.svg") ?>
        </button>
    </form>

    <form action='admin/delete-image' method='POST'>
        <input type='hidden' name='id' value="<?php echo $id ?>">
        <button class='btn-outlined btn-image-edit btn-danger' type='submit'>
            <span><?php echo PTW\translation('image-delete') ?></span>
            <?php echo file_get_contents("static/images/delete.svg") ?>
        </button>
    </form>

<?php endforeach; ?>

<?php if (empty($images)) : ?>
    <p><?php echo PTW\translation('image-edit-no-image-to-edit') ?></p>
<?php endif; ?>
