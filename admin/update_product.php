<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update-product'])) {



}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Ενημέρωση προϊόντος</h1>
   
   <?php
    $update_id = $_GET['update'];
    $select_products = $conn->prepare('SELECT * FROM `product` WHERE product_id = ?');
    $select_products->execute([$update_id]);
    $product = $select_products->fetch();
    
    $product_subcategory_id = $product['subcategory_subcategory_id'];
    $select_subcategory = $conn->prepare('SELECT * FROM `subcategory` WHERE subcategory_id = ?');
    $select_subcategory->execute([$product_subcategory_id]);
    $product_subcategory = $select_subcategory->fetch();
    
    $product_category_id = $product_subcategory['category_category_id'];
    $select_category = $conn->prepare('SELECT category_name FROM `category` WHERE category_id = ?');
    $select_category->execute([$product_category_id]);
    $product_category = $select_category->fetch();
    ?>
    
    <form method="post" enctype="multipart/form-data">
    <div class="existing-product">
         <div class="product-image-container">
            <img src="../uploaded_img/<?php echo $product['product_image']; ?>" alt="Image about the product">
            <button class="update-image-button">Ενημέρωση Εικόνας</button>
        </div>
        
        <div class="product_info">Όνομα: <span><?php echo $product['product_name']; ?></span> <button class="update-button">Ενημέρωση</button> </div>
        <div class="product_description">Πληροφορίες: <span><?php echo $product['product_description']; ?></span><button class="update-button">Ενημέρωση</button></div>
        <div class="product_info">Κατηγορία: <span><?php echo $product_category['category_name']; ?></span><button class="update-button">Ενημέρωση</button></div>
        <div class="product_info">Υπόκατηγορία: <span><?php echo $product_subcategory['subcategory_name']; ?></span><button class="update-button">Ενημέρωση</button></div>
    </div>

        <button type="submit" name="confirm-updates">Επιβεβαίωση Ενημερώσεων</button>
    </form>

    <?php
    if (isset($_POST['confirm-updates'])) {
    }
    ?>
</section>








<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="../js/admin_ajax.js"></script>
<script src="../js/admin_update_product.js"></script>


   
</body>
</html>