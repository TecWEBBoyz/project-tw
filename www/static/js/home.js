document.addEventListener("DOMContentLoaded", () => {
    let script = document.querySelector('script[data-template="home"]');
    if (!script) return;
    const images = document.querySelectorAll(".gallery-item img");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("modal-image");
    const modalDescription = document.getElementById("modal-description");
    const loader = document.querySelector(".loader");
    const close_button = document.getElementById("close-modal");
    const body = document.body;

    let lastSelectedFocus;
    let zoomLevel = 1;
    let isZooming = false;
    let isDragging = false;
    let startX = 0;
    let startY = 0;
    let translateX = 0;
    let translateY = 0;

    images.forEach(img => {
        img.addEventListener("click", (event) => {
            event.preventDefault();

            lastSelectedFocus = event.target.parentElement;
            const galleryItem = event.target.closest(".gallery-item");
            const description = "<p>" + galleryItem.getAttribute("data-description") + "</p>";

            modal.classList.add("visible");
            let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');
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
                let fullsizeImage = img.src.replace(/_.*\.(jpg|jpeg|png)$/i, '.$1');
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
        resetModal();
    });
    close_button.addEventListener("keydown", (event) => {

        if (event.key === "Enter") {
            event.preventDefault();
            resetModal();
        }
    });

    const MAX_ZOOM_LEVEL = 3;

    modalImage.addEventListener("wheel", (event) => {
        event.preventDefault();
        isZooming = true;

        const scaleAmount = event.deltaY > 0 ? 0.9 : 1.1;
        zoomLevel = Math.min(MAX_ZOOM_LEVEL, Math.max(1, zoomLevel * scaleAmount));
        applyTransform();
    });

    modalImage.addEventListener("touchstart", (event) => {
        if (event.touches.length === 2) {
            isZooming = true;
            touchStartDistance = getTouchDistance(event.touches);
        } else if (event.touches.length === 1 && zoomLevel > 1) {
            isDragging = true;
            startX = event.touches[0].clientX - translateX;
            startY = event.touches[0].clientY - translateY;
            modalImage.style.cursor = "grabbing";
        }
    });

    modalImage.addEventListener("touchmove", (event) => {
        if (isZooming && event.touches.length === 2) {
            event.preventDefault();
            const currentDistance = getTouchDistance(event.touches);
            const scaleAmount = currentDistance / touchStartDistance;
            zoomLevel = Math.min(MAX_ZOOM_LEVEL, Math.max(1, zoomLevel * scaleAmount));
            applyTransform();
            touchStartDistance = currentDistance;
        } else if (isDragging && event.touches.length === 1) {
            event.preventDefault();
            translateX = event.touches[0].clientX - startX;
            translateY = event.touches[0].clientY - startY;
            applyTransform();
        }
    });

    modalImage.addEventListener("touchend", () => {
        isZooming = false;
        isDragging = false;
        modalImage.style.cursor = "grab";
    });

    document.addEventListener("mousedown", (event) => {
        event.preventDefault();
        if (zoomLevel > 1 && event.target === modalImage) {
            isDragging = true;
            startX = event.clientX - translateX;
            startY = event.clientY - translateY;
            modalImage.style.cursor = "grabbing";

            document.addEventListener("mousemove", handleMouseMove);
            document.addEventListener("mouseup", handleMouseUp);
        }
    });

    function handleMouseMove(event) {
        if (isDragging) {
            event.preventDefault();
            translateX = event.clientX - startX;
            translateY = event.clientY - startY;
            applyTransform();
        }
    }

    function handleMouseUp() {
        isDragging = false;
        modalImage.style.cursor = "grab";
        document.removeEventListener("mousemove", handleMouseMove);
        document.removeEventListener("mouseup", handleMouseUp);
    }

    document.addEventListener("mouseleave", () => {
        if (isDragging) {
            isDragging = false;
            modalImage.style.cursor = "grab";
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
        }
    });

    function applyTransform() {
        const rect = modalImage.getBoundingClientRect();
        const container = modalImage.parentElement.getBoundingClientRect();

        // Margine base (scalerà con lo zoom)
        const baseMargin = 50;
        const scaledMargin = baseMargin * zoomLevel;

        // Calcola le dimensioni effettive dell'immagine scalata
        const scaledWidth = rect.width * zoomLevel;
        const scaledHeight = rect.height * zoomLevel;

        // Calcola i limiti della traslazione
        const minX = container.width / 2 - scaledWidth / 2 + scaledMargin;
        const maxX = scaledWidth / 2 - container.width / 2 - scaledMargin;

        const minY = container.height / 2 - scaledHeight / 2 + scaledMargin;
        const maxY = scaledHeight / 2 - container.height / 2 - scaledMargin;

        // Se l'immagine è più piccola del contenitore, centrarla
        if (scaledWidth <= container.width - 2 * scaledMargin) {
            translateX = 0;
        } else {
            translateX = Math.min(maxX, Math.max(minX, translateX));
        }

        if (scaledHeight <= container.height - 2 * scaledMargin) {
            translateY = 0;
        } else {
            translateY = Math.min(maxY, Math.max(minY, translateY));
        }

        // Applica la trasformazione
        modalImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${zoomLevel})`;
    }

    function getTouchDistance(touches) {
        const dx = touches[0].clientX - touches[1].clientX;
        const dy = touches[0].clientY - touches[1].clientY;
        return Math.sqrt(dx * dx + dy * dy);
    }

    function resetModal() {
        modal.classList.remove("visible");
        modalImage.src = "";
        modalImage.style.transform = "scale(1) translate(0, 0)";
        zoomLevel = 1;
        translateX = 0;
        translateY = 0;
        modalDescription.textContent = "";

        loader.classList.remove("hidden");
        body.classList.remove("no-interaction");
        body.setAttribute('aria-hidden', 'true');
        body.classList.remove("no-scroll");

        lastSelectedFocus.focus();
    }

    window.imageError = (el) => {
        el.classList.add("error");
    }
});
