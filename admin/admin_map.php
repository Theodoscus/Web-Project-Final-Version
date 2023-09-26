<?php
include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['admin_id'];

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
    <title>Map</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

</head>


<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="map-container">

        <form action="" method="post">
            <input type="text" name="search" id="searchInput" placeholder="Αναζήτηση supermarket..." class="box">
        </form>

        <div id="map"></div>

    </section>


    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script src="../js/script.js"></script>
    <script src="../js/admin_map.js"></script>

</body>

</html>