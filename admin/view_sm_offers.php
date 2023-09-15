<?php

include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_style.css">



</head>

<body>

    <?php include '../components/admin_header.php'; ?>


    <section class="home-products">
        <h1 class="heading">Προσφορές για το supermarket: </h1>

        <div class="swiper products-slider">

            <div class="swiper-wrapper">
                <?php

                function get_current_date()
                {
                    $current_date = new DateTime();
                    $current_date_f = $current_date->format('Y-m-d');

                    return $current_date_f;
                }

                $current_date = get_current_date();
                $sid = 0;
                if (isset($_GET["sid"])) {
                    $sid = $_GET["sid"];
                }

                $select_offers = $conn->prepare("SELECT offers.expiration_date, offers.out_of_stock, offers.creation_date, offers.offer_id, product.product_id, product.product_name, offers.product_price, product.product_image ,supermarket.supermarket_name,supermarket.supermarket_address,users.username, offers.total_likes, offers.total_dislikes FROM offers,product,supermarket,users WHERE supermarket.supermarket_id=? AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.product_product_id=product.product_id  AND offers.Users_user_id=users.user_id AND offers.product_product_id=product.product_id");
                $select_offers->execute([$sid]);
                if ($select_offers->rowCount() > 0) {
                    while ($fetch_product = $select_offers->fetch(PDO::FETCH_ASSOC)) {
                        $offer_exp = $fetch_product['expiration_date'];
                        if ($current_date < $offer_exp) {
                ?>





                            <form action="" method="post" class="swiper-slide slide">
                                <input type="hidden" name="oid" value="<?= $fetch_product['offer_id']; ?>">
                                <input type="hidden" name="pid" value="<?= $fetch_product['product_id']; ?>">
                                <input type="hidden" name="name" value="<?= $fetch_product['product_name']; ?>">
                                <input type="hidden" name="price" value="<?= $fetch_product['product_price']; ?>">
                                <input type="hidden" name="image" value="<?= $fetch_product['product_image']; ?>">
                                <input type="hidden" name="supermarket_name" value="<?= $fetch_product['supermarket_name']; ?>">
                                <input type="hidden" name="supermarket_address" value="<?= $fetch_product['supermarket_address']; ?>">
                                <input type="hidden" name="creation_date" value="<?= $fetch_product['creation_date']; ?>">
                                <input type="hidden" name="stock" value="<?= $fetch_product['out_of_stock']; ?>">
                                <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                                <a href="quick_view.php?oid=<?= $fetch_product['offer_id']; ?>" class="fas fa-eye"></a>
                                <img src="../uploaded_img/<?= $fetch_product['product_image']; ?>" alt="">
                                <div class="name"><?= $fetch_product['product_name']; ?></div>
                                <?php $stock = $fetch_product['out_of_stock'];
                                if ($stock === 'false') {
                                    $has_stock = 'Υπάρχει απόθεμα';
                                } else {
                                    $has_stock = 'Δεν υπάρχει απόθεμα';
                                } ?>
                                <div class="stock"><?= $has_stock ?></div>
                                <div class="flex">
                                    <div class="price"><?= $fetch_product['product_price']; ?><span>€ στο supermarket: </span><br><?= $fetch_product['supermarket_name']; ?><span>, </span><?= $fetch_product['supermarket_address']; ?></div>

                                </div>
                                <div class="down-part">
                                    <div class="author"><span>Δημιουργήθηκε από: </span><?= $fetch_product['username']; ?> την ημερομηνία: <?= $fetch_product['creation_date']; ?></div>

                                    <div class="likes">
                                        <img src="../images/like.png" alt="like">
                                        <?= $fetch_product['total_likes']; ?>
                                        <img src="../images/dislike.png" alt="dislike">
                                        <?= $fetch_product['total_dislikes']; ?>
                                    </div>
                                </div>
                            </form>
                <?php
                        }
                    }
                } else {
                    echo '<p class="empty">Δεν υπάρχουν διαθέσιμες προσφορές!</p>';
                }
                ?>

            </div>

            <div class="swiper-pagination"></div>

        </div>

    </section>

    <!-- custom js file link  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/admin_map.js"></script>
    <script src="../js/admin_view_offers.js"></script>



</body>

</html>