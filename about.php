<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   // Query the database to get the username for the logged in user
   $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
   $stmt->bindParam(':user_id', $user_id);
   $stmt->execute();
   
   $result = $stmt->fetch(PDO::FETCH_ASSOC);
   $username = $result['username'];
} else {
   $user_id = '';
   $username = '';
}

// Query the database to get reviews data
$reviewsStmt = $conn->prepare("SELECT review.content, review.stars, users.username 
                               FROM review 
                               INNER JOIN users ON review.Users_user_id = users.user_id");
$reviewsStmt->execute();
$reviewsData = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['publish'])) {
   // Get the values from the form
   $content = $_POST['review_input'];
   $stars = $_POST['star_input'];

   // Insert the new review into the database
   $insertStmt = $conn->prepare("INSERT INTO review (Users_user_id, content, stars) VALUES (:user_id, :content, :stars)");
   $insertStmt->bindParam(':user_id', $user_id);
   $insertStmt->bindParam(':content', $content);
   $insertStmt->bindParam(':stars', $stars);

   if ($insertStmt->execute()) {
      // Refresh the page to display the updated reviews
      header('location: about.php');
      exit();
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Required CSS -->
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/review_style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="about">

      <div class="row">

         <div class="image">
            <img src="images/imgabout.png" alt="">
         </div>

         <div class="content">
            <h3>Πως λειτουργει;</h3>
            <p>Αναζητήστε τις καλύτερες προσφορές στα καταστήματα της περιοχής σας ενημερωμένα με τιμές σε πραγματικό χρόνο από διαφορετικούς χρήστες που χρησιμοποιούν την εφαρμογή και βρίσκονται τουλάχιστον 50 μέτρα απο το κατάστημα.</p>
            <a href="home.php" class="btn">Δείτε τις προσφορές!</a>
         </div>

      </div>

   </section>


   <section class="reviews">
      <h1 class="heading">Αξιολογησεις χρηστων</h1>
      <div class="swiper reviews-slider">
         <div class="swiper-wrapper">

            <?php if($reviewsStmt->rowCount() > 0){ foreach ($reviewsData as $review) {?>
  
               <div class="swiper-slide slide">
                  <!-- Display review content -->
                  <img src="images/avatar.jpg" alt="">
                  <p><?php echo $review['content']; ?></p>
                  <div class="stars">
                     <?php
                     // Display stars based on review.stars
                     $starsCount = intval($review['stars']);
                     for ($i = 0; $i < $starsCount; $i++) {
                        echo '<i class="fas fa-star"></i>';
                     }
                     ?>
                  </div>
                  <!-- Display username -->
                  <h3><?php echo $review['username']; ?></h3>
               </div>
            <?php }} else {
               $message[]='Δεν υπάρχουν διαθέσιμες αξιολογήσεις!';
            } ?>
         </div>


         <div class="swiper-pagination"></div>
      </div>
      <p class="proverbial-text">If you want to share your thoughts about your experience using our website, click the button below</p>
      <button id="review-button" <?php if(isset($_SESSION['user_id'])){}else{echo "disabled";};  ?>>Add Review</button>
      <div id="reviews-container"></div>
      <div id="review-modal" class="modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
               <label for="review-input">Enter your review:</label>
               <textarea id="review-input" name="review_input" rows="10"></textarea>
               <label for="star-input">From 0 to 5 how good was your experience?</label>
               <input type="number" id="star-input" name="star_input" min="0" max="5" step="1">
               <label for="name-input">Name:</label>
               <input type="text" id="name-input" value="<?php echo $username ?>">
               <div class="button-container">
                  <button id="publish-button" type="submit" name="publish">Publish</button>
                  <button id="cancel-button">Cancel</button>
               </div>
               <span id="review-error" class="error-message"></span>
            </form>
         </div>
      </div>
   </section>



   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>
   <script src="js/user_script.js"></script>



</body>

</html>