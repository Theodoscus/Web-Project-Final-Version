let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.update-product .image-container .main-image img');
let subImages = document.querySelectorAll('.update-product .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});


// Function to render a product box
function renderProduct(product) {
   const productBox = `
       <div class="box">
           <img src="../uploaded_img/${product.product_image}" alt="Image about the product">
           <div class="product_name">${product.product_name}</div>
           <!-- <div class="price">$<span><?php // echo $fetch_products['price'];?></span>/-</div> -->
           <div class="product_description"><span>${product.product_description}</span></div>
           <div class="flex-btn">
               <a href="update_product.php?update=${product.product_id}" class="option-btn">update</a>
               <a href="products.php?delete=${product.product_id}" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
           </div>
       </div>
   `;
   productContainer.innerHTML += productBox;
}

// Function to filter and display products based on the search input
function filterProducts() {
   const searchTerm = searchInput.value.trim().toLowerCase();
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

// Event listener for the search input
searchInput.addEventListener('input', filterProducts);
