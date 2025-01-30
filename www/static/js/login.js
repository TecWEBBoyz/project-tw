document.addEventListener("DOMContentLoaded", () => {
    const errorFields = document.querySelectorAll(".error-message");
    if (errorFields.length > 0) {
        errorFields[0].focus();
    }
});