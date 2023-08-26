<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Ενημέρωση προϊόντος</h1>
   
   <?php
    $update_id = $_GET['update'];
    $select_products = $conn->prepare('SELECT * FROM `product` WHERE product_id = ?');
    $select_products->execute([$update_id]);
    $product = $select_products->fetch();
    
    $product_subcategory_id = $product['subcategory_subcategory_id'];
    $select_subcategory = $conn->prepare('SELECT * FROM `subcategory` WHERE subcategory_id = ?');
    $select_subcategory->execute([$product_subcategory_id]);
    $product_subcategory = $select_subcategory->fetch();
    
    $product_category_id = $product_subcategory['category_category_id'];
    $select_category = $conn->prepare('SELECT category_name FROM `category` WHERE category_id = ?');
    $select_category->execute([$product_category_id]);
    $product_category = $select_category->fetch();
    ?>
    
    
    <div class="existing-product">
    <form method="post" enctype="multipart/form-data">
         <div class="product-image-container">
            <img src="../uploaded_img/<?php echo $product['product_image']; ?>" alt="Image about the product">
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>  
        <div class="product_info">Όνομα: 
            <input type="text" id="updated-name" name="updated-name" value="<?php echo $product['product_name']; ?>">
            <button class="update-button" type="button" name="update-name" onclick="updateName()">Συνέχεια</button>
        </div>
        <div class="product_description">Πληροφορίες: 
            <input type="text" name="updated-description" value="<?php echo $product['product_description']; ?>">
            <button class="update-button" type="button" name="update-description" onclick="updateDescription()">Συνέχεια</button>
        </div>
        <div class="product_info">Κατηγορία: <span><?php echo $product_category['category_name']; ?> </div>
        <div class="product_info">Υπόκατηγορία: <span><?php echo $product_subcategory['subcategory_name']; ?> </div>
     
        <button type="submit" name="confirm-updates">Επιβεβαίωση και επιστροφή στα προϊόντα</button>
    </form>
    </div>

    <?php    


    if (isset($_POST['confirm-updates'])) {
        
        $updatedName = $_POST['updated-name'];
        $updateQuery = $conn->prepare('UPDATE product SET product_name = ? WHERE product_id = ?');
        $updateQuery->execute([$updatedName, $update_id]);

        $updatedDescription = $_POST['updated-description'];  
        $updateQuery = $conn->prepare('UPDATE product SET product_description = ? WHERE product_id = ?');
        $updateQuery->execute([$updatedDescription, $update_id]);

        if (!empty($_FILES['fileToUpload']['name'])){
        $target_dir = "../uploaded_img/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            echo "File is not an image.";
            $uploadOk = 0;
          }

           // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            
            $uploadedFileName = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
            $updateQuery = $conn->prepare('UPDATE product SET product_image = ? WHERE product_id = ?');
            $updateQuery->execute([$uploadedFileName, $update_id]);
            
            } else {
            echo "Sorry, there was an error uploading your file.";
            }
        }     
        }

        header('location:products.php');
    }
    ?>
</section>








<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="../js/admin_ajax.js"></script>
<script src="../js/admin_update_product.js"></script>


   
</body>
</html>