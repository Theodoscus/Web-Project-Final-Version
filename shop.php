<?php

include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:user_login.php');
}


if(isset($_POST['submit'])) {
    if (!empty($_POST['subcategory_select'])){
    
        $selected = $_POST['subcategory_select'];
        header('location:subcategory_products.php');
        $_SESSION['subcategoryId'] = $selected;
    
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
   <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
 
   
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="background-category-select">
    <div class="category-select-container">
    <form action="" method="post" id="subcategory_form" >  
        <p class="products-title">Προιόντα</p>
        <p class="instructions-text">Πρώτα επιλέξτε την κατηγορία, έπειτα την υποκατηγορία και επιλέξτε αναζήτηση για να δείτε τα αποτελέσματα.</p>
        <div class="cat-subc-selection">
            <div id="category" class="category-select">
                <div class="category-box">    
                <p class="category-text">Κατηγορία  </p>
                    
                    <div  class="cat-dropdown">
                        <div class="category-select-menu"  >
                        <select name="category_select" id="category_select"  style="width:300px;">
                        <option selected disabled value='0'>Επιλέξτε Κατηγορία</option>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM category ORDER BY category_name");
                            $stmt->execute();
                            $categoriesList= $stmt->fetchAll();

                            foreach($categoriesList as $category){
                                echo "<option value='".$category['category_id']."'>".$category['category_name']."</option>";
                            }
        
                        ?>
                        </select>         
                        </div>
                    </div> 
                </div>  
                
                <div class="category-box">   
                    <p class="category-text">Υπό-κατηγορία  </p>
                    <div  class="cat-dropdown">
                        <div class="category-select-menu"  >
                        <select name="subcategory_select" id="subcategory_select" style="width:300px;">
                        <option selected disabled value='0'>Επιλέξτε Υποκατηγορία</option>
                        </select>           
                        </div>
                    </div>     
                </div>
                <div>          
                <button class="search-button"  type="submit"  name="submit">Αναζήτηση </button>
        </div>
            </div>
            
        </div>
        
    </form>
    </div>
</div>




<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/script.js"></script>
<script src="js/ajax_script_dropdown.js"   ></script>



</body>
</html>
