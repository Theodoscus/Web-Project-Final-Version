<?php
include 'components/connect.php';
$get_tokens_cm = $conn->prepare("SELECT system_tokens_id FROM system_tokens WHERE MONTH(creation_date)=MONTH(CURDATE()) AND YEAR(creation_date)=YEAR(CURDATE())");
$get_tokens_cm->execute();

if($get_tokens_cm->rowCount() <= 0){
    echo 'great';
$get_users = $conn->prepare("SELECT COUNT(user_id) FROM users"); 
$get_users->execute();
if($get_users->rowCount() > 0){
while($fetch_users = $get_users->fetch(PDO::FETCH_ASSOC)) {
    $user_count = $fetch_users['COUNT(user_id)'];
    
}
}
$tokens=$user_count*100;
echo $tokens;
$insert_system_tokens = $conn->prepare("INSERT INTO system_tokens(amount,creation_date) VALUES (?, CURDATE())"); 
$insert_system_tokens->execute([$tokens]);}
?>