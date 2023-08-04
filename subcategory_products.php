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
   <link rel="stylesheet" href="css/glider.min.css">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
   <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
</head>
<body>

<?php include 'components/user_header.php';?>


<section class="p-slider"> 
   <?php
      $stmt = $conn->query("SELECT subcategory_name FROM subcategory WHERE subcategory_id=$subcategoryId");
      $subcategoryName = $stmt->fetch();
   ?>
   <h3 class="product-slider-heading" >Υποκατηγορία: <?php echo $subcategoryName["subcategory_name"]; ?></h3>

   <div class="glider-contain">
      <div class="glider">
         
         <?php
         $query = $conn->query("SELECT * FROM product WHERE subcategory_subcategory_id = $subcategoryId");
         
            while (($row = $query->fetch()) !== false ){
               $imageURL = 'uploaded_img/'.$row["product_image"];
         ?>

         <div class="product-box">
            <div class="p-img-container">
               <div class="p-img">
                  <a href="#">
                     <img src="<?php echo $imageURL; ?>" alt="images/clown-image.jpg" >
                  </a>
               </div>
            </div>

            <div class="p-box-text">
               <div class="product-category">
                  <span><?php echo $row["product_name"];?></span>
               </div>
            </div>
            <a href="view_offers.php?pid=<?php echo $row["product_id"];?>"class="product-title">
               Δείτε τις προσφορές
            </a>
         </div>
            <?php
               }
            ?>
      </div>
   </div>

      <button aria-label="Previous" class="glider-prev">«</button>
      <button aria-label="Next" class="glider-next">»</button>
      <div role="tablist" class="dots"></div>
   </div>

</section>

<section class="filler-space"> 
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/glider.min.js"></script>
   <script>
      new Glider(document.querySelector('.glider'), {
  slidesToScroll: 1,
  slidesToShow: 4,
  draggable: true,
  dots: '.dots',
  arrows: {
    prev: '.glider-prev',
    next: '.glider-next'
  }
});
</script>

</body>
</html>