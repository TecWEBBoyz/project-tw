window.loadJS = () => {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('image-upload');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
        dropZone.addEventListener(event, e => e.preventDefault());
        dropZone.addEventListener(event, e => e.stopPropagation());
    });

    dropZone.addEventListener('dragover', () => {
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', e => {
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        fileInput.files = files;
    });

    dropZone.addEventListener('click', () => fileInput.click());
}
document.addEventListener("DOMContentLoaded", () => {
    loadJS();
});
