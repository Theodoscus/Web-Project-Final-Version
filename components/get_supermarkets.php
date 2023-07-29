
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

SELECT
    s.supermarket_name,
    s.x_coord,
    s.y_coord,
    COUNT(CASE WHEN c.category_name = 'Αντισηπτικά' THEN o.offer_id END) AS Category1_Count
    
FROM
    supermarket s
JOIN
    offers o ON s.supermarket_id = o.supermarket_supermarket_id
JOIN
    product p ON o.product_product_id = p.product_id
JOIN
    subcategory sc ON p.subcategory_subcategory_id = sc.subcategory_id
JOIN
    category c ON sc.category_category_id = c.category_id
GROUP BY
    s.supermarket_id, s.supermarket_name, s.x_coord, s.y_coord
