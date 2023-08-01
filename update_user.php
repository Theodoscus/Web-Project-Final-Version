<?php

include 'components/connect.php';

session_start();


if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   

}else{
   $user_id = '';
};


include 'components/wishlist_cart.php';




if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $update_profile = $conn->prepare('UPDATE `users` SET username = ?, email = ? WHERE user_id = ?');
    $update_profile->execute([$name, $email, $user_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($old_pass == $empty_pass) {
        $message[] = 'please enter old password!';
    } elseif ($old_pass != $prev_pass) {
        $message[] = 'old password not matched!';
    } elseif ($new_pass != $cpass) {
        $message[] = 'confirm password not matched!';
    } else {
        if ($new_pass != $empty_pass) {
            $update_admin_pass = $conn->prepare('UPDATE `users` SET password = ? WHERE user_id = ?');
            $update_admin_pass->execute([$cpass, $user_id]);
            $message[] = 'password updated successfully!';
        } else {
            $message[] = 'please enter a new password!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    


</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="form-container" >
        <form action="" method="post">
            <h3>update now</h3>
            <input type="hidden" name="prev_pass" value="<?php echo $fetch_profile['password']; ?>">
            <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?php echo $fetch_profile['username']; ?>">
            <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch_profile['email']; ?>">
            <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="update now" class="btn" name="submit">
        </form>
    </section>

    <section class="home-products">

   <h1 class="heading">προσφορές που εχετε δημοσιευσει</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT offers.out_of_stock, offers.offer_id, product.product_id, product.product_name, offers.product_price, product.product_image ,supermarket.supermarket_name,supermarket.supermarket_address, offers.total_likes, offers.total_dislikes, offers.creation_date, users.username FROM offers,product,supermarket,users WHERE offers.Users_user_id=? AND offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND users.user_id=offers.Users_user_id ORDER BY offer_id DESC"); 
     $select_products->execute([$user_id]);
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
      <input type="hidden" name="creation_date" value="<?= $fetch_product['creation_date']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['out_of_stock']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?oid=<?= $fetch_product['offer_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
      <div class="name"><?= $fetch_product['product_name']; ?></div>
      <?php $stock = $fetch_product['out_of_stock']; if ($stock==='false'){
         $has_stock='Υπάρχει απόθεμα';
         }else{
            $has_stock='Δεν υπάρχει απόθεμα';
         }?>
      <div class="stock"><?= $has_stock?></div>
      <div class="flex">
         <div class="price"><?= $fetch_product['product_price']; ?><span>€ στο supermarket: </span><br><?= $fetch_product['supermarket_name']; ?><span>, </span><?= $fetch_product['supermarket_address']; ?></div>
         
      </div>
      <div class="down-part">
         <div class="author"><span>Δημιουργήθηκε από: </span><?= $fetch_product['username']; ?> την ημερομηνία: <?= $fetch_product['creation_date']; ?></div>
         
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

    

</body>

</html>