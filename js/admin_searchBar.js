function filterProducts(products) {
    const searchInput = document.getElementById('searchInput');
    const productContainer = document.getElementById('productContainer');
    
    function renderProduct(product) {
        const productBox = `
            <div class="box">
                <img src="../uploaded_img/${product.product_image}" alt="Image about the product">
                <div class="product_name">${product.product_name}</div>
                <div class="product_description"><span>${product.product_description}</span></div>
                <div class="flex-btn">
                    <a href="update_product.php" class="option-btn">Ενημέρωση</a>
                    <a href="products.php?delete=${product.product_id}" class="delete-btn" onclick="return confirm('Διαγραφή προϊόντος?');">Διαγραφή</a>
                </div>
            </div>
        `;
        productContainer.innerHTML += productBox;
    }
    
    function performFilter(searchTerm) {
        productContainer.innerHTML = '';

        if (searchTerm === '') {
            products.forEach(product => renderProduct(product));
        } else {
            products.forEach(product => {
                const productName = product.product_name.toLowerCase();
                if (productName.includes(searchTerm)) {
                    renderProduct(product);
                }
            });
        }
    }

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim().toLowerCase();
        performFilter(searchTerm);
    });

    // Initial rendering
    performFilter('');
}
