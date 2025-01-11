const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('image-upload');

// Prevenire il comportamento di default per drag-and-drop
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
    dropZone.addEventListener(event, e => e.preventDefault());
    dropZone.addEventListener(event, e => e.stopPropagation());
});

// Aggiungere stile visivo per il dragover
dropZone.addEventListener('dragover', () => {
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', e => {
    dropZone.classList.remove('dragover');
    const files = e.dataTransfer.files;

    // Assegna i file all'input nascosto
    fileInput.files = files;
});

// Aprire il selettore file quando si clicca sul drop-zone
dropZone.addEventListener('click', () => fileInput.click());