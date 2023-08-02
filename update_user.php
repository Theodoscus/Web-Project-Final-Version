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

<section class='like-dislike-table'>
    <h1 class="heading">Like/Dislike που έχετε κάνει</h1>
    <table>
        <thead>
            <tr>
                <th>Like/Dislike</th>
                <th>Date</th>
                <th>Product name</th>
                <th>Product price</th>
                <th>Supermarket name</th>
            </tr>
        </thead>
    <?php
     $select_like_dislike = $conn->prepare("SELECT likeactivity.like_type,likeactivity.date, offers.product_price, product.product_name, supermarket.supermarket_name FROM likeactivity,offers,product,supermarket WHERE likeactivity.Users_user_id=? AND likeactivity.offers_offer_id=offers.offer_id AND offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id"); 
     $select_like_dislike->execute([$user_id]);
     if($select_like_dislike->rowCount() > 0){
      while($fetch_like_dislike = $select_like_dislike->fetch(PDO::FETCH_ASSOC)){
   ?>
    <form action="" method="post" class="like_dislike_form">
      <input type="hidden" name="like_type" value="<?= $fetch_like_dislike['like_type']; ?>">
      <input type="hidden" name="date" value="<?= $fetch_like_dislike['date']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_like_dislike['product_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_like_dislike['product_price']; ?>">
      <input type="hidden" name="supermarket_name" value="<?= $fetch_like_dislike['supermarket_name']; ?>">
      
        <tbody id="tableBody">
        <td> <?= $fetch_like_dislike['like_type']?>  </td>
        <td> <?= $fetch_like_dislike['date']?>  </td>
        <td> <?= $fetch_like_dislike['product_name']?> </td>
        <td> <?= $fetch_like_dislike['product_price']?> </td>
        <td> <?= $fetch_like_dislike['supermarket_name']?> </td>
        </tbody>
    
    </form>



    <?php
      }
   }else{
      echo '<p class="empty">Δεν υπάρχει διαθέσιμο ιστορικό!</p>';
   }
   ?>
</table>
</section>

<section class='score_activity'>

<h1 class="heading">Score και Tokens που έχετε συγκεντρώσει</h1>
    <table>
        <thead>
            <tr>
                <th>Total Score</th>
                <th>Monthly Score</th>
                <th>Total Tokens</th>
                <th>Last Month Tokens</th>
            </tr>
        </thead>
    <?php
     $select_score = $conn->prepare("SELECT
     u.user_id,
     u.total_score,
     COALESCE(current_month_score, 0) AS current_month_score,
     COALESCE(last_month_tokens, 0) AS last_month_tokens,
     COALESCE(total_tokens, 0) AS total_tokens
 FROM
     users u
 LEFT JOIN (
     SELECT
         sa.Users_user_id,
         SUM(sa.score) AS current_month_score
     FROM
         score_activity sa
     WHERE
         sa.Users_user_id = ? AND
         MONTH(sa.date) = MONTH(CURRENT_DATE()) AND
         YEAR(sa.date) = YEAR(CURRENT_DATE())
     GROUP BY
         sa.Users_user_id
 ) cms ON u.user_id = cms.Users_user_id
 LEFT JOIN (
     SELECT
         ut.Users_user_id,
         SUM(CASE
             WHEN MONTH(ut.date) = MONTH(CURRENT_DATE()) - 1 AND YEAR(ut.date) = YEAR(CURRENT_DATE()) THEN ut.tokens
             ELSE 0
         END) AS last_month_tokens,
         SUM(ut.tokens) AS total_tokens
     FROM
         user_tokens ut
     WHERE
         ut.Users_user_id = ?
     GROUP BY
         ut.Users_user_id
 ) lmt ON u.user_id = lmt.Users_user_id
 WHERE
     u.user_id = ?;"); 
     $select_score->execute([$user_id, $user_id, $user_id]);
     if($select_score->rowCount() > 0){
      while($fetch_score = $select_score->fetch(PDO::FETCH_ASSOC)){
   ?>
    <form action="" method="post" class="score_form">
      <input type="hidden" name="total_score" value="<?= $fetch_score['total_score']; ?>">
      <input type="hidden" name="score" value="<?= $fetch_score['current_month_score']; ?>">
      <input type="hidden" name="total_tokens" value="<?= $fetch_score['total_tokens']; ?>">
      <input type="hidden" name="tokens" value="<?= $fetch_score['last_month_tokens']; ?>">
      
      
      
        <tbody id="tableBody">
        <td> <?= $fetch_score['total_score']?>  </td>
        <td> <?= $fetch_score['current_month_score']?>  </td>
        <td> <?= $fetch_score['total_tokens']?> </td>
        <td> <?= $fetch_score['last_month_tokens']?> </td>
        
        </tbody>
    
    </form>



    <?php
      }
   }else{
      echo '<p class="empty">Δεν υπάρχει διαθέσιμο ιστορικό!</p>';
   }
   ?>
</table>

</section>
    





<?php include 'components/footer.php'; ?>



    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

    

</body>

</html>