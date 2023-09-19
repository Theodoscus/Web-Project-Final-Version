<?php

include 'connect.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:user_login.php');
}
//Fetching data

$request = 0;
if(isset($_POST['request'])){
    $request = $_POST['request'];
 }

 if($request == 2){

    

    

 }