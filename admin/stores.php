<?php

include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_login.php');
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
            <input type="file" id="jsonFileInput" name="jsonFileInput" accept=".geojson">
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
   
    $stmt = $conn->prepare('INSERT INTO supermarket(supermarket_name,supermarket_address,x_coord,y_coord,has_offers) VALUES (?,?,?,?,0)');

    if ($jsonFileInput['error'] === UPLOAD_ERR_OK) {
        $jsonData = file_get_contents($jsonFileInput['tmp_name']);

        if ($jsonData !== false) {
            $parsedData = json_decode($jsonData, true);

            if ($parsedData !== null) {               

                foreach ($parsedData['features'] as $row) {
                    $supermarket_name = isset($row['properties']['name']) ? $row['properties']['name'] : 'no name';
                    $supermarket_addr = isset($row['properties']['addr:street']) ? $row['properties']['addr:street'] : 'no address';
                    
                    $supermarket_X = $row['geometry']['coordinates'][0];
                    $supermarket_Y = $row['geometry']['coordinates'][1];
                    
                    $stmt->bindValue(1, $supermarket_name, PDO::PARAM_STR);
                    $stmt->bindValue(2, $supermarket_addr, PDO::PARAM_STR);
                    $stmt->bindValue(3, $supermarket_X, PDO::PARAM_STR);
                    $stmt->bindValue(4, $supermarket_Y, PDO::PARAM_STR);
                    $stmt->execute();
                }

                echo 'JSON data uploaded and processed successfully!';
            } else {
                echo 'Error parsing JSON data.';
            }
        } else {
            echo 'Error reading JSON file.';
        }
    } else {
        echo 'File upload error: '.$jsonFileInput['error'];
        if ($jsonFileInput['error'] === UPLOAD_ERR_NO_FILE) {
            echo ' No file was uploaded';
        }
    }
    }
?>


<section class="shop-display">
    <h1 class="heading">Καταστήματα</h1>

    <div class="box-container">
        <?php
        $select_shops = $conn->prepare('SELECT * FROM `supermarket`');
        $select_shops->execute();
        $supermarkets = $select_shops->fetchALL(PDO::FETCH_ASSOC);
        foreach ($supermarkets as $supermarket) {
        ?>
        <div class="box">
            <div class="supermarket-info"> Όνομα καταστήματος: <?php echo $supermarket['supermarket_name']; ?></div>
            <div class="supermarket-info"> Διεύθυνση: <?php echo $supermarket['supermarket_address']; ?></div>
            <div class="supermarket-info"> Συντεταγμένες:  Χ:<?php echo $supermarket['x_coord']; ?> Υ: <?php echo $supermarket['y_coord']; ?> </div>
            <div class="supermarket-info"> Αριθμός προσφορών: <?php echo $supermarket['has_offers']; ?> </div>
            <div class="flex-btn">
            <a href="#" class="option-btn">Ενημέρωση</a>
            <a href="#" class="delete-btn" onclick="return confirm('Διαγραφή καταστήματος?');">Διαγραφή</a>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</section>




   
</body>
</html>