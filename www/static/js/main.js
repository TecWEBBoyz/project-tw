document.addEventListener("DOMContentLoaded", () => {

    const scrollTopButton = document.getElementById('scrollTopButton');
    const images = document.querySelectorAll("img")

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopButton.classList.add('show');
        } else {
            scrollTopButton.classList.remove('show');
        }
    });

    scrollTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    images.forEach((img) => {
        if (img.complete) {
            img.parentElement.classList.add("loaded");
        } else {
            img.addEventListener("load", () => {
                img.parentElement.classList.add("loaded");
            });
        }
    })

    // Toast

    const toastContainer = document.getElementById("toast-container");
    const toastList = Array.from(toastContainer.children);

    if (toastList.length > 0) {
        const firstToast = toastList[0];
        const closeToastBtn = firstToast.querySelector(".toast-close");

        // Add focus to the first toast
        focusToast(firstToast, closeToastBtn);

        // Add click event to the close button
        if (closeToastBtn) {
            closeToastBtn.addEventListener("click", () => {
                firstToast.remove();
                focusNextToastOrRestore(toastList, toastContainer);
            });
        }

        setTimeout(() => {
            if (toastContainer.contains(firstToast)) {
                firstToast.remove();
                focusNextToastOrRestore(toastList, toastContainer);
            }
        }, 5000);

    }
});

function focusToast(toast, closeToastBtn) {
    // Add keyboard navigation to the toast
    toast.addEventListener("keydown", (e) => {
        if (e.key === "Tab") {
            e.preventDefault(); // Prevent default tab behavior
            closeToastBtn.focus(); // Move focus to the close button
        }
    });

    // Focus the toast itself
    toast.focus();
}

function focusNextToastOrRestore(toastList, toastContainer) {
    const updatedToastList = Array.from(toastContainer.children);

    if (updatedToastList.length > 0) {
        // Focus the next toast in line
        const nextToast = updatedToastList[0];
        const nextCloseBtn = nextToast.querySelector(".toast-close");
        focusToast(nextToast, nextCloseBtn);
    } else {
        // No toasts left, restore focus to the body or another element
        document.body.focus();
    }
}