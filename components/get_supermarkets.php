<?php
include 'connect.php';


$get_markets = $conn->prepare("SELECT supermarket.supermarket_id, supermarket.supermarket_name, supermarket.x_coord, supermarket.y_coord, supermarket.has_offers, supermarket.supermarket_address  FROM supermarket "); 
$get_markets->execute();
if($get_markets->rowCount() > 0)

{

$rows = array();
while($fetch_markets = $get_markets->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = $fetch_markets;
}
echo json_encode($rows);
}

?>
