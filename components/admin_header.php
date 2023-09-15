<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">

    <section class="flex">

        <a href="../admin/products.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="../admin/products.php">Προϊόντα</a>
            <a href="../admin/statics.php">Στατιστικά</a>
            <a href="../admin/leaderboard.php">Leaderboard</a>
            <a href="../admin/stores.php">Καταστήματα</a>
            <a href="../admin/admin_map.php">Χάρτης</a>
        </nav>

        <a href="../components/admin_logout.php" class="admin-logout-button" onclick="return confirm('logout from the website?');">Αποσύνδεση</a>

    </section>

</header>