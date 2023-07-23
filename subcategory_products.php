<?php

include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'];
$subcategoryId = $_SESSION['subcategoryId'];
$var = 10;
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
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/subcategory_style.css">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
   <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
   
</head>
<body>

<?php include 'components/user_header.php';?>
<div class="filter-box">
   <p> Here add filters </p> 
</div>
<div class="products-container">
   <div class="subcategory-name-display"> 
      <?php
         $stmt = $conn->query("SELECT subcategory_name FROM subcategory WHERE subcategory_id=$subcategoryId");
         $subcategoryName = $stmt->fetch();
      ?>
      <p> Υποκατηγορία: <?php echo $subcategoryName["subcategory_name"]; ?></p>
   </div>
</div>





<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->

<script src="js/script.js"></script>

</body>
</html>