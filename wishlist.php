<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

include 'components/wishlist_cart.php';

if(isset($_POST['delete'])){
   $wishlist_id = $_POST['oid'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE offer_id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

if(isset($_GET['delete_all'])){
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h3 class="heading">Αγαπημένες προσφορές</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT offer_id, wishlist_id FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
            $oid = $fetch_wishlist['offer_id'];
            $wid = $fetch_wishlist['wishlist_id'];
            $select_products = $conn->prepare("SELECT offers.offer_id, offers.note, offers.product_price, offers.creation_date, offers.total_likes, offers.total_dislikes, offers.out_of_stock, users.user_id, users.username, supermarket.supermarket_name,supermarket.supermarket_address, product.product_id, product.product_name, product.product_name, product.product_description, product.product_image from product,offers,users,supermarket  WHERE offers.offer_id=? AND offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.Users_user_id=users.user_id "); 
            $select_products->execute([$fetch_wishlist['offer_id']]);
               if($select_products->rowCount() > 0){
                  while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                     $grand_total++;
   ?>
      <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['product_id']; ?>">
      <input type="hidden" name="oid" value="<?= $oid ?>">
      <input type="hidden" name="wishlist_id" value="<?= $wid; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['product_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['product_price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['product_image']; ?>">
      <a href="quick_view.php?pid=<?= $oid ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
      <div class="name"><?= $fetch_product['product_name']; ?></div>
      <div class="flex">
         <div class="price">$<?= $fetch_product['product_price']; ?>/- στο supermarket: <?= $fetch_product['supermarket_name']; ?><span>, </span><?= $fetch_product['supermarket_address']; ?></div>
      </div>
      <a href="quick_view.php?oid=<?= $oid ?>" class="option-btn">Αναλυτικά</a>
      <input type="submit" value="Διαγραφή προσφοράς" onclick="return confirm('Θέλετε να διαγράψετε την προσφορά από τα αγαπημένα σας;');" class="delete-btn" name="delete">
   </form>
   <?php
         }
      }
   }  
   }else{
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      
      <a href="shop.php" class="option-btn">Δείτε και άλλες προσφορές</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 0)?'':'disabled'; ?>" onclick="return confirm('Επιθυμείτε να διαγράψετε όλα τα αντικέιμενα από τα αγαπημένα σας;');">Διαγραφή όλων των προιόντων</a>
   </div>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>