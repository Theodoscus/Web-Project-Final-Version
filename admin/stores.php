<?php

include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="upload-container">
        <h2>Εισάγετε το αρχείο JSON για τα καταστήματα</h2>
        <form id="jsonUploadForm"  method="post" enctype="multipart/form-data">
            <label for="jsonFileInput" class="custom-file-upload">
                Choose File
            </label>
            <input type="file" id="jsonFileInput" name="jsonFileInput" accept=".json">
            <button type="submit" name="submit" >Ανέβασμα JSON</button>
        </form>

        <div class="delete-button-container">
        <?php
            if (isset($_POST['delete'])) {
                // Perform the action you want to do when the button is pressed
                // For example, delete a file or a database record
                // You can add your own logic here
                }
        ?>
            <button type="submit" name="delete" class="stores-delete-button">Διαγραφή όλων των καταστημάτων</button>
        </div>
   </div>

   <?php
if (isset($_POST['submit'])) {
    $jsonFileInput = $_FILES['jsonFileInput'];
   // add code here
}
?>


    <
<section class="shop-display">
    <h1 class="heading">Καταστήματα</h1>

    <div class="box-container">
        <div class="box">

        </div>
    </div>
</section>




   
</body>
</html>