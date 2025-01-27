window.loadersOnImages = () => {
    const images = document.querySelectorAll("img");

    images.forEach((img) => {
        const wrapper = document.createElement("div");
        wrapper.className = "image-wrapper";

        img.parentNode.insertBefore(wrapper, img);
        wrapper.appendChild(img);

        img.onload = () => {
            setTimeout(() => {
                wrapper.classList.add("loaded");
            }, 250);
        };

        img.src = img.src;
    });
};
document.addEventListener("DOMContentLoaded", () => {
    const scrollTopButton = document.getElementById('scrollTopButton');

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
});