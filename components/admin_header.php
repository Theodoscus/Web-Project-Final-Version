<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">

    <section class="flex">

        <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="../admin/products.php">Προϊόντα</a>
            <a href="../admin/statics.php">Στατιστικά</a>
            <a href="../admin/leaderboard.php">Leaderboard</a>
            <a href="../admin/stores.php">Καταστήματα</a>
            <a href="../admin/map.php">Χάρτης</a>
        </nav>

        <div class="icons">
    <div id="user-btn" class="fas fa-user"></div>
</div>

<div class="profile">
         <?php
$select_admin = $conn->prepare('SELECT * FROM `users` WHERE user_id = ? ');
$select_admin->execute([$user_id]);
if ($select_admin->rowCount() > 0) {
    $fetch_profile = $select_admin->fetch(PDO::FETCH_ASSOC);
    ?>
         <p><?php echo $fetch_profile['username']; ?></p>
         <a href="admin_login.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
         <?php
} else {
    ?>
         <p>please login !</p>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn">login</a>
         </div>
         <?php
}
?>      
         
         
      </div>
         
         
      </div>



    </section>

</header>
