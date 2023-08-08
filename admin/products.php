<?php

include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_name = filter_var($product_name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $product_description = $_POST['product_description'];
    $product_description = filter_var($product_description, FILTER_SANITIZE_STRING);

    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/'.$image_01;

    $select_products = $conn->prepare('SELECT * FROM `product` WHERE product_name = ?');
    $select_products->execute([$product_name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'product name already exist!';
    } else {
        $insert_products = $conn->prepare('INSERT INTO `product`(product_name, product_description, price, image_01) VALUES(?,?,?,?)');
        $insert_products->execute([$product_name, $product_description, $price, $image_01]);

        if ($insert_products) {
            if ($image_size_01 > 2000000) {
                $message[] = 'image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name_01, $image_folder_01);
                $message[] = 'new product added!';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_product_id = $_GET['delete'];
    $delete_product_image = $conn->prepare('SELECT * FROM `product` WHERE product_id = ?');
    $delete_product_image->execute([$delete_product_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
    $delete_product = $conn->prepare('DELETE FROM `product` WHERE product_id = ?');
    $delete_product->execute([$delete_product_id]);
    // $delete_cart = $conn->prepare('DELETE FROM `cart` WHERE pproduct_id = ?');
    // $delete_cart->execute([$delete_product_id]);
    // $delete_wishlist = $conn->prepare('DELETE FROM `wishlist` WHERE pproduct_id = ?');
    // $delete_wishlist->execute([$delete_product_id]);
    header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="wproduct_idth=device-wproduct_idth, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Όνομα προϊόντος (Υποχρεωτικό)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Εισάγετε το όνομα του προϊόντος" name="name">
         </div>
         <div class="inputBox">
            <span>Τιμή προϊόντος (Υποχρεωτικό)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Εισάγετε την τιμή του προϊόντος" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>Είκονα (Μη υποχρεωτικό)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>Περιγραφή προϊόντος (Υποχρεωτικό)</span>
            <textarea name="product_description" placeholder="Εισάγετε την περιγραφή " class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="inputBox">
                <span>Κατηγορία</span>
                <select name="category_select" id="category_select" class="box" required>
                    <option selected disabled value='0'>Επιλέξτε Κατηγορία</option>
                    <?php
                            $stmt = $conn->prepare("SELECT * FROM category ORDER BY category_name");
                            $stmt->execute();
                            $categoriesList= $stmt->fetchAll();

                            foreach($categoriesList as $category){
                                echo "<option value='".$category['category_id']."'>".$category['category_name']."</option>";
                            }
        
                        ?>
                </select>
            </div>
            <div class="inputBox">
                <span>Υποκατηγορία</span>
                <select name="subcategory_select" id="subcategory_select" class="box" required>
                    <option selected disabled value='0'>Επιλέξτε Υποκατηγορία</option>
                </select>
            </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

    <h1 class="heading">products added</h1>

    <!-- Search bar -->
<div class="search-container">
   <input type="text" id="searchInput" placeholder="Search by product name">
</div>

<div class="box-container" id="productContainer">
   <?php
   $select_products = $conn->prepare('SELECT * FROM `product`');
$select_products->execute();
$products = $select_products->fetchAll(PDO::FETCH_ASSOC);
foreach ($products as $product) {
    ?>
      <div class="box">
         <img src="../uploaded_img/<?php echo $product['product_image']; ?>" alt="Image about the product">
         <div class="product_name"><?php echo $product['product_name']; ?></div>
         <!-- <div class="price">$<span><?php // echo $fetch_products['price'];?></span>/-</div> -->
         <div class="product_description"><span><?php echo $product['product_description']; ?></span></div>
         <div class="flex-btn">
            <a href="update_product.php" class="option-btn">Ενημέρωση</a>
            <a href="products.php?delete=<?php echo $product['product_id']; ?>" class="delete-btn" onclick="return confirm('Διαγραφή προϊόντος?');">Διαγραφή</a>
         </div>
      </div>
      <?php
}
?>
</div>

<!-- Here the code is the js for the search bar: -->
<script>
   const products = <?php echo json_encode($products); ?>;
   const searchInput = document.getElementById('searchInput');
   const productContainer = document.getElementById('productContainer');

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
</script>




<script src="../js/admin_script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="../js/admin_ajax.js"  ></script>
</body>
</html>