function filterProducts(products) {
    const searchInput = document.getElementById('searchInput');
    const productContainer = document.getElementById('productContainer');
    let timeoutId;

    function renderProduct(product) {
        return `
            <div class="box">
                <img src="../uploaded_img/${product.product_image}" alt="Image about the product">
                <div class="product_name">${product.product_name}</div>
                <div class="product_description"><span>${product.product_description}</span></div>
                <div class="flex-btn">
                    <a href="update_product.php" class="option-btn">Ενημέρωση</a>
                    <a href="products.php?delete=${product.product_id}" class="delete-btn" onclick="return confirm('Διαγραφή προϊόντος;');">Διαγραφή</a>
                </div>
            </div>`;
    }

    function performFilter(searchTerm) {
        productContainer.innerHTML = '';

        if (searchTerm === '') {
            const productElements = products.map(renderProduct).join('');
            productContainer.innerHTML = productElements;
        } else {
            const filteredProducts = products.filter(product => {
                const productName = product.product_name.toLowerCase();
                return productName.includes(searchTerm);
            });

            const filteredProductElements = filteredProducts.map(renderProduct).join('');
            productContainer.innerHTML = filteredProductElements;
        }
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(timeoutId);
        const searchTerm = searchInput.value.trim().toLowerCase();

        timeoutId = setTimeout(() => {
            performFilter(searchTerm);
        }, 300); // Adjust the debounce delay as needed
    });

    // Initial rendering
    performFilter('');
}
