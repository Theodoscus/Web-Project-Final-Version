<?php
include 'connect.php';

$get_markets = $conn->prepare("SELECT product_id, product_name FROM product"); 
$get_markets->execute();
if($get_markets->rowCount() > 0)

{

$data = array();
while($fetch_markets = $get_markets->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $fetch_markets;
}
echo json_encode($data);
}

?>