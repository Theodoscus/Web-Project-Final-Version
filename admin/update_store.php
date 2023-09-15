<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

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

<section class ="update-store">
    <h1 class="heading">Ενημέρωση καταστήματος</h1>

    <?php
    $update_id = $_GET['update'];
    $select_stores = $conn->prepare('SELECT * FROM `supermarket` WHERE supermarket_id = ?');
    $select_stores->execute([$update_id]);
    $store = $select_stores->fetch();
    ?>
    
    <div class="existing-store">
        <form method="post">
            <div class="store_info">Όνομα: 
            <input type="text" id="updated-name" name="updated-name" value="<?php echo $store['supermarket_name']; ?>">
            <button class="update-button" type="button" name="update-name" onclick="updateStoreName()">Συνέχεια</button>
            </div>  
            <div class="store_info">Διεύθυνση: 
            <input type="text" id="updated-address" name="updated-address" value="<?php echo $store['supermarket_address']; ?>">
            <button class="update-button" type="button" name="update-address" onclick="updateStoreAddress()">Συνέχεια</button>
            </div> 
            <div class="store_coord">Συντεταγμένες: &nbsp  &nbsp   Χ: 
            <input type="text" id="updated-Χ" name="updated-Χ" value="<?php echo $store['x_coord']; ?>"> 
            <button class="update-button" type="button" name="update-X" onclick="updateXcoord()">Συνέχεια</button>
            &nbsp &nbsp Υ: <input type="text" id="updated-Υ" name="updated-Υ" value="<?php echo $store['y_coord']; ?>">
            <button class="update-button" type="button" name="update-Y" onclick="updateYcoord()">Συνέχεια</button>
            </div>
            
            <button class="confirm-updates" type="submit" name="confirm-updates">Επιβεβαίωση και επιστροφή στα καταστήματα</button>
        </form>
    </div>

</section>

<?php 
if (isset($_POST['confirm-updates'])) {

    $updatedName = $_POST['updated-name'];
    $updateQuery = $conn->prepare('UPDATE supermarket SET supermarket_name = ? WHERE supermarket_id = ?');
    $updateQuery->execute([$updatedName, $update_id]);

    $updatedAddress = $_POST['updated-address'];
    $updateQuery = $conn->prepare('UPDATE supermarket SET supermarket_address = ? WHERE supermarket_id = ?');
    $updateQuery->execute([$updatedAddress, $update_id]);

    $updatedX = $_POST['updated-Χ'];
    $updateQuery = $conn->prepare('UPDATE supermarket SET x_coord = ? WHERE supermarket_id = ?');
    $updateQuery->execute([$updatedX, $update_id]);

    $updatedY = $_POST['updated-Υ'];
    $updateQuery = $conn->prepare('UPDATE supermarket SET y_coord = ? WHERE supermarket_id = ?');
    $updateQuery->execute([$updatedY, $update_id]);

    header('location:stores.php');
}

?>

</body>
</html>