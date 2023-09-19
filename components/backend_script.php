<?php


//Fetching data

$request = 0;
if(isset($_POST['request'])){
    $request = $_POST['request'];
 }

 if($request == 1){
  
    $categoryid = $_POST['categoryid'];

    $stmt = $conn->prepare("SELECT * FROM subcategory WHERE category_category_id=:categoryId ORDER BY subcategory_name");
    $stmt->bindValue(':categoryId', $categoryid, PDO::PARAM_STR);

    $stmt->execute();
    $subcategoryList = $stmt->fetchAll();

    $response = array();

    foreach($subcategoryList as $subcategory){
        $response[] = array(
          "subcategory_id" => $subcategory['subcategory_id'],
          "subcategory_name" => $subcategory['subcategory_name']
        );
    }
    echo json_encode($response);
   exit;
 }
?>