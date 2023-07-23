
<?php
include 'connect.php';
session_start();
$x_coord = $_SESSION['x_coord'];
$y_coord = $_SESSION['y_coord'];
$mapPoints = array($x_coord, $y_coord);



    // Return the map points as JSON data
    header('Content-Type: application/json');
    echo json_encode($mapPoints);


?>