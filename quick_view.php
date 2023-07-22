
<?php

include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:user_login.php');
}

include 'components/wishlist_cart.php';
include 'components/like_dislike.php';
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Επισκόπηση προσφοράς</h1>

   <?php
      if ($_SERVER["REQUEST_METHOD"] === "GET") {
         if (isset($_GET["key1"]) && isset($_GET["key2"])){
         $x_coord = $_GET["key1"];
         $y_coord = $_GET["key2"];
         }
         
       
         $response = "Data received successfully!";
         
       }    
      if (isset($_GET["oid"])){   
      $oid = $_GET["oid"];
      
      
      
      $select_products = $conn->prepare("SELECT offers.offer_id, offers.note, offers.product_price, offers.creation_date, offers.total_likes, offers.total_dislikes, offers.out_of_stock, users.user_id, users.username, supermarket.supermarket_name,supermarket.supermarket_address, product.product_id, product.product_name, product.product_name, product.product_description, product.product_image from product,offers,users,supermarket  WHERE offers.offer_id=? AND offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.Users_user_id=users.user_id "); 
      $select_products->execute([$oid]);
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="oid" value="<?= $fetch_product['offer_id']; ?>">
      <input type="hidden" name="pid" value="<?=$fetch_product['product_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['product_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['product_price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['product_image']; ?>">
      <input type="hidden" name="user_id" value="<?= $fetch_product['product_image']; ?>">
      
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
            </div>
         </div>
         <div class="content">
         <div class="author">
               <div class="author-name"><span>Created by: </span><?= $fetch_product['username'];?> </div>
            </div>
            <div class="location-container">
               <img src="images/location.png" alt="">
               <div class="location-name"><span> </span><?= $fetch_product['supermarket_name'];?><span>, </span> <?=$fetch_product['supermarket_address'];?></div>
            </div>
           
            <div class="name"><?= $fetch_product['product_name']; ?></div>
            <div class="flex">
               <div class="price"><span>$</span><?= $fetch_product['product_price']; ?><span>/-</span></div>
               
            </div>
            <div class="details"><?= $fetch_product['product_description']; ?></div>
            <div class="flex-btn">
               <input class="option-btn" type="submit" name="out_of_stock" value="η προσφορα έχει εξαντληθεί">
            </div>
            <div class="flex-btn">
               
               <input class="option-btn" type="submit" name="add_to_wishlist" value="προσθέστε στα αγαπημένα">
               
            </div>
            <div class="like-dislike-btns">
               <div class="like-btn">
               <input class="option-btn" type="submit" name="like" value="Like (<?= $fetch_product['total_likes'];?>)">
               </div>
               <div class="dislike-btn">
               <input class="option-btn" type="submit" name="dislike" value="Dislike (<?= $fetch_product['total_dislikes'];?>)">
               </div>
            </div> 
         </div>
      </div>
   </form>
   <?php
         }
      }
   }else{
      echo '<p class="Η προσφορά που ψάχνετε δεν υπάρχει. Δοκιμάστε ξανά.</p>';
   }
   ?>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/getlocation.js"></script>

</body>
</html>