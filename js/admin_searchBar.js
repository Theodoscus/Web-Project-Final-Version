$(document).ready(function () {

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

    function filterProducts(searchTerm, productsData) {
        const filteredProducts = productsData.filter((product) => {
            const productName = product.product_name.toLowerCase();
            return productName.includes(searchTerm);
        });

        const productElements = filteredProducts.map(renderProduct).join("");
        $("#productContainer").html(productElements);
    }

    // Handle search input changes
    $("#searchInput").on("input", function () {
        const searchTerm = $(this).val().trim().toLowerCase();
        filterProducts(searchTerm, productsData);
    });

    // Initial rendering
    filterProducts("", productsData);
});
