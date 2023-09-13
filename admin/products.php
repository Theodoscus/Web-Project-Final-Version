<?php

include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product-name'];
    $product_name = filter_var($product_name, FILTER_SANITIZE_STRING);

    $product_description = $_POST['product-description'];
    $product_description = filter_var($product_description, FILTER_SANITIZE_STRING);

    $product_subcategory = $_POST['subcategory_select'];

    $target_dir = "../uploaded_img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);



    $select_products = $conn->prepare('SELECT * FROM `product` WHERE product_name = ?');
    $select_products->execute([$product_name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'product name already exist!';
    } else {

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
            
            $insert_products = $conn->prepare('INSERT INTO `product`(product_name, product_description,subcategory_subcategory_id,product_image) VALUES(?,?,?,?)');
            $insert_products->execute([$product_name, $product_description,$product_subcategory,$uploadedFileName]);
            
            } else {
            echo "Sorry, there was an error uploading your file.";
            }
        }     

    }
    
}

if (isset($_GET['delete'])) {
    $delete_product_id = $_GET['delete'];
    $delete_product_image = $conn->prepare('SELECT * FROM `product` WHERE product_id = ?');
    $delete_product_image->execute([$delete_product_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
    $select_offer_id = $conn->prepare('SELECT offer_id FROM `offers` WHERE  product_product_id = ?');
    $select_offer_id->execute([$delete_product_id]);
    $delete_offer_id = $select_offer_id->fetch();

    $delete_product = $conn->prepare('DELETE FROM `product` WHERE product_id = ?');
    $delete_offer = $conn->prepare('DELETE FROM `offers` WHERE product_product_id = ?');
    $delete_likeactivity = $conn->prepare('DELETE FROM `likeactivity` WHERE offers_offer_id = ?');

    $delete_likeactivity->execute([$delete_offer_id]);
    $delete_offer->execute([$delete_product_id]);
    $delete_product->execute([$delete_product_id]);

    header('location:products.php');
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

    <div class="empty-space"> </div>
    <div class="upload-container">
        <h2>Εισάγετε το αρχείο JSON για τα προϊόντα</h2>
        <form id="jsonUploadForm" method="post" enctype="multipart/form-data">
            <label for="jsonFileInput" class="custom-file-upload">
                Choose File
            </label>
            <input type="file" id="jsonFileInput" name="jsonFileInput" accept=".json">
            <button type="submit" name="submit">Ανέβασμα JSON</button>
        </form>

        <form id="delete-button" method="post">
            <div class="delete-button-container">
                <button type="submit" name="delete" class="stores-delete-button">Διαγραφή όλων των προϊόντων</button>
            </div>
        </form>
    </div>

    <?php
    $stmt = $conn->prepare('SET SQL_SAFE_UPDATES = 0; DELETE FROM likeactivity; DELETE from offers; DELETE FROM product;');
    if (isset($_POST['delete'])) {
        $stmt->execute();
        $stmt->closeCursor();
        echo 'Επιτυχής Διαγραφή';
    }
    ?>


    <?php
    if (isset($_POST['submit'])) {
        $jsonFileInput = $_FILES['jsonFileInput'];

        $stmt = $conn->prepare('INSERT INTO product(product_id,product_name,product_description,subcategory_subcategory_id) VALUES (?,?,?,?)');
        $stmtCat = $conn->prepare('INSERT INTO category(category_id,category_name) VALUES (?,?)');
        $stmtSub = $conn->prepare('INSERT INTO subcategory(subcategory_id,subcategory_name,category_category_id) VALUES (?,?,?)');

        // Check if there was no file upload error
        if ($jsonFileInput['error'] === UPLOAD_ERR_OK) {
            $jsonData = file_get_contents($jsonFileInput['tmp_name']);

            if ($jsonData !== false) {
                $parsedData = json_decode($jsonData, true);

                if ($parsedData !== null) {
                    foreach ($parsedData['products'] as $row) {
                        $product_id = $row['id'];
                        $product_name = $row['name'];
                        $product_description = $row['name'];
                        $subcategory_subcategory_id = $row['subcategory'];
                        $existingProductQuery = $conn->prepare('SELECT COUNT(*) FROM product WHERE product_id = ?');
                        $existingProductQuery->execute([$product_id]);
                        $count = $existingProductQuery->fetchColumn();
                        if ($count == 0) {
                            $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
                            $stmt->bindValue(2, $product_name, PDO::PARAM_STR);
                            $stmt->bindValue(3, $product_description, PDO::PARAM_STR);
                            $stmt->bindValue(4, $subcategory_subcategory_id, PDO::PARAM_STR);

                            $stmt->execute();
                        }
                    }

                    foreach ($parsedData['categories'] as $row) {
                        $category_id = $row['id'];
                        $category_name = $row['name'];

                        foreach ($row['subcategories'] as $row2) {
                            $subcategory_name = $row2['name'];
                            $subcategory_id = $row2['uuid'];

                            $existingSubcategoryQuery = $conn->prepare('SELECT COUNT(*) FROM subcategory WHERE subcategory_id = ?');
                            $existingSubcategoryQuery->execute([$subcategory_id]);
                            $count = $existingSubcategoryQuery->fetchColumn();

                            if ($count == 0) {
                                $stmtSub->bindValue(1, $subcategory_id, PDO::PARAM_STR);
                                $stmtSub->bindValue(2, $subcategory_name, PDO::PARAM_STR);
                                $stmtSub->bindValue(3, $category_id, PDO::PARAM_STR);

                                $stmtSub->execute();
                            }
                        }

                        $existingCategoryQuery = $conn->prepare('SELECT COUNT(*) FROM category WHERE category_id = ?');
                        $existingCategoryQuery->execute([$category_id]);
                        $count = $existingCategoryQuery->fetchColumn();

                        if ($count == 0) {
                            $stmtCat->bindValue(1, $category_id, PDO::PARAM_STR);
                            $stmtCat->bindValue(2, $category_name, PDO::PARAM_STR);

                            $stmtCat->execute();
                        }
                    }

                    echo 'Επιτυχία ανεβάσματος JSON αρχείου!';
                } else {
                    echo 'Πρόβλημα ανεβάσματος JSON αρχείου.';
                }
            } else {
                echo 'Πρόβλημα διαβάσματος JSON αρχείου.';
            }
        } else {
            if ($jsonFileInput['error'] = 4) {
                echo 'Δεν έχετε επιλέξει αρχείο';
            }
        }
    }
    ?>

    <section class="add-products">

        <h1 class="heading">Προσθέστε προϊόν</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <span>Όνομα προϊόντος (Υποχρεωτικό)</span>
                    <input type="text" class="box" required maxlength="100" placeholder="Εισάγετε το όνομα του προϊόντος" name="product-name">
                </div>
                <div class="inputBox">
                    <span>Είκονα (Μη υποχρεωτικό)</span>
                    <input type="file" name="fileToUpload" id="fileToUpload"  required class="box" >
                </div>
                <div class="inputBox">
                    <span>Κατηγορία</span>
                    <select name="category_select" id="category_select" class="box" required>
                        <option selected disabled value='0'>Επιλέξτε Κατηγορία</option>
                        <?php
                        $stmt = $conn->prepare('SELECT * FROM category ORDER BY category_name');
                        $stmt->execute();
                        $categoriesList = $stmt->fetchAll();

                        foreach ($categoriesList as $category) {
                            echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Υποκατηγορία</span>
                    <select name="subcategory_select" id="subcategory_select" class="box" required>
                        <option selected disabled value='0'>Επιλέξτε Υποκατηγορία</option>
                    </select>
                </div>
            </div>

            <div class="inputBox">
                <span>Περιγραφή προϊόντος (Υποχρεωτικό)</span>
                <textarea name="product-description" placeholder="Εισάγετε την περιγραφή" class="box" required maxlength="500" cols="30" rows="10"></textarea>
            </div>

            <input type="submit" value="add product" class="btn" name="add_product">
        </form>


    </section>

    <section class="show-products">

        <h1 class="heading">Προϊόντα</h1>

        <!-- Search bar -->
        <div class="search-container">
            <form action="products.php" method="GET"> <!-- Adjust 'products.php' to the appropriate file -->
            <div class="search-bar">
                <input type="text" name="search" id="searchInput" placeholder="Αναζητήστε το όνομα του προϊόντος">
            </div>
            <button type="button" id="cancelSearch" >Ακύρωση</button>
            <button type="submit" id="searchButton">Αναζήτηση</button>
            </form>
        </div>

        <!-- Container for displaying products -->
        <div class="box-container" id="productContainer">
            <?php


            // Retrieve the search term from the URL
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

            // Pagination settings
            $itemsPerPage = 15;
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            

            // Modify the SQL query to include the search term
            $select_products = $conn->prepare('SELECT * FROM `product` WHERE product_name LIKE ?');
            $select_products->execute(["%$searchTerm%"]);
            $products = $select_products->fetchAll(PDO::FETCH_ASSOC);

            

            // Calculate total pages and start/end indices
            $totalItems = count($products);
            $totalPages = ceil($totalItems / $itemsPerPage);
            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $endIndex = min($startIndex + $itemsPerPage, $totalItems);

            // Loop through products and display them
            for ($i = $startIndex; $i < $endIndex; ++$i) {
                if ($i >= $totalItems) {
                    break; // Break the loop if we've displayed all available products
                }
                $product = $products[$i];

            ?>

                <div class="box">
                    <img src="../uploaded_img/<?php echo $product['product_image']; ?>" alt="Image about the product">
                    <div class="product_name"><?php echo $product['product_name']; ?></div>
                    <div class="product_description"><span><?php echo $product['product_description']; ?></span></div>
                    <div class="flex-btn">
                        <a href="update_product.php?update=<?php echo $product['product_id']; ?>" class="option-btn">Ενημέρωση</a>
                        <a href="products.php?delete=<?php echo $product['product_id']; ?>" class="delete-btn" onclick="return confirm('Διαγραφή προϊόντος?');">Διαγραφή</a>
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

                // Display first page link
                echo "<a href='?page=1' class='page-link'>1</a> ... ";

                for ($page = 1; $page <= $totalPages; ++$page) {
                    $activeClass = ($page === $currentPage) ? 'active' : '';

                    // Construct the correct URL by encoding the search term and page number
                    $url = "?page=$page&search=" . rawurlencode($searchTerm);

                    // Display only the page links within the calculated range
                    if ($page >= $startPage && $page <= $endPage) {
                        echo "<a href='$url' class='page-link $activeClass'>$page</a>";
                    }
                }

                // Display last page link
                if($totalPages>=15){
                echo "... <a href='?page=$totalPages' class='page-link'>$totalPages</a>";
                }
                ?>


            </div>
        </div>

    </section>
    <?php

?>
  

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/admin_searchBar.js"></script>
    <!--<script src="../js/admin_script.js"></script> -->
    <script src="../js/admin_ajax.js"></script>
</body>

</html>