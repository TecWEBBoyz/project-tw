<?php
?>
<a href="admin/upload" class="btn-outlined" id="upload-button">
    <span><?php echo \PTW\translation('upload-image') ?></span>
    <?php echo file_get_contents("static/images/right-chevron.svg") ?>
</a>

<div class="table-responsive">
    <?php if (empty($TEMPLATE_DATA['images'])): ?>
        <p><?php echo \PTW\translation('no-images') ?></p>
    <?php else: ?>
        <table id="admin-photo-list">
            <thead>
            <tr>
                <th scope="col"><?php echo \PTW\translation('image-id') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-title') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-description') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-place') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-category') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-date') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-visibility') ?></th>
                <th scope="col"><?php echo \PTW\translation('image-actions') ?></th>
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

                $rowStyle = is_null($imageArray['updated_at'] ?? null)
                    ? " class='require-edit' title='" . \PTW\translation('image-require-edit') . "'"
                    : "";
                ?>

                <tr <?php echo $rowStyle . 'id=\"$id\"' ?>>
                    <td data-label='<?php echo \PTW\translation(' image-id') ?>'><?php echo $index; ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-title') ?>'><?php echo $title; ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-description') ?>'
                        class="long-text"><?php echo $description ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-place') ?>'><?php echo $place; ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-category') ?>'><?php echo $category; ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-date') ?>'>
                        <time datetime='<?php echo $date ?>'><?php echo $date; ?></time>
                    </td>
                    <td data-label='<?php echo \PTW\translation(' image-visibility') ?>'><?php echo $visible; ?></td>
                    <td data-label='<?php echo \PTW\translation(' image-actions') ?>' class='actions'>

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
