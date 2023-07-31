<?php
include 'components/connect.php';
$get_tokens = $conn->prepare("SELECT amount FROM tokens WHERE MONTH(creation_date)=MONTH(CURDATE()) AND YEAR(creation_date)=YEAR(CURDATE())"); 
$get_tokens->execute();
if($get_tokens->rowCount() > 0){
    while($fetch_tokens= $get_score->fetch(PDO::FETCH_ASSOC)) {
        $total_tokens_non_format = $fetch_tokens['amount'];
        $total_tokens=round($total_tokens_non_format*0.8);
        
    }
}
$get_total_score = $conn->prepare("SELECT SUM(score) FROM score_activity WHERE MONTH(date)=MONTH(CURDATE()) AND YEAR(date)=YEAR(CURDATE())"); 
$get_total_score->execute();
if($get_total_score->rowCount() > 0){
    while($fetch_total_score= $get_total_score->fetch(PDO::FETCH_ASSOC)) {
        $total_score = $fetch_total_score['SUM(score)'];
        
    }
}
$get_user_score = $conn->prepare("SELECT Users_user_id, SUM(score) AS total_score
FROM score_activity
WHERE MONTH(date) = MONTH(CURDATE())  AND YEAR(date) = YEAR(CURDATE())
GROUP BY Users_user_id;"); 
$get_user_score->execute();
if($get_user_score->rowCount() > 0){
while($fetch_user_score= $get_user_score->fetch(PDO::FETCH_ASSOC)) {
    $user_score = $fetch_users['SUM(score)'];
    $user_percentage = $user_score/$total_score;
    $user_tokens = $user_percentage*$total_tokens;
    $user_tokens_round = round($user_tokens);
    $user_id = $fetch_users['Users_user_id'];
    $insert_tokens = $conn->prepare("INSERT INTO user_tokens(tokens,Users_user_id,date) VALUES (?,?,curdate())"); 
    $get_tokens->execute([$user_tokens_round, $user_id]);
    
}
}







?>