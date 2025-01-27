window.loadJS = () => {
    console.log("Loading home.js");
    const images = document.querySelectorAll(".gallery-item img");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("modal-image");
    const modalDescription = document.getElementById("modal-description");
    const loader = document.querySelector(".loader");
    const close_button = document.getElementById("close-modal");
    const body = document.body;

    let lastSelectedFocus;

    /* ToDo(Luca) Codice Duplicato */
    images.forEach(img => {
        img.addEventListener("click", (event) => {
            event.preventDefault();

            lastSelectedFocus = event.target.parentElement;
            const galleryItem = event.target.closest(".gallery-item");
            const description = "<p>" + galleryItem.getAttribute("data-description") + "</p>";

            modal.classList.add("visible");
            let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '_75percent.$1');
            modalImage.onload = () => {
                body.classList.add("no-scroll");
                body.classList.add("no-interaction");

                close_button.focus();

                loader.classList.add("hidden");
            };
            modalImage.src = fullsizeImage;
            modalDescription.innerHTML = description;
        });

        img.parentElement.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();

                lastSelectedFocus = event.target;
                const galleryItem = event.target.closest(".gallery-item");
                const description = "<p>" + galleryItem.getAttribute("data-description") + "</p>";

                modal.classList.add("visible");
                let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '_75percent.$1');
                modalImage.onload = () => {

                    body.classList.add("no-scroll");
                    body.classList.add("no-interaction");

                    close_button.focus();

                    loader.classList.add("hidden");
                };
                modalImage.src = fullsizeImage;
                modalDescription.innerHTML = description;
            }
        })
    });

    close_button.addEventListener("click", () => {
        modal.classList.remove("visible");
        modalImage.src = "";
        modalDescription.textContent = "";

        loader.classList.remove("hidden");
        body.classList.remove("no-interaction");
        body.setAttribute('aria-hidden', 'true');
        body.classList.remove("no-scroll");

        lastSelectedFocus.focus();
    });
    close_button.addEventListener("keydown", (event) => {

        if (event.key === "Enter") {
            event.preventDefault();

            modal.classList.remove("visible");
            modalImage.src = "";
            modalDescription.textContent = "";

            loader.classList.remove("hidden");
            body.classList.remove("no-interaction");
            body.setAttribute('aria-hidden', 'true');
            body.classList.remove("no-scroll");

            lastSelectedFocus.focus();
        }
    });
    window.imageError = (el) => {
        el.classList.add("error");
    }
}
document.addEventListener("DOMContentLoaded", () => {
    loadJS();
});
