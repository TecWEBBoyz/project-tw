<?php
?>
<a href="admin/upload" class="btn-outlined" id="upload-button">
    <span><?php use PTW\Models\ImageCategory;

        echo \PTW\translation('upload-image') ?></span>
    <?php echo file_get_contents("static/images/right-chevron.svg") ?>
</a>

<form method="GET" action="" id="category-filter-form">
    <div class="form-group">
        <p class="caption label"><?php echo \PTW\translation('image-filter-by-category'); ?></p>
        <ul class="category-buttons">
            <?php
            $index = 0;
            foreach (ImageCategory::cases() as $category):
                if ($category->value == ImageCategory::no_category->value) continue;
                $index++;
                $isSelected = isset($TEMPLATE_DATA['category']) && $TEMPLATE_DATA['category'] === $category->value;
                ?>
                <li>
                    <button type="submit" name="category" value="<?php echo htmlspecialchars($category->value, ENT_QUOTES, 'UTF-8'); ?>"
                            class="category-button <?php echo $isSelected ? 'selected' : ''; ?>">
                        <?php echo \PTW\translation('image-category-' . $index); ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</form>

<div class="table-responsive">
    <?php if (empty($TEMPLATE_DATA['images'])): ?>
        <p><?php echo \PTW\translation('no-images') ?></p>
    <?php else: ?>
        <table id="admin-photo-list">
            <thead>
            <tr>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-id') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-order'); ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-title') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-description') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-place') ?></th>
<!--                <th scope="col">--><?php //echo \PTW\translationWithSpan('image-category') ?><!--</th>-->
                <th scope="col"><?php echo \PTW\translationWithSpan('image-date') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-visibility') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $images = $TEMPLATE_DATA['images'] ?? [];
            $index = 0;
            foreach ($images as $image) :
                $index++;
                $imageArray = $image->ToArray();
                if (is_null($imageArray)) {
                    continue;
                }
                $id = htmlspecialchars($imageArray['id'], ENT_QUOTES, 'UTF-8');
                $alt = htmlspecialchars(!empty($imageArray['alt']) ? $imageArray['alt'] : PTW\translation('image-no-alt'), ENT_QUOTES, 'UTF-8');
                $description = htmlspecialchars(!empty($imageArray['description']) ? $imageArray['description'] : PTW\translation('image-no-description'), ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars(!empty($imageArray['title']) ? $imageArray['title'] : PTW\translation('image-no-title'), ENT_QUOTES, 'UTF-8');
                $place = htmlspecialchars(!empty($imageArray['place']) ? $imageArray['place'] : PTW\translation('image-no-place'), ENT_QUOTES, 'UTF-8');
                $date = htmlspecialchars($imageArray['date'] ?? PTW\translation('image-no-date'), ENT_QUOTES, 'UTF-8');
                $visible = $imageArray['visible'] ? PTW\translation('image-visibility-yes') : PTW\translation('image-visibility-no');
                $category = htmlspecialchars($imageArray['category'] ?? PTW\translation('image-no-category'), ENT_QUOTES, 'UTF-8');
                $order = htmlspecialchars($imageArray['order_id'] ?? PTW\translation('image-no-order'), ENT_QUOTES, 'UTF-8');
                $rowStyle = is_null($imageArray['updated_at'] ?? null)
                    ? " class='require-edit' title='" . \PTW\translation('image-require-edit') . "'"
                    : "";
                ?>

                <tr <?php echo $rowStyle ?> id="<?php echo $id ?>">

                    <td data-label='<?php echo \PTW\translation('image-id') ?>'><?php echo $order; ?></td>
                    <td data-label='<?php echo \PTW\translation('image-order') ?>' class='actions'>
                        <form action='admin/reorder-image' method='POST' class='form-inline'>
                            <input type='hidden' name='id' value='<?php echo $id; ?>'>
                            <input type='hidden' name='direction' value='up'>
                            <button type='submit' class="icon-button" aria-label="<?php echo \PTW\translation('move-up') ?>">
                                <?php echo file_get_contents("static/images/icons/arrow-up.svg"); ?>
                            </button>
                        </form>
                        <form action='admin/reorder-image' method='POST' class='form-inline'>
                            <input type='hidden' name='id' value='<?php echo $id; ?>'>
                            <input type='hidden' name='direction' value='down'>
                            <button type='submit' class="icon-button" aria-label="<?php echo \PTW\translation('move-down') ?>">
                                <?php echo file_get_contents("static/images/icons/arrow-down.svg"); ?>
                            </button>
                        </form>
                    </td>
                    <td data-label='<?php echo \PTW\translation('image-title') ?>'><?php echo $title; ?></td>
                    <td data-label='<?php echo \PTW\translation('image-description') ?>'
                        class="long-text"><?php echo $description ?></td>
                    <td data-label='<?php echo \PTW\translation('image-place') ?>'><?php echo $place; ?></td>
<!--                    <td data-label='--><?php //echo \PTW\translation('image-category') ?><!--'>--><?php //echo $category; ?><!--</td>-->
                    <td data-label='<?php echo \PTW\translation('image-date') ?>'>
                        <time datetime='<?php echo $date ?>'><?php echo $date; ?></time>
                    </td>
                    <td data-label='<?php echo \PTW\translation('image-visibility') ?>'><?php echo $visible; ?></td>
                    <td data-label='<?php echo \PTW\translation('image-actions') ?>' class='actions'>

                        <ul>
                            <li>
                                <form action='admin/edit-image-visibility' method='POST' class='form-inline confirm-form' data-action="<?php echo str_replace("{ACTION}",\PTW\translation('image-toggle-visibility'), \PTW\translation('confirm-action')) ?>">
                                    <input type='hidden' name='id' value='<?php echo $id;?>'>
                                    <button type='submit' aria-label="<?php echo \PTW\translation('image-toggle-visibility')?>" class="icon-button icon-button-primary">
                                        <?php echo ($imageArray['visible'] == '0' ? file_get_contents("static/images/eye-close.svg") : file_get_contents("static/images/eye-open.svg"));?>
                                        <span>Visibility</span>
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action='admin/edit-image' method='GET' class='form-inline'>
                                    <input type='hidden' name='id' value='<?php echo $id;?>'>
                                    <button type='submit' aria-label="<?php echo \PTW\translation('image-edit') ?>"
                                            class="icon-button icon-button-primary">
                                        <?php echo file_get_contents("static/images/edit.svg"); ?>
                                        <span>Edit</span>
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action='admin/delete-image' method='POST' class='form-inline confirm-form'
                                      data-action="<?php echo str_replace("{ACTION}", \PTW\translation('image-delete'), \PTW\translation('confirm-action')) ?>">
                                    <input type='hidden' name='id' value='<?php echo $id;?>'>
                                    <button type='submit' aria-label="<?php echo \PTW\translation('image-delete') ?>"
                                            class="icon-button icon-button-danger">
                                        <?php echo file_get_contents("static/images/delete.svg"); ?>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Custom Confirmation Modal -->
<div id="custom-confirm-modal" class="modal">
    <div class="modal-content">
        <p id="custom-modal-message"></p>
        <div class="modal-actions">
            <button id="cancel-action" class="btn btn-secondary"><?php echo \PTW\translation('cancel'); ?></button>
            <button id="confirm-action" class="btn btn-danger"><?php echo \PTW\translation('confirm'); ?></button>
        </div>
    </div>
</div>
