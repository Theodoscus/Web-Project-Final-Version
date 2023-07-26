<?php

include 'components/connect.php';

session_start();


if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   

}else{
   $user_id = '';
};


include 'components/wishlist_cart.php';
//include 'components/get_supermarkets.php';


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
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
   <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="map-container">

   <form action="" method="post">
      <input type="text" name="search" placeholder="Αναζήτηση supermarket..." class="box">
      <input type="submit" name="submit" value="Αναζήτηση" class="btn">
   </form>





      <div id="map"></div>
      
</section>

</div>

<section class="category">

   <h1 class="heading">Προσφορες ανα γενικη υποκατηγορια</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=laptop" class="swiper-slide slide">
      <img src="images/liquid-soap.png" alt="">
      <h3>Αντισηπτικά</h3>
   </a>

   <a href="category.php?category=tv" class="swiper-slide slide">
      <img src="images/baby-boy.png" alt="">
      <h3>Βρεφικά Είδη</h3>
   </a>

   <a href="category.php?category=camera" class="swiper-slide slide">
      <img src="images/pet.png" alt="">
      <h3>Για κατοικίδια</h3>
   </a>

   <a href="category.php?category=mouse" class="swiper-slide slide">
      <img src="images/cleaning.png" alt="">
      <h3>Καθαριότητα</h3>
   </a>

   <a href="category.php?category=fridge" class="swiper-slide slide">
      <img src="images/cheers.png" alt="">
      <h3>Ποτά-Αναψυκτικά</h3>
   </a>

   <a href="category.php?category=washing" class="swiper-slide slide">
      <img src="images/first-aid-kit.png" alt="">
      <h3>Προστασία Υγείας</h3>
   </a>

   <a href="category.php?category=smartphone" class="swiper-slide slide">
      <img src="images/social-care.png" alt="">
      <h3>Προσωπική Φροντίδα</h3>
   </a>

   <a href="category.php?category=watch" class="swiper-slide slide">
      <img src="images/healthy-food.png" alt="">
      <h3>Τρόφιμα</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">προσφατες προσφορες</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT offers.offer_id, product.product_id, product.product_name, offers.product_price, product.product_image ,supermarket.supermarket_name,supermarket.supermarket_address,users.username, offers.total_likes, offers.total_dislikes FROM offers,product,supermarket,users WHERE offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.Users_user_id=users.user_id  ORDER BY offer_id DESC LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
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

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>
<script src="js/map.js"></script>




   


</body>
</html>