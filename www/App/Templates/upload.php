<form action="admin/upload" method="POST" enctype="multipart/form-data">
    <div id="drop-zone">
        Trascina le immagini qui oppure
        <label for="image-upload" style="color: #009578; cursor: pointer;">selezionale</label>.
        <input type="file" id="image-upload" name="images[]" accept="image/*" multiple required>
    </div>
    <button type="submit">Carica</button>
</form>
