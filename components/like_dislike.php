<?php

if(isset($_POST['like'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $oid = $_POST['oid'];
      $oid = filter_var($oid, FILTER_SANITIZE_STRING);
      $get_users_id=$conn->prepare("SELECT offers.Users_user_id FROM offers WHERE offers.offer_id=?");
      $get_users_id->execute([$oid]);
      while($fetch_users_id = $get_users_id->fetch(PDO::FETCH_ASSOC)){
      $uid = $fetch_users_id['Users_user_id'];
      }
      $check_likeactivity_numbers = $conn->prepare("SELECT likeactivity.like_type FROM likeactivity WHERE likeactivity.offers_offer_id = ? AND likeactivity.Users_user_id = ?");
      $check_likeactivity_numbers->execute([$oid, $user_id]);
      if($check_likeactivity_numbers->rowCount() > 0){
        while($fetch_likes = $check_likeactivity_numbers->fetch(PDO::FETCH_ASSOC)){
            if($fetch_likes['like_type']=="like"){
                $message[] = 'You already like this offer!';
                
            }elseif($fetch_likes['like_type']=="dislike"){
                $update_activity= $conn->prepare("UPDATE likeactivity SET like_type='like' WHERE offers_offer_id = ? AND Users_user_id = ?");
                $update_activity->execute([$oid, $user_id]);
                $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes+1, total_dislikes=total_dislikes-1 WHERE offer_id=?");
                $update_offer->execute([$oid]);
                $update_users_score=$conn->prepare("UPDATE users SET total_score=total_score+6 WHERE user_id=?");
                $update_users_score->execute([$uid]);
                $update_score_activity=$conn->prepare("UPDATE score_activity SET score=5, date=CURDATE(), action_type='like' WHERE Users_user_id=? AND offer_id=? AND action_type='dislike'");
                $update_score_activity->execute([$uid,$oid]);
            }
         
        }
      }else{
        $insert_activity= $conn->prepare("INSERT INTO likeactivity(like_type, offers_offer_id, Users_user_id) VALUES('like',?,?)");
        $insert_activity->execute([$oid,$user_id]);
        $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes+1 WHERE offer_id=?");
        $update_offer->execute([$oid]);
        $update_users_score=$conn->prepare("UPDATE users SET total_score=total_score+5 WHERE user_id=?");
        $update_users_score->execute([$uid]);
        $insert_score_activity=$conn->prepare("INSERT INTO score_activity(score,Users_user_id,date,action_type,offer_id) VALUES (5,?,CURDATE(),'like',?)");
        $insert_score_activity->execute([$uid,$oid]);
      
      } 

   }

} elseif (isset($_POST['dislike'])){
    $oid = $_POST['oid'];
      $oid = filter_var($oid, FILTER_SANITIZE_STRING);
      $get_users_id=$conn->prepare("SELECT offers.Users_user_id FROM offers WHERE offers.offer_id=?");
      $get_users_id->execute([$oid]);
      while($fetch_users_id = $get_users_id->fetch(PDO::FETCH_ASSOC)){
      $uid = $fetch_users_id['Users_user_id'];}

      $check_likeactivity_numbers = $conn->prepare("SELECT likeactivity.like_type FROM likeactivity WHERE likeactivity.offers_offer_id = ? AND likeactivity.Users_user_id = ?");
      $check_likeactivity_numbers->execute([$oid, $user_id]);
      if($check_likeactivity_numbers->rowCount() > 0){
        while($fetch_likes = $check_likeactivity_numbers->fetch(PDO::FETCH_ASSOC)){
            if($fetch_likes['like_type']=="dislike"){
                $message[] = 'You already dislike this offer!';
            }elseif($fetch_likes['like_type']=="like"){
                $update_activity= $conn->prepare("UPDATE likeactivity SET like_type='dislike' WHERE offers_offer_id = ? AND Users_user_id = ?");
                $update_activity->execute([$oid, $user_id]);
                $update_offer=$conn->prepare("UPDATE offers SET total_likes=total_likes-1, total_dislikes=total_dislikes+1 WHERE offer_id=?");
                $update_offer->execute([$oid]);
                $update_users_score=$conn->prepare("UPDATE users SET total_score=total_score-6 WHERE user_id=?");
                $update_users_score->execute([$uid]);
                $update_score_activity=$conn->prepare("UPDATE score_activity SET score=-1, date=CURDATE(), action_type='dislike' WHERE Users_user_id=? AND offer_id=? AND action_type='like'");
                $update_score_activity->execute([$uid,$oid]);
            }
         
        }
      }else{
        $insert_activity= $conn->prepare("INSERT INTO likeactivity(like_type, offers_offer_id, Users_user_id) VALUES('dislike',?,?)");
        $insert_activity->execute([$oid,$user_id]);
        $update_offer=$conn->prepare("UPDATE offers SET total_dislikes=total_dislikes+1 WHERE offer_id=?");
        $update_offer->execute([$oid]);
        $update_users_score=$conn->prepare("UPDATE users SET total_score=total_score-1 WHERE user_id=?");
        $update_users_score->execute([$uid]);
        $insert_score_activity=$conn->prepare("INSERT INTO score_activity(score,Users_user_id,date,action_type,offer_id) VALUES (-1,?,CURDATE(),'dislike',?)");
        $insert_score_activity->execute([$uid,$oid]);
      }

   }




?>