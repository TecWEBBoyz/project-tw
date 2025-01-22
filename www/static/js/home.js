
window.loadJS = () => {
    console.log("Loading home.js");
    const images = document.querySelectorAll(".gallery-item img");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("modal-image");
    const modalDescription = document.getElementById("modal-description");
    const loader = document.querySelector(".loader");
    const close_button = document.getElementById("close-modal");
    const body = document.body;
    images.forEach(img => {
        img.addEventListener("click", (event) => {
            event.preventDefault(); // Evita la navigazione per JavaScript abilitato
            const galleryItem = event.target.closest(".gallery-item");
            const description = "<p>" + galleryItem.getAttribute("data-description") + "</p>";

            modal.style.display = "flex";
            let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '_75percent.$1');
            modalImage.onload = () => {
                modalImage.style.display = "block";
                body.classList.add("no-scroll");
                modalImage.focus();
                modalDescription.innerHTML = description;
                loader.style.display = "none";
                document.body.classList.add("no-interaction");
                modalImage.focus();
            };
            modalImage.src = fullsizeImage;
            modalDescription.innerHTML = description;
        });
    });

    close_button.addEventListener("click", () => {
        modal.style.display = "none";
        modalImage.src = "";
        modalDescription.textContent = "";
        loader.style.display = "flex";
        document.body.classList.remove("no-interaction");
        document.body.setAttribute('aria-hidden', 'true');
        body.classList.remove("no-scroll");
    });
    window.imageLoaded = (el) => {
        el.classList.add("loaded");
    }
    window.imageError = (el) => {
        el.classList.add("error");
    }
}
document.addEventListener("DOMContentLoaded", () => {
    loadJS();
});
