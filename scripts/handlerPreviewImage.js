function displayInPreview(inputElement, idPreviewImage) {
    const file = inputElement.files[0];
    if (file && file.type.startsWith("image/")) {
        const elementImage = document.getElementById(idPreviewImage);
        elementImage.src = URL.createObjectURL(file);
    }
}