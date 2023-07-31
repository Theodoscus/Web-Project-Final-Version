<?php
include 'connect.php';
session_start();
$oid = $_SESSION['oid'];
$data = $_POST['key1'];
$insert_user = $conn->prepare("UPDATE offers SET out_of_stock=? WHERE offer_id=?");
$insert_user->execute([$data, $oid]);

?>
