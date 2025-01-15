window.loadJS = () => {
    console.log("Loading admin.js");
    const forms = document.querySelectorAll('.confirm-form');
    const modal = document.getElementById('custom-confirm-modal');
    const modalMessage = document.getElementById('custom-modal-message');
    const confirmButton = document.getElementById('confirm-action');
    const cancelButton = document.getElementById('cancel-action');

    let currentForm = null;

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            currentForm = form;
            const actionType = form.getAttribute('data-action');
            modalMessage.textContent = actionType;
            modal.style.display = 'flex';
        });
    });

    confirmButton.addEventListener('click', function() {
        if (currentForm) {
            currentForm.submit();
        }
        modal.style.display = 'none';
    });

    cancelButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    loadJS();
});
