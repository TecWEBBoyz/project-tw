<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Path</th>
        <th>Alt</th>
        <th>Description</th>
        <th>Title</th>
        <th>Place</th>
        <th>Date</th>
        <th>Visible</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $images = $TEMPLATE_DATA['images'] ?? [];
    foreach ($images as $image) {
        $imageArray = $image->ToArray();
        if (is_null($imageArray)) {
            continue;
        }
        $id = htmlspecialchars($imageArray['id'], ENT_QUOTES, 'UTF-8');
        $path = htmlspecialchars($imageArray['path'], ENT_QUOTES, 'UTF-8');
        $alt = htmlspecialchars($imageArray['alt'] ?? "No alt", ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($imageArray['description'] ?? "No description", ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($imageArray['title'] ?? "No title", ENT_QUOTES, 'UTF-8');
        $place = htmlspecialchars($imageArray['place'] ?? "No place", ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($imageArray['date'] ?? "", ENT_QUOTES, 'UTF-8');
        $visible = $imageArray['visible'] ? 'Yes' : 'No';

        echo "<tr>
                    <td>$id</td>
                    <td>$path</td>
                    <td>$alt</td>
                    <td>$description</td>
                    <td>$title</td>
                    <td>$place</td>
                    <td>$date</td>
                    <td>$visible</td>
                    <td>
                        <form action='admin/edit-image-visibility' method='POST' class='form-inline'>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' aria-label='Toggle Visibility'>
                                <i class='fas fa-eye'></i>
                            </button>
                        </form>
                        <form action='admin/edit-image' method='GET' class='form-inline'>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' aria-label='Edit Image'>
                                <i class='fas fa-edit'></i>
                            </button>
                        </form>
                        <form action='admin/delete-image' method='POST' class='form-inline'>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' aria-label='Delete Image'>
                                <i class='fas fa-trash'></i>
                            </button>
                        </form>
                    </td>
                </tr>";
    }
    ?>
    </tbody>
</table>
