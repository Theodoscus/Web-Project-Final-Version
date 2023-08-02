<?php 
include 'connect.php';
function get_current_date(){
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    return $current_date_f;
 }
 
 function get_last_day_date(){
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    $last_day = $current_date->modify('-1 day');
    $last_day_f = $last_day->format('Y-m-d');
    return $last_day_f;
 }
 
 function get_last_week_date(){
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    $last_week = $current_date->modify('-1 week');
    $last_week_f = $last_week->format('Y-m-d');
    return $last_week_f;
 }
 
 function get_avg_day_price($product_id, $last_day){
    include 'components/connect.php';
    $select_product_avg_day_price = $conn->prepare("SELECT offers.product_price FROM  offers WHERE offers.product_product_id = ? AND offers.creation_date=? "); 
             $select_product_avg_day_price->execute([$product_id, $last_day]);
             $total_price=0;
             $total_prods=0;
             if($select_product_avg_day_price->rowCount() > 0){
             while($fetch_product_avg_day_price = $select_product_avg_day_price->fetch(PDO::FETCH_ASSOC)){
                $total_price += $fetch_product_avg_day_price['product_price'];
                $total_prods += 1;
                
                
             }
             $avg_day_price = $total_price/$total_prods;
             return $avg_day_price;
          } else {
             return INF;
          }
 }
 
 function get_avg_week_price($product_id, $last_week){
    include 'components/connect.php';
    $select_product_avg_week_price = $conn->prepare("SELECT offers.product_price FROM  offers WHERE offers.product_product_id = ? AND offers.creation_date=? "); 
             $select_product_avg_week_price->execute([$product_id, $last_week]);
             $total_price=0;
             $total_prods=0;
             if($select_product_avg_week_price->rowCount() > 0){
             while($fetch_product_avg_week_price = $select_product_avg_week_price->fetch(PDO::FETCH_ASSOC)){
                $total_price += $fetch_product_avg_week_price['product_price'];
                $total_prods += 1;
                
                
             }
             $avg_week_price = $total_price/$total_prods;
             return $avg_week_price;
          } else {
             return INF;
          }
 }

 function update_offers_exp($oid, $exp_date){
    include 'components/connect.php';
    $update_offer = $conn->prepare("UPDATE offers SET expiration_date=");
    $insert_score->execute([$score, $user_id, $action_type,$oid]);
   
}
// Retrieve all the items
$sql = "SELECT * FROM offers";
$result = $conn->query($sql);

if($result->rowCount() > 0){
    while($fetch_result = $result->fetch(PDO::FETCH_ASSOC)){
        $oid = $fetch_result["offer_id"];
        $price = $fetch_result["product_price"];
        $pid = $fetch_result["product_product_id"];
        $expiration_date = $fetch_result["expiration_date"];
        $current_date=get_current_date();
        $last_day=get_last_day_date();
        $last_week=get_last_week_date();
        $avg_day_price=get_avg_day_price($pid, $last_day);
        $avg_week_price=get_avg_week_price($pid, $last_week);
        

        // Check if the item needs to be updated
        if ($current_date >= $expiration_date) {
            if ($price <= 0.8 * $avg_day_price){
               $update_offers_exp = $conn->prepare("UPDATE offers SET expiration_date=DATE_ADD(expiration_date, INTERVAL 7 DAY) WHERE offer_id=? "); 
               $update_offers_exp->execute([$oid]);
            }else if($price <= 0.8 * $avg_week_price){
               $update_offers_exp = $conn->prepare("UPDATE offers SET expiration_date=DATE_ADD(expiration_date, INTERVAL 7 DAY) WHERE offer_id=? "); 
               $update_offers_exp->execute([$oid]);
          }
            

            
        }
    }
}



?>