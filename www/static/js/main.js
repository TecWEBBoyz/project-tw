window.loadersOnImages = () => {

    const images = document.querySelectorAll("img");

    images.forEach((img) => {
        const wrapper = document.createElement("div");
        wrapper.className = "image-wrapper";

        img.parentNode.insertBefore(wrapper, img);
        wrapper.appendChild(img);

        if (img.complete) {
            img.parentElement.classList.add("loaded");
        } else {
            img.addEventListener("load", () => {
                setTimeout(() => {
                    wrapper.classList.add("loaded");
                }, 250);
            });
        }
    });
};

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
    if (scrollTopButton){
        scrollTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Category navigation
    const categoryButtons = document.getElementsByClassName("category-navigation-link");

    for(const category of categoryButtons) {
        category.addEventListener("click", (e) => {
            e.preventDefault();

            const id = e.target.getAttribute("href").replace("#", "");
            const scrollToSection = document.getElementById(id);

            scrollToSection.scrollIntoView({behavior: "smooth"});
        });
    }

    // Toast
    const toastContainer = document.getElementById("toast-container");
    if(toastContainer) {
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
    }


    window.loadersOnImages();

    // Ottieni l'URL della pagina corrente senza il fragment (#) e i parametri
    const currentUrl = window.location.origin + window.location.pathname;

    // Seleziona tutti i link nella pagina
    const links = document.querySelectorAll("a");

    links.forEach(link => {
        // Ottieni l'URL assoluto del link
        const linkUrl = new URL(link.href, window.location.origin);
        const formattedLinkUrl = linkUrl.origin + linkUrl.pathname;
        let e = link.parentElement.getAttribute("class");
        if (formattedLinkUrl === currentUrl && !(e && e.includes("navigation-help"))) {
            link.setAttribute("disabled", "disabled");
            link.setAttribute("aria-disabled", "true");
            link.addEventListener("click", (e) => {
                e.preventDefault();
            });
        }
    });


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