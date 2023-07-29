<?php

include '../components/connect.php'; // Update the path to connect.php

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Update the path to the CSS file -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>

<body>

    <?php include '../components/admin_header.php'; ?> <!-- Update the path to admin_header.php -->

    <div class="home-bg">

        

    </div>

    <?php include '../components/footer.php'; ?> <!-- Update the path to footer.php -->

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script src="../js/script.js"></script> <!-- Update the path to script.js -->
    <script src="../js/map.js"></script> <!-- Update the path to map.js -->

</body>

</html>
