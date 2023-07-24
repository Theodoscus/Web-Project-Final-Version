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
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<section class="form-container">
<?php 
      $sid=0;
      if (isset($_GET["sid"])){   
      $sid = $_GET["sid"];
      
      }
      
      $select_products = $conn->prepare("SELECT x_coord, y_coord, supermarket_name, supermarket_address FROM supermarket WHERE supermarket_id= ?"); 
      $select_products->execute([$sid]);
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         $x_coord = $fetch_product['x_coord'];
         $y_coord = $fetch_product['y_coord'];
         $_SESSION['x_coord'] = $x_coord;
         $_SESSION['y_coord'] = $y_coord;
?>
   <form action="store_offer.php" method="post">
   <input type="hidden" name="supermarket_name" value="<?= $fetch_product['supermarket_name']; ?>">
   <input type="hidden" name="supermarket_address" value="<?= $fetch_product['supermarket_address']; ?>">
      <h3>Συμπληρώστε μια προσφορά!</h3>
      <h2> Supermarket: <?= $fetch_product['supermarket_name']; ?>, <?= $fetch_product['supermarket_address']; ?></h2>
      <div class="custom-dropdown">
      <input type="text" id="search" placeholder="Αναζήτηση προιόντος...">
      <div class="dropdown">
      <div class="selected-option">Select an option</div>
      <ul class="options">
        <!-- The dropdown options will be populated dynamically from the database -->
      </ul>
      </div>
      </div>
      <input type="price" name="offer_price" required placeholder="Εισάγετε την τιμή της προσφοράς" maxlength="10"  class="box">
      <input type="text" name="offer_note" required placeholder="Εισάγετε λεπτομέρειες ή διευκρινίσεις" maxlength="50"  class="box" >
      
      <input type="submit" value="Συμπλήρωση προσφοράς" class="btn" id="submit_offer_button" name="submit">
      
   </form>

   <div id="overlay"></div>

    <!-- Modal dialog -->
    <div id="modalDialog">
        <h2>No Access</h2>
        <p>You don't have permission to access this page.</p>
        
    </div>

</section>
<?php }} ?>
<?php include 'components/footer.php'; ?>



<script src="js/script.js"></script>
<script src="js/get_access.js"></script>
<script src="js/fetch_products_dp.js"></script>



   


</body>
</html>