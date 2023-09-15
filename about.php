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
   <link rel="stylesheet" href="node_modules/swiper/swiper.min.css">

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
      <h1 class="heading">Αξιολογήσεις χρηστών</h1>
      <div class="swiper reviews-slider">
         <div class="swiper-wrapper">
            <!-- List of reviews -->
            <ul id="review-list"></ul>
            <div class="swiper-slide slide">
               <img src="images/pic-1.png" alt="">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>john deo</h3>
            </div>
            <div class="swiper-slide slide">
               <img src="images/pic-1.png" alt="">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>john deo</h3>
            </div>
         </div>
         <div class="swiper-pagination"></div>
      </div>
      <p class="proverbial-text">If you want to share your thoughts about your experience using our website, click the button below</p>
      <button id="review-button">Add Review</button>
      <div id="reviews-container"></div>
      <div id="review-modal" class="modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <label for="review-input">Enter your review:</label>
            <textarea id="review-input" rows="10"></textarea>
            <label for="name-input">Name:</label>
            <input type="text" id="name-input" value="<?php echo $username ?>">
            <div class="button-container">
               <button id="publish-button">Publish</button>
               <button id="cancel-button">Cancel</button>
            </div>
            <span id="review-error" class="error-message"></span>
         </div>
      </div>
   </section>



   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>
   <script src="js/user_script.js"></script>
   <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
   <script src="node_modules/swiper/swiper.min.js"></script>


</body>

</html>