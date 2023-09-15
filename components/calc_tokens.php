<?php
include 'components/connect.php';
$get_min_date = $conn->prepare("SELECT MIN(DATE_FORMAT(signup_date, '%Y-%m')) AS min_date FROM users WHERE user_type='user'"); 
$get_min_date->execute();
if($get_min_date->rowCount() > 0){
    while($fetch_min = $get_min_date->fetch(PDO::FETCH_ASSOC)) {
        $min_date = $fetch_min['min_date'];
        
    }
    if ($min_date === NULL){
        
    } else{

$currentDate = date('Y-m-d');
$missingMonths = array();
$existingDates = array();
$currentMonth = strtotime($min_date);
$maxDate = strtotime($currentDate);

while ($currentMonth< $maxDate){
    $missingMonths[] = date('Y-m', $currentMonth);
    $currentMonth = strtotime('+1 month', $currentMonth);
}

$get_tokens_cm = $conn->prepare("SELECT creation_date FROM system_tokens WHERE creation_date<=curdate()");
$get_tokens_cm->execute();
if($get_tokens_cm->rowCount() > 0){
    while($fetch_existing_dates = $get_tokens_cm->fetch(PDO::FETCH_ASSOC)) {
        $existingDates[] = $fetch_existing_dates['creation_date'];
        
    }
    
foreach ($missingMonths as $missingMonth) {
    $exists = false;
    $fullDate = $missingMonth . "-01";
    
    
        foreach ($existingDates as $dates) {
            
            
            if ($dates == $fullDate) {
                $exists = true;
                break;
            }
        }
    
       
        if (!$exists) {
            $get_users = $conn->prepare("SELECT COUNT(user_id) FROM users WHERE DATE_FORMAT(signup_date, '%Y-%m')<? AND user_type='user'"); 
            $get_users->execute([$missingMonth]);
            if($get_users->rowCount() > 0){
            while($fetch_users = $get_users->fetch(PDO::FETCH_ASSOC)) {
            $user_count = $fetch_users['COUNT(user_id)'];
    
            }
            }
            $tokens=$user_count*100;
            
            
            $insert_system_tokens = $conn->prepare("INSERT INTO system_tokens(amount,creation_date) VALUES (?, ?)"); 
            $insert_system_tokens->execute([$tokens, $fullDate]);
        }
    }
} else {
    foreach ($missingMonths as $missingMonth) {
        $get_users = $conn->prepare("SELECT COUNT(user_id) FROM users WHERE DATE_FORMAT(signup_date, '%Y-%m')<? AND user_type='user'"); 
            $get_users->execute([$missingMonth]);
            if($get_users->rowCount() > 0){
            while($fetch_users = $get_users->fetch(PDO::FETCH_ASSOC)) {
            $user_count = $fetch_users['COUNT(user_id)'];
    
            }
            }
            $tokens=$user_count*100;
            $fullDate = $missingMonth . "-01";
            
            $insert_system_tokens = $conn->prepare("INSERT INTO system_tokens(amount,creation_date) VALUES (?, ?)"); 
            $insert_system_tokens->execute([$tokens, $fullDate]);

}
}
}
}
?>