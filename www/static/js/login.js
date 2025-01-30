document.addEventListener("DOMContentLoaded", () => {
    let script = document.querySelector('script[data-template="login"]');
    if (!script) return;
    const errorFields = document.querySelectorAll(".error-message");
    if (errorFields.length > 0) {
        errorFields[0].focus();
    }
});