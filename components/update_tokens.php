<?php
include 'components/connect.php';

$get_min_date = $conn->prepare("SELECT MIN(creation_date) AS min_date FROM system_tokens"); 
$get_min_date->execute();
if($get_min_date->rowCount() > 0){
    while($fetch_min = $get_min_date->fetch(PDO::FETCH_ASSOC)) {
        $min_date = $fetch_min['min_date'];
    }}
$currentDate = date('Y-m-d');
$missingMonths = array();
$existingDates = array();
$currentMonth = strtotime($min_date);
$maxDate = strtotime('last day of previous month', strtotime($currentDate));

while ($currentMonth< $maxDate){
        $missingMonths[] = date('Y-m', $currentMonth);
        $currentMonth = strtotime('+1 month', $currentMonth);
}

$get_tokens_cm = $conn->prepare("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS 'year_month' FROM user_tokens");
$get_tokens_cm->execute();
if($get_tokens_cm->rowCount() > 0){
    while($fetch_existing_dates = $get_tokens_cm->fetch(PDO::FETCH_ASSOC)) {
        $existingDates[] = $fetch_existing_dates['year_month'];
        
    }


foreach ($missingMonths as $missingMonth) {
    $exists = false;
    
        foreach ($existingDates as $dates) {
            
            if ($dates == $missingMonth) {
                $exists = true;
                break;
            }
        
        }
       
        if (!$exists) {
            
            $fullDate = $missingMonth . "-01";
            $get_tokens = $conn->prepare("SELECT amount FROM system_tokens WHERE creation_date=?"); 
            $get_tokens->execute([$fullDate]);
            if($get_tokens->rowCount() > 0){
                while($fetch_tokens= $get_tokens->fetch(PDO::FETCH_ASSOC)) {
                    $total_tokens_non_format = $fetch_tokens['amount'];
                    $total_tokens=round($total_tokens_non_format*0.8);
        
                }
                
            }
            $get_total_score = $conn->prepare("SELECT SUM(score) FROM score_activity WHERE MONTH(date)=MONTH(?) AND YEAR(date)=YEAR(?)"); 
            $get_total_score->execute([$fullDate, $fullDate]);
            if($get_total_score->rowCount() > 0){
            while($fetch_total_score= $get_total_score->fetch(PDO::FETCH_ASSOC)) {
            $total_score = $fetch_total_score['SUM(score)'];}}
            if ($total_score>0){
            $get_user_score = $conn->prepare("SELECT Users_user_id, SUM(score) AS total_score
            FROM score_activity
            WHERE MONTH(date) = MONTH(?)  AND YEAR(date) = YEAR(?)
            GROUP BY Users_user_id;"); 
            $get_user_score->execute([$fullDate, $fullDate]);
            if($get_user_score->rowCount() > 0){
            while($fetch_user_score= $get_user_score->fetch(PDO::FETCH_ASSOC)) {

                $user_score = $fetch_user_score['total_score'];
                $user_percentage = $user_score/$total_score;
                $user_tokens = $user_percentage*$total_tokens;
                $user_tokens_round = round($user_tokens);
                $user_id = $fetch_user_score['Users_user_id'];
                $lastDayOfMonth = new DateTime("$missingMonth-01");
                $lastDayOfMonth->modify('last day of this month');
                $formattedDate = $lastDayOfMonth->format('Y-m-d');
                $insert_tokens = $conn->prepare("INSERT INTO user_tokens(tokens,Users_user_id,date) VALUES (?,?,?)"); 
                $insert_tokens->execute([$user_tokens_round, $user_id, $formattedDate]);
                
    
            }
        }
        
            } else {
                $lastDayOfMonth = new DateTime("$missingMonth-01");
                $lastDayOfMonth->modify('last day of this month');
                $formattedDate = $lastDayOfMonth->format('Y-m-d');
                $get_user_id = $conn->prepare("SELECT user_id from users");
                $get_user_id->execute();
                if($get_user_id->rowCount() > 0){
                    while($fetch_user_id= $get_user_id->fetch(PDO::FETCH_ASSOC)) {
                        $user_id = $fetch_user_id['user_id'];
                $insert_tokens = $conn->prepare("INSERT INTO user_tokens(tokens,Users_user_id,date) VALUES (0,?,?)"); 
                $insert_tokens->execute([$user_id, $formattedDate]);
                
            }}
            }
        
        }}
    

}else{
    foreach ($missingMonths as $missingMonth) { 
        
            $fullDate = $missingMonth . "-01";
            $get_tokens = $conn->prepare("SELECT amount FROM system_tokens WHERE creation_date=?"); 
            $get_tokens->execute([$fullDate]);
            if($get_tokens->rowCount() > 0){
                while($fetch_tokens= $get_tokens->fetch(PDO::FETCH_ASSOC)) {
                    $total_tokens_non_format = $fetch_tokens['amount'];
                    $total_tokens=round($total_tokens_non_format*0.8);
        
                }
            }
            $get_total_score = $conn->prepare("SELECT SUM(score) FROM score_activity WHERE MONTH(date)=MONTH(?) AND YEAR(date)=YEAR(?)"); 
            $get_total_score->execute([$fullDate, $fullDate]);
            if($get_total_score->rowCount() > 0){
            while($fetch_total_score= $get_total_score->fetch(PDO::FETCH_ASSOC)) {
            $total_score = $fetch_total_score['SUM(score)'];}}
            $get_user_score = $conn->prepare("SELECT Users_user_id, SUM(score) AS total_score
            FROM score_activity
            WHERE MONTH(date) = MONTH(?)  AND YEAR(date) = YEAR(?)
            GROUP BY Users_user_id;"); 
            $get_user_score->execute([$fullDate, $fullDate]);
            if($get_user_score->rowCount() > 0){
            while($fetch_user_score= $get_user_score->fetch(PDO::FETCH_ASSOC)) {
                $user_score = $fetch_user_score['total_score'];
                $user_percentage = $user_score/$total_score;
                $user_tokens = $user_percentage*$total_tokens;
                $user_tokens_round = round($user_tokens);
                $user_id = $fetch_user_score['Users_user_id'];
                $lastDayOfMonth = new DateTime("$missingMonth-01");
                $lastDayOfMonth->modify('last day of this month');
                $formattedDate = $lastDayOfMonth->format('Y-m-d');
                $insert_tokens = $conn->prepare("INSERT INTO user_tokens(tokens,Users_user_id,date) VALUES (?,?,?)"); 
                $insert_tokens->execute([$user_tokens_round, $user_id, $formattedDate]);
                
            }
        
    } else {
        $get_user_id = $conn->prepare("SELECT user_id from users");
        $lastDayOfMonth = new DateTime("$missingMonth-01");
        $lastDayOfMonth->modify('last day of this month');
        $formattedDate = $lastDayOfMonth->format('Y-m-d');
                $get_user_id->execute();
                if($get_user_id->rowCount() > 0){
                    while($fetch_user_id= $get_user_id->fetch(PDO::FETCH_ASSOC)) {
                        $user_id = $fetch_user_id['user_id'];
                $insert_tokens = $conn->prepare("INSERT INTO user_tokens(tokens,Users_user_id,date) VALUES (0,?,?)"); 
                $insert_tokens->execute([$user_id, $formattedDate]);
                
            }}

    }
    
            }}
        


?>