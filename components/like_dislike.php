<?php

if(isset($_POST['like'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);

      $check_likeactivity_numbers = $conn->prepare("SELECT likeactivity.like_type FROM likeactivity WHERE likeactivity.offers_offer_id = ? AND likeactivity.Users_user_id = ?");
      $check_likeactivity_numbers->execute([$pid, $user_id]);
      if($check_likeactivity_numbers->rowCount() > 0){
        while($fetch_likes = $check_likeactivity_numbers->fetch(PDO::FETCH_ASSOC)){
            if($fetch_likes['like_type']=="like"){
                $message[] = 'You already like this offer!';
                
            }elseif($fetch_likes['like_type']=="dislike"){
                $update_activity= $conn->prepare("UPDATE likeactivity SET like_type='like' WHERE offers_offer_id = ? AND Users_user_id = ?");
                $update_activity->execute([$pid, $user_id]);
                $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes+1, total_dislikes=total_dislikes-1 WHERE offer_id=?");
                $update_offer->execute([$pid]);
            }
         
        }
      }else{
        $insert_activity= $conn->prepare("INSERT INTO likeactivity(like_type, offers_offer_id, Users_user_id) VALUES('like',?,?)");
        $insert_activity->execute([$pid,$user_id]);
        $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes+1 WHERE offer_id=?");
        $update_offer->execute([$pid]);
      }

   }

} elseif (isset($_POST['dislike'])){
    $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);

      $check_likeactivity_numbers = $conn->prepare("SELECT likeactivity.like_type FROM likeactivity WHERE likeactivity.offers_offer_id = ? AND likeactivity.Users_user_id = ?");
      $check_likeactivity_numbers->execute([$pid, $user_id]);
      if($check_likeactivity_numbers->rowCount() > 0){
        while($fetch_likes = $check_likeactivity_numbers->fetch(PDO::FETCH_ASSOC)){
            if($fetch_likes['like_type']=="dislike"){
                $message[] = 'You already dislike this offer!';
            }elseif($fetch_likes['like_type']=="like"){
                $update_activity= $conn->prepare("UPDATE likeactivity SET like_type='dislike' WHERE offers_offer_id = ? AND Users_user_id = ?");
                $update_activity->execute([$pid, $user_id]);
                $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes-1, total_dislikes=total_dislikes+1 WHERE offer_id=?");
                $update_offer->execute([$pid]);
            }
         
        }
      }else{
        $insert_activity= $conn->prepare("INSERT INTO likeactivity(like_type, offers_offer_id, Users_user_id) VALUES('dislike',?,?)");
        $insert_activity->execute([$pid,$user_id]);
        $update_offer=$conn->prepare("UPDATE offers SET total_dislikes=total_dislikes+1 WHERE offer_id=?");
        $update_offer->execute([$pid]);
      }

   }




?>