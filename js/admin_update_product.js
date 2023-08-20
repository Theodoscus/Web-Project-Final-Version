const updateButtons = document.querySelectorAll('.update-button');
updateButtons.forEach(button => {
    button.addEventListener('click', function() {
        const fieldContainer = this.parentElement;
        const fieldValue = fieldContainer.querySelector('span').textContent;
        const updatedValue = prompt('Enter the updated value:', fieldValue);

        if (updatedValue !== null) {
            // Update the displayed value
            fieldContainer.querySelector('span').textContent = updatedValue;

            // You can also send the updated value to the server using AJAX
            // Here's a simplified example using fetch:
            // fetch('update.php', {
            //     method: 'POST',
            //     body: JSON.stringify({ field: 'product_name', value: updatedValue })
            // });
        }
    });
});

// Attach click event listener to the update image button
const updateImageButton = document.querySelector('.update-image-button');
updateImageButton.addEventListener('click', function() {
    // Implement image update logic here
});