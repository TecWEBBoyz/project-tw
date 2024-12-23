document.addEventListener("DOMContentLoaded", () => {
    const images = document.querySelectorAll(".gallery-item img");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("modal-image");
    const modalDescription = document.getElementById("modal-description");
    const loader = document.querySelector(".loader");

    images.forEach(img => {
        img.addEventListener("click", (event) => {
            event.preventDefault(); // Evita la navigazione per JavaScript abilitato
            const galleryItem = event.target.closest(".gallery-item");
            const description = galleryItem.getAttribute("data-description");

            modal.style.display = "flex";
            let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');
            modalImage.onload = () => {
                modalImage.style.display = "block";
                modalDescription.textContent = description;
                loader.style.display = "none";
            };
            modalImage.src = fullsizeImage;
            modalDescription.textContent = description;
        });
    });

    modal.addEventListener("click", () => {
        modal.style.display = "none";
        modalImage.src = "";
        modalDescription.textContent = "";
        loader.style.display = "flex";
    });
});