
<?php
include 'connect.php';


$get_markets = $conn->prepare("SELECT
s.supermarket_id,
s.supermarket_name,
s.supermarket_address,
s.has_offers,
s.x_coord,
s.y_coord,
COUNT(CASE WHEN c.category_name = 'Αντισηπτικά' THEN o.offer_id END) AS Sanitizers,
COUNT(CASE WHEN c.category_name = 'Βρεφικά Είδη' THEN o.offer_id END) AS Baby,
COUNT(CASE WHEN c.category_name = 'Για κατοικίδια' THEN o.offer_id END) AS Pets,
COUNT(CASE WHEN c.category_name = 'Καθαριότητα' THEN o.offer_id END) AS Cleaning,
COUNT(CASE WHEN c.category_name = 'Ποτά - Αναψυκτικά' THEN o.offer_id END) AS Drinks,
COUNT(CASE WHEN c.category_name = 'Προστασία Υγείας' THEN o.offer_id END) AS Health,
COUNT(CASE WHEN c.category_name = 'Προσωπική Φροντίδα' THEN o.offer_id END) AS Care,
COUNT(CASE WHEN c.category_name = 'Τρόφιμα' THEN o.offer_id END) AS Food
    
FROM
    supermarket s
LEFT JOIN
    offers o ON s.supermarket_id = o.supermarket_supermarket_id
LEFT JOIN
    product p ON o.product_product_id = p.product_id
LEFT JOIN
    subcategory sc ON p.subcategory_subcategory_id = sc.subcategory_id
LEFT JOIN
    category c ON sc.category_category_id = c.category_id
GROUP BY
    s.supermarket_id, s.supermarket_name, s.x_coord, s.y_coord;"); 
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


