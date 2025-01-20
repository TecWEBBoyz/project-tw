<?php
?>
<div id="upload-button-container">
    <a href="admin/upload" class="btn btn-primary"><?php echo \PTW\translation('upload-image') ?></a>
</div>

<div class="table-responsive">
    <?php if (empty($TEMPLATE_DATA['images'])): ?>
        <p><?php echo \PTW\translation('no-images') ?></p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th><?php echo \PTW\translation('image-id') ?></th>
                <th><?php echo \PTW\translation('image-alt') ?></th>
                <th><?php echo \PTW\translation('image-description') ?></th>
                <th><?php echo \PTW\translation('image-title') ?></th>
                <th><?php echo \PTW\translation('image-place') ?></th>
                <th><?php echo \PTW\translation('image-date') ?></th>
                <th><?php echo \PTW\translation('image-visibility') ?></th>
                <th><?php echo \PTW\translation('image-actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $images = $TEMPLATE_DATA['images'] ?? [];
            $index = 0;
            foreach ($images as $image) {
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


                // Todo(Luca): Rivedere, niente stili inline -> Fare tramite classi.
                $rowStyle = is_null($imageArray['updated_at'] ?? null)
                    ? " style='background-color: orange;' title='".\PTW\translation('image-require-edit')."'"
                    : "";

                echo "<tr$rowStyle id=\"$id\">
                        <td data-label='".\PTW\translation('image-id')."'>$index</td>
                        <td data-label='".\PTW\translation('image-alt')."'>$alt</td>
                        <td data-label='".\PTW\translation('image-description')."'>$description</td>
                        <td data-label='".\PTW\translation('image-title')."'>$title</td>
                        <td data-label='".\PTW\translation('image-place')."'>$place</td>
                        <td data-label='".\PTW\translation('image-date')."'>$date</td>
                        <td data-label='".\PTW\translation('image-visibility')."'>$visible</td>
                        <td data-label='".\PTW\translation('image-actions')."'>
                            <form action='admin/edit-image-visibility' method='POST' class='form-inline confirm-form' data-action='".str_replace("{ACTION}",\PTW\translation('image-toggle-visibility'), \PTW\translation('confirm-action'))."'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-toggle-visibility')."'>
                                    ".($imageArray['visible'] == '0' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>')."
                                </button>
                            </form>
                            <form action='admin/edit-image' method='GET' class='form-inline'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-edit')."'>
                                    <i class='fas fa-edit'></i>
                                </button>
                            </form>
                            <form action='admin/delete-image' method='POST' class='form-inline confirm-form' data-action='".str_replace("{ACTION}",\PTW\translation('image-delete'), \PTW\translation('confirm-action'))."'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-delete')."'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
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
