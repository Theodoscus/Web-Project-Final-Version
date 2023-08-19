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

        <form id="delete-button"  method="post">
            <div class="delete-button-container">
                <button type="submit" name="delete" class="stores-delete-button">Διαγραφή όλων των καταστημάτων</button>
            </div>
        </form>
   </div>

   <?php
            $stmt = $conn->prepare('SET SQL_SAFE_UPDATES = 0; DELETE FROM supermarket;');
            if (isset($_POST['delete'])) {
                $stmt->execute();
                $stmt->closeCursor();
                echo 'Επιτυχής Διαγραφή';
                }
        ?>

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

                echo 'Επιτυχία ανεβάσματος JSON αρχείου!';
            } else {
                echo 'Πρόβλημα ανεβάσματος JSON αρχείου';
            }
        } else {
            echo 'Πρόβλημα ανάγνωσης JSON αρχείου';
        }
    } else {
        echo 'File upload error: '.$jsonFileInput['error'];
        if ($jsonFileInput['error'] === UPLOAD_ERR_NO_FILE) {
            echo ' Δεν έχετε επιλέξει αρχείο';
        }
    }
    }
?>


<section class="shop-display">
    <h1 class="heading">Καταστήματα</h1>

    <div class="box-container">
        <?php
        // Retrieve the search term from the URL
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


        $select_shops = $conn->prepare('SELECT * FROM `supermarket` WHERE supermarket_name LIKE ?');
        $select_shops->execute(["%$searchTerm%"]);
        $supermarkets = $select_shops->fetchALL(PDO::FETCH_ASSOC);

        // Pagination settings
        $itemsPerPage = 15;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Calculate total pages and start/end indices
        $totalItems = count($supermarkets);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $endIndex = min($startIndex + $itemsPerPage, $totalItems);

        for ($i = $startIndex; $i < $endIndex; ++$i) {
            if ($i >= $totalItems) {
                break; // Break the loop if we've displayed all available products
            }
            $supermarket = $supermarkets[$i];

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
        <div class="pagination">
                <?php
                // Calculate the range of pages to display
                $chunkSize = 20; // Number of page links to display at once
                $startPage = max($currentPage - floor($chunkSize / 2), 1);
                $endPage = min($startPage + $chunkSize - 1, $totalPages);


                for ($page = 1; $page <= $totalPages; ++$page) {
                    $activeClass = ($page === $currentPage) ? 'active' : '';

                    // Construct the correct URL by encoding the search term and page number
                    $url = "?page=$page&search=" . rawurlencode($searchTerm);

                    // Display only the page links within the calculated range
                    if ($page >= $startPage && $page <= $endPage) {
                        echo "<a href='$url' class='page-link $activeClass'>$page</a>";
                    }
                }

                ?>


            </div>

    
    </div>

</section>




   
</body>
</html>