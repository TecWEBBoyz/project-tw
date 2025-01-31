document.addEventListener("DOMContentLoaded", () => {
    let script = document.querySelector('script[data-template="home"]');
    if (!script) return;
    const images = document.querySelectorAll(".gallery-item img");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("modal-image");
    const modalDescription = document.getElementById("modal-description");
    const modalTitle = document.getElementById("modal-title");
    const loader = document.querySelector(".loader");
    const close_button = document.getElementById("close-modal");
    const body = document.body;

    let lastSelectedFocus;

    images.forEach(img => {
        img.addEventListener("click", (event) => {
            event.preventDefault();

            lastSelectedFocus = event.target.parentElement;
            const galleryItem = event.target.closest(".gallery-item");

            modalDescription.textContent = galleryItem.getAttribute("data-description");
            modalTitle.textContent = galleryItem.getAttribute("data-title");

            modal.setAttribute("aria-hidden", "false");

            modal.classList.add('visible');
            document.body.classList.add('no-scroll', 'no-interaction');

            let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');

            modalImage.onload = () => {
                close_button.focus();

                loader.classList.add("hidden");
            };

            modalImage.src = fullsizeImage;
        });

        img.parentElement.parentElement.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {

                event.preventDefault();

                lastSelectedFocus = event.target;
                const galleryItem = event.target.closest(".gallery-item");

                modalDescription.textContent = galleryItem.getAttribute("data-description");
                modalTitle.textContent = galleryItem.getAttribute("data-title");

                modal.setAttribute("aria-hidden", "false");

                modal.classList.add('visible');
                document.body.classList.add('no-scroll', 'no-interaction');

                let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');

                modalImage.onload = () => {
                    close_button.focus();

                    loader.classList.add("hidden");
                };

                modalImage.src = fullsizeImage;
            }
        })
    });

    close_button.addEventListener("click", () => {
        resetModal();
    });

    close_button.addEventListener("keydown", (event) => {

        if (event.key === "Enter") {
            event.preventDefault();
            resetModal();
        }

        if (event.key === "Tab" || event.key === "Tab" && event.shiftKey) {
            event.preventDefault();
        }


    });

    function resetModal() {
        modal.classList.remove("visible");
        modalImage.src = "";
        modalTitle.textContent = "";
        modalDescription.textContent = "";

        loader.classList.remove("hidden");
        body.classList.remove("no-interaction");
        body.classList.remove("no-scroll");

        lastSelectedFocus.focus();
    }

    window.imageError = (el) => {
        el.classList.add("error");
    }
});
