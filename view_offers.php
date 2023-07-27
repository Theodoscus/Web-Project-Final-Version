<?php

include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:user_login.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
   
</head>
<body>

<?php include 'components/user_header.php';?>


<section class="home-products">
    <?php
    $pid=0;
    if (isset($_GET["pid"])){   
    $pid = $_GET["pid"];
    }
    
     $select_offers = $conn->prepare("SELECT offers.offer_id, product.product_id, product.product_name, offers.product_price, product.product_image ,supermarket.supermarket_name,supermarket.supermarket_address,users.username, offers.total_likes, offers.total_dislikes FROM offers,product,supermarket,users WHERE product.product_id=? AND offers.product_product_id=product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.Users_user_id=users.user_id"); 
     $select_offers->execute([$pid]);
     if($select_offers->rowCount() > 0){
      while($fetch_product = $select_offers->fetch(PDO::FETCH_ASSOC)){
   ?>
   <h1 class="heading">Προσφορές για το προιόν: <?=$fetch_product['product_name'];?></h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="oid" value="<?= $fetch_product['offer_id']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_product['product_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['product_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['product_price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['product_image']; ?>">
      <input type="hidden" name="supermarket_name" value="<?= $fetch_product['supermarket_name']; ?>">
      <input type="hidden" name="supermarket_address" value="<?= $fetch_product['supermarket_address']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?oid=<?= $fetch_product['offer_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
      <div class="name"><?= $fetch_product['product_name']; ?></div>
      <div class="flex">
         <div class="price"><?= $fetch_product['product_price']; ?><span>€ στο supermarket: </span><?= $fetch_product['supermarket_name']; ?><span>, </span><?= $fetch_product['supermarket_address']; ?></div>
         
      </div>
      <div class="down-part">
         <div class="author"><span>Created by: </span><?= $fetch_product['username']; ?></div>
         
         <div class="likes">
         <img src="images/like.png" alt="like">
            <?= $fetch_product['total_likes']; ?>
         <img src="images/dislike.png" alt="dislike">
         <?= $fetch_product['total_dislikes']; ?>
         </div>
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Δεν υπάρχουν διαθέσιμες προσφορές!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

   


</body>
</html>