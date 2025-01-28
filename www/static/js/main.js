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
});