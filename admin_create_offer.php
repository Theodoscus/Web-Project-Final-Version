<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

function select_product_id($product_name)
{
    include 'components/connect.php';
    $select_product_id = $conn->prepare("SELECT product_id FROM  product WHERE product_name = ?");
    $select_product_id->execute([$product_name]);
    if ($select_product_id->rowCount() > 0) {
        while ($fetch_product_id = $select_product_id->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $fetch_product_id['product_id'];
        }
        return $product_id;
    }
}

function select_product_price($product_id, $sid)
{
    include 'components/connect.php';
    $select_product_price = $conn->prepare("SELECT offers.product_price FROM  offers WHERE offers.product_product_id = ? AND offers.supermarket_supermarket_id=? ORDER BY product_price ASC LIMIT 1");
    $select_product_price->execute([$product_id, $sid]);
    if ($select_product_price->rowCount() > 0) {
        while ($fetch_product_price = $select_product_price->fetch(PDO::FETCH_ASSOC)) {
            $product_price_fetch = $fetch_product_price['product_price'];
        }
        return $product_price_fetch;
    } else {
        return INF;
    }
}

function get_current_date()
{
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    return $current_date_f;
}

function get_last_day_date()
{
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    $last_day = $current_date->modify('-1 day');
    $last_day_f = $last_day->format('Y-m-d');
    return $last_day_f;
}

function get_last_week_date()
{
    $current_date = new DateTime();
    $current_date_f = $current_date->format('Y-m-d');
    $last_week = $current_date->modify('-1 week');
    $last_week_f = $last_week->format('Y-m-d');
    return $last_week_f;
}

function get_avg_day_price($product_id, $last_day)
{
    include 'components/connect.php';
    $select_product_avg_day_price = $conn->prepare("SELECT offers.product_price FROM  offers WHERE offers.product_product_id = ? AND offers.creation_date=? ");
    $select_product_avg_day_price->execute([$product_id, $last_day]);
    $total_price = 0;
    $total_prods = 0;
    if ($select_product_avg_day_price->rowCount() > 0) {
        while ($fetch_product_avg_day_price = $select_product_avg_day_price->fetch(PDO::FETCH_ASSOC)) {
            $total_price += $fetch_product_avg_day_price['product_price'];
            $total_prods += 1;
        }
        $avg_day_price = $total_price / $total_prods;
        return $avg_day_price;
    } else {
        return INF;
    }
}

function get_avg_week_price($product_id, $last_week)
{
    include 'components/connect.php';
    $select_product_avg_week_price = $conn->prepare("SELECT offers.product_price FROM  offers WHERE offers.product_product_id = ? AND offers.creation_date=? ");
    $select_product_avg_week_price->execute([$product_id, $last_week]);
    $total_price = 0;
    $total_prods = 0;
    if ($select_product_avg_week_price->rowCount() > 0) {
        while ($fetch_product_avg_week_price = $select_product_avg_week_price->fetch(PDO::FETCH_ASSOC)) {
            $total_price += $fetch_product_avg_week_price['product_price'];
            $total_prods += 1;
        }
        $avg_week_price = $total_price / $total_prods;
        return $avg_week_price;
    } else {
        return INF;
    }
}

function insert_offer($note, $admin_id, $product_price, $product_id, $supermarket_id)
{
    include 'components/connect.php';
    $insert_user = $conn->prepare("INSERT INTO `offers`(note, Users_user_id, product_price, product_product_id, creation_date, out_of_stock, supermarket_supermarket_id, total_likes, total_dislikes, expiration_date) VALUES(?,?,?,?,CURDATE(),'false',?,0,0,DATE_ADD(CURDATE(), INTERVAL 7 DAY))");
    $insert_user->execute([$note, $admin_id, $product_price, $product_id, $supermarket_id]);
    $update_sm = $conn->prepare("UPDATE supermarket SET has_offers=1 WHERE supermarket_id=?");
    $update_sm->execute([$supermarket_id]);
    $select_offers_id = $conn->prepare("SELECT offers.offer_id FROM  offers WHERE offers.Users_user_id = ? AND offers.product_product_id=? ORDER BY offers.offer_id DESC ");
    $select_offers_id->execute([$admin_id, $product_id]);
    $fetch_offers_id = $select_offers_id->fetch(PDO::FETCH_ASSOC);
    $oid = $fetch_offers_id['offer_id'];
    return $oid;
}


if (isset($_POST['submit'])) {
    if (isset($_GET["sid"])) {
        $sid = $_GET["sid"];
    }
    $product_name = $_POST['offer_product'];
    $offer_price = $_POST['offer_price'];
    $offer_note = $_POST['offer_note'];
    $product_id = select_product_id($product_name);
    $product_price_fetch = select_product_price($product_id, $sid);
    if ($offer_price <= 0.8 * $product_price_fetch) {
        $current_date = get_current_date();
        $last_day = get_last_day_date();
        $last_week = get_last_week_date();
        $avg_day_price = get_avg_day_price($product_id, $last_day);
        $avg_week_price = get_avg_week_price($product_id, $last_week);

        $oid = insert_offer($offer_note, $admin_id, $offer_price, $product_id, $sid);

        $message[] = 'Επιτυχής δημοσίευση προσφοράς!';
        sleep(2);
        header('location:admin/admin_map.php');
    } else {
        $message[] = 'Υπάρχει ήδη προσφορά για το συγκεκριμένο προιόν με παραπλήσια τιμή!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create offer</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <header class="header">

        <section class="flex">

            <a href="../admin/products.php" class="logo">Admin<span>Panel</span></a>

            <nav class="navbar">
                <a href="admin/products.php">Προϊόντα</a>
                <a href="admin/statics.php">Στατιστικά</a>
                <a href="admin/leaderboard.php">Leaderboard</a>
                <a href="admin/stores.php">Καταστήματα</a>
                <a href="admin/admin_map.php">Χάρτης</a>
            </nav>

            <a href="components/admin_logout.php" class="admin-logout-button" onclick="return confirm('logout from the website?');">Αποσύνδεση</a>

        </section>

    </header>
    <section class="form-container">
        <?php
        $sid = 0;
        if (isset($_GET["sid"])) {
            $sid = $_GET["sid"];
        }

        $select_products = $conn->prepare("SELECT x_coord, y_coord, supermarket_name, supermarket_address FROM supermarket WHERE supermarket_id= ?");
        $select_products->execute([$sid]);
        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                $x_coord = $fetch_product['x_coord'];
                $y_coord = $fetch_product['y_coord'];
                $_SESSION['x_coord'] = $x_coord;
                $_SESSION['y_coord'] = $y_coord;
        ?>
                <form action="" method="post">
                    <input type="hidden" name="supermarket_name" value="<?= $fetch_product['supermarket_name']; ?>">
                    <input type="hidden" name="supermarket_address" value="<?= $fetch_product['supermarket_address']; ?>">
                    <h3>Συμπληρώστε μια προσφορά!</h3>
                    <h2> Supermarket: <?= $fetch_product['supermarket_name']; ?>, <?= $fetch_product['supermarket_address']; ?></h2>
                    <div class="custom-dropdown">
                        <input type="text" name="offer_product" id="search" placeholder="Αναζήτηση προιόντος...">
                        <div class="dropdown">
                            <div class="selected-option">Select an option</div>
                            <ul class="options">
                                <!-- The dropdown options will be populated dynamically from the database -->
                            </ul>
                        </div>
                    </div>
                    <input type="price" name="offer_price" required placeholder="Εισάγετε την τιμή της προσφοράς" maxlength="10" class="box">
                    <input type="text" name="offer_note" required placeholder="Εισάγετε λεπτομέρειες ή διευκρινίσεις" maxlength="50" class="box">

                    <input type="submit" value="Συμπλήρωση προσφοράς" class="btn" id="submit_offer_button" name="submit">

                </form>

                <div id="overlay"></div>

                <!-- Modal dialog -->
                <div id="modalDialog">
                    <h2>No Access</h2>
                    <p>You don't have permission to access this page.</p>

                </div>

    </section>
<?php }
        } ?>



<script src="js/script.js"></script>
<script src="js/get_access.js"></script>
<script src="js/admin_fetch_products.js"></script>






</body>

</html>