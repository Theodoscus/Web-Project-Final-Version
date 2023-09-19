<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $oid = $_POST['oid'];
      $oid = filter_var($oid, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE offer_id = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$oid, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Έχει ήδη προστεθεί στα αγαπημένα!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, product_id, offer_id) VALUES(?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $oid]);
         $message[] = 'Προστέθηκε στα αγαπημένα!';
      }

   }

}



?>