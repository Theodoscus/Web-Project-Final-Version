<?php
include 'connect.php';
session_start();
$stock = $_SESSION['stock'];




    // Return the map points as JSON data
    header('Content-Type: application/json');
    echo json_encode($stock);


?>