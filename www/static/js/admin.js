document.addEventListener("DOMContentLoaded", () => {
    let script = document.querySelector('script[data-template="admin"]');
    if (!script) return;
    const forms = document.querySelectorAll('.confirm-form');
    const modal = document.getElementById('custom-confirm-modal');
    const modalMessage = document.getElementById('custom-modal-message');
    const confirmButton = document.getElementById('confirm-action');
    const cancelButton = document.getElementById('cancel-action');

    let currentForm = null;
    let lastFocusedElement = null;

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            currentForm = form;
            lastFocusedElement = document.activeElement;

            modalMessage.textContent = form.getAttribute('data-action');
            modal.setAttribute("aria-hidden", "false");

            modal.classList.add('visible');
            document.body.classList.add('no-scroll');

            cancelButton.focus();

            if (confirmButton && cancelButton) {
                confirmButton.addEventListener('keydown', function (e) {
                    if (e.key === 'Tab' && !e.shiftKey) {
                        e.preventDefault();
                        cancelButton.focus();
                    }
                });

                cancelButton.addEventListener('keydown', function (e) {
                    if (e.key === 'Tab' && e.shiftKey) {
                        e.preventDefault();
                        confirmButton.focus();
                    }
                });
            }

        });
    });

    if(confirmButton){
        confirmButton.addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit();
            }

            modal.setAttribute("aria-hidden", "true");

            modal.classList.remove('visible');
            document.body.classList.remove('no-scroll');

            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        });
    }

    if (cancelButton){
        cancelButton.addEventListener('click', function() {
            modal.setAttribute("aria-hidden", "true");

            modal.classList.remove('visible');
            document.body.classList.remove('no-scroll');

            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        });
    }

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.setAttribute("aria-hidden", "true");

            modal.classList.remove('visible');
            document.body.classList.remove('no-scroll');

            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        }
    });
});
