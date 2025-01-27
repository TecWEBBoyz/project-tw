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
        resetModal();
    });
    close_button.addEventListener("keydown", (event) => {

        if (event.key === "Enter") {
            event.preventDefault();
            resetModal();
        }
    });

    modalImage.addEventListener("wheel", (event) => {
        event.preventDefault();
        isZooming = true;

        const scaleAmount = event.deltaY > 0 ? 0.9 : 1.1;
        zoomLevel = Math.max(1, zoomLevel * scaleAmount);
        modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
    });

    modalImage.addEventListener("gesturestart", (event) => {
        event.preventDefault();
        isZooming = true;
    });

    modalImage.addEventListener("gesturechange", (event) => {
        event.preventDefault();

        zoomLevel = Math.max(1, zoomLevel * event.scale);
        modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
    });

    modalImage.addEventListener("gestureend", () => {
        isZooming = false;
    });

    document.addEventListener("mousedown", (event) => {
        if (zoomLevel > 1 && event.target === modalImage) {
            isDragging = true;
            startX = event.clientX - translateX;
            startY = event.clientY - translateY;
            modalImage.style.cursor = "grabbing";

            document.addEventListener("mousemove", handleMouseMove);
            document.addEventListener("drag", handleMouseMove);
            document.addEventListener("mouseup", handleMouseUp);
            document.addEventListener("dragend", handleMouseUp);
        }
    });
    function handleMouseMove(event) {
        console.log("mousemove");
        if (isDragging) {
            event.preventDefault();
            translateX = event.clientX - startX;
            translateY = event.clientY - startY;
            modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
        }
    }

    function handleMouseUp(event) {
        console.log("mouseup");
        if (isDragging) {
            isDragging = false;
            modalImage.style.cursor = "grab";
            translateX = event.clientX - startX;
            translateY = event.clientY - startY;
            modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("drag", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
            document.removeEventListener("dragend", handleMouseUp);

        }
    }

    document.addEventListener("mouseleave", () => {
        console.log("mouseleave");
        if (isDragging) {
            isDragging = false;
            modalImage.style.cursor = "grab";
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("drag", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
            document.removeEventListener("dragend", handleMouseUp);
        }
    });

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
}
document.addEventListener("DOMContentLoaded", () => {
    loadJS();
});
