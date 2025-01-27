<?php
?>
<div id="upload-button-container">
    <a href="admin/upload" class="btn btn-primary"><?php echo \PTW\translation('upload-image') ?></a>
</div>

<div class="table-responsive">
    <?php if (empty($TEMPLATE_DATA['images'])): ?>
        <p><?php echo \PTW\translation('no-images') ?></p>
    <?php else: ?>
        <table id="admin-photo-list">
            <thead>
            <tr>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-id') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-alt') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-description') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-title') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-place') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-date') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-visibility') ?></th>
                <th scope="col"><?php echo \PTW\translationWithSpan('image-actions') ?></th>
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
                    ? " class='require-edit' title='".\PTW\translation('image-require-edit')."'"
                    : "";

                echo "<tr$rowStyle id=\"$id\">
                        <td data-label='".\PTW\translation('image-id')."'>$index</td>
                        <td data-label='".\PTW\translation('image-alt')."'>$alt</td>
                        <td data-label='".\PTW\translation('image-description')."'>$description</td>
                        <td data-label='".\PTW\translation('image-title')."'>$title</td>
                        <td data-label='".\PTW\translation('image-place')."'>$place</td>
                        <td data-label='".\PTW\translation('image-date')."'><time datetime='".$date."'>$date</time></td>
                        <td data-label='".\PTW\translation('image-visibility')."'>$visible</td>
                        <td data-label='".\PTW\translation('image-actions')."'>
                            <form action='admin/edit-image-visibility' method='POST' class='form-inline confirm-form' data-action='".str_replace("{ACTION}",\PTW\translation('image-toggle-visibility'), \PTW\translation('confirm-action'))."'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-toggle-visibility')."'>
                                    ".($imageArray['visible'] == '0' ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"/></svg>')."
                                </button>
                            </form>
                            <form action='admin/edit-image' method='GET' class='form-inline'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-edit')."'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z'/></svg>
                                </button>
                            </form>
                            <form action='admin/delete-image' method='POST' class='form-inline confirm-form' data-action='".str_replace("{ACTION}",\PTW\translation('image-delete'), \PTW\translation('confirm-action'))."'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' aria-label='".\PTW\translation('image-delete')."'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
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
