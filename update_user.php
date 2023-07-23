<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $update_profile = $conn->prepare('UPDATE `users` SET username = ?, email = ? WHERE user_id = ?');
    $update_profile->execute([$name, $email, $user_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($old_pass == $empty_pass) {
        $message[] = 'please enter old password!';
    } elseif ($old_pass != $prev_pass) {
        $message[] = 'old password not matched!';
    } elseif ($new_pass != $cpass) {
        $message[] = 'confirm password not matched!';
    } else {
        if ($new_pass != $empty_pass) {
            $update_admin_pass = $conn->prepare('UPDATE `users` SET password = ? WHERE user_id = ?');
            $update_admin_pass->execute([$cpass, $user_id]);
            $message[] = 'password updated successfully!';
        } else {
            $message[] = 'please enter a new password!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/user_style.css">


</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="form-container" style="width:50%; float: left;">
        <form action="" method="post">
            <h3>update now</h3>
            <input type="hidden" name="prev_pass" value="<?php echo $fetch_profile['password']; ?>">
            <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?php echo $fetch_profile['username']; ?>">
            <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch_profile['email']; ?>">
            <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="update now" class="btn" name="submit">
        </form>
    </section>



    <section class="home-products" style="width:50%; float: right;">
        <h1 class="tittleOffer">Προσφορές που έχω δημοσιοποιήσει</h1>
        <div class="swiper products-slider">
            <div class="swiper-wrapper">

                <?php
                $select_products = $conn->prepare('SELECT  offers.offer_id,  product.product_name, offers.product_price, product.product_image ,supermarket.supermarket_name,supermarket.supermarket_address,users.username, offers.total_likes, offers.total_dislikes FROM offers,product,supermarket,users WHERE offers.product_product_id=product.product_id AND offers.supermarket_supermarket_id=supermarket.supermarket_id AND offers.Users_user_id=users.user_id AND users.user_id = :user_id ORDER BY offer_id DESC LIMIT 6');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

$select_products->bindParam(':user_id', $user_id);
$select_products->execute();

$select_products->execute();
if ($select_products->rowCount() > 0) {
    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
                        <form action="" method="post" class="swiper-slide slide">
                            <input type="hidden" name="pid" value="<?php echo $fetch_product['offer_id']; ?>">
                            <input type="hidden" name="name" value="<?php echo $fetch_product['product_name']; ?>">
                            <input type="hidden" name="price" value="<?php echo $fetch_product['product_price']; ?>">
                            <input type="hidden" name="image" value="<?php echo $fetch_product['product_image']; ?>">
                            <input type="hidden" name="supermarket_name" value="<?php echo $fetch_product['supermarket_name']; ?>">
                            <input type="hidden" name="supermarket_address" value="<?php echo $fetch_product['supermarket_address']; ?>">
                            <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                            <a href="quick_view.php?oid=<?php echo $fetch_product['offer_id']; ?>" class="fas fa-eye"></a>
                            <img src="uploaded_img/<?php echo $fetch_product['product_image']; ?>" alt="">
                            <div class="name"><?php echo $fetch_product['product_name']; ?></div>
                            <div class="flex">
                                <div class="price"><?php echo $fetch_product['product_price']; ?><span>€ στο supermarket: </span><?php echo $fetch_product['supermarket_name']; ?><span>, </span><?php echo $fetch_product['supermarket_address']; ?></div>

                            </div>
                            <div class="down-part">
                                <div class="author"><span>Created by: </span><?php echo $fetch_product['username']; ?></div>

                                <div class="likes">
                                    <img src="images/like.png" alt="like">
                                    <?php echo $fetch_product['total_likes']; ?>
                                    <img src="images/dislike.png" alt="dislike">
                                    <?php echo $fetch_product['total_dislikes']; ?>
                                </div>
                            </div>
                        </form>
                <?php
    }
} else {
    echo '<p class="empty">Δεν υπάρχουν διαθέσιμες προσφορές!</p>';
}
?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="like-activity">
        <?php
        // Prepare the SQL query to retrieve the user ID
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = '';
        }

        // Prepare the SQL query to retrieve the offer data
        $sql = 'SELECT 
                likeactivity.like_type AS `Like`, 
                product.product_name AS `Product`, 
                users.username AS `Owner`, 
                offers.creation_date AS `Date`
            FROM offers
            JOIN product ON offers.product_product_id = product.product_id
            JOIN likeactivity ON offers.offer_id = likeactivity.offers_offer_id
            JOIN users ON offers.Users_user_id = users.user_id
            WHERE likeactivity.Users_user_id = ?
            ORDER BY offers.offer_id DESC LIMIT 6';

$stmt = $conn->prepare($sql); // Prepare the statement

// Bind the user ID to the prepared statement
$stmt->bindParam(1, $user_id);

// Execute the query
$stmt->execute();

// Create the HTML table
echo '<table>';
echo '<tr><th>Type</th><th>Product Name</th><th>Creator of Offer</th><th>Creation Date Of Product</th></tr>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr><td>'.$row['Like'].'</td><td>'.$row['Product'].'</td><td>'.$row['Owner'].'</td><td>'.$row['Date'].'</td></tr>';
}
echo '</table>';
?>
    </section>

    <!-- here we create "Total score from likes/deslikes for the user from Beginning" -->
    <section class="score-activity">
        <?php
// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Query to calculate the total likes/deslikes score
$sql_total = 'SELECT
                    offers.total_likes * 5 - offers.total_dislikes AS total_likes
                FROM offers
                JOIN product ON offers.product_product_id = product.product_id
                WHERE offers.Users_user_id = :user_id
                ORDER BY product.product_id ASC';

// Prepare and execute the total score query
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_total->execute();

// Calculate the total score for all offers
$total_likes = 0;
while ($row_total = $stmt_total->fetch(PDO::FETCH_ASSOC)) {
    $total_likes += $row_total['total_likes'];
}

// Create the HTML table for the total score and product name
echo '<table>';
echo '<tr><th>Score from likes/dislikes</th></tr>';
while ($row = $stmt_total->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr><td>'.$row['total_likes'].'</td><td>';
}
echo '<tr><td class="total-score" colspan="2">Total score for all the offers from the beginning are: '.$total_likes.'</td></tr>';
echo '</table>';

// Get the first and last day of the current month
$first_day = date('Y-m-01');
$last_day = date('Y-m-t');

// Query to calculate the total likes/deslikes score from this month
$sql_month = 'SELECT
                    offers.total_likes * 5 - offers.total_dislikes AS month_likes
                FROM offers
                JOIN product ON offers.product_product_id = product.product_id
                WHERE offers.Users_user_id = :user_id
                AND offers.creation_date >= :first_day
                AND offers.creation_date <= :last_day
                ORDER BY product.product_id ASC';

// Check if $stmt_month is not set, then prepare and execute the total score query for this month
if (!isset($stmt_month)) {
    $stmt_month = $conn->prepare($sql_month);
}
$stmt_month->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_month->bindParam(':first_day', $first_day, PDO::PARAM_STR);
$stmt_month->bindParam(':last_day', $last_day, PDO::PARAM_STR);
$stmt_month->execute();

// Calculate the total likes and dislikes for this month
$month_likes = 0;
while ($row_month = $stmt_month->fetch(PDO::FETCH_ASSOC)) {
    $month_likes += $row_month['month_likes'];
}

// Create the HTML table for the total score and product name
echo '<table>';
echo '<tr><th>Score from likes/dislikes for this month</th></tr>';
echo '<tr><td>'.$month_likes.'</td></tr>';
echo '</table>';
?>
    </section>


    <!-- here we create "Total score from History for the user from Beginning" -->
    <section class="score-activity">
        <?php
// Initialize the variable to store the total score
$history_cal = 0;

// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Retrieve today's date with hours, minutes, and seconds
$today = date('Y-m-d H:i:s');

// Calculate the date 1 day before
$one_day_ago = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($today)));

// Calculate the date 7 days ago
$seven_days_ago = date('Y-m-d H:i:s', strtotime('-7 days', strtotime($today)));

// Query to get the average price for the offers in the specified date range and product
$sql_avg_price = 'SELECT product.product_id, AVG(offers.product_price) AS average_price
FROM offers
JOIN product ON offers.product_product_id = product.product_id
WHERE DATE(offers.creation_date) >= :seven_days_ago
AND DATE(offers.creation_date) <= :one_day_ago
GROUP BY product.product_id';

$stmt_avg_price = $conn->prepare($sql_avg_price);
$stmt_avg_price->bindParam(':seven_days_ago', $seven_days_ago, PDO::PARAM_STR);
$stmt_avg_price->bindParam(':one_day_ago', $one_day_ago, PDO::PARAM_STR);
$stmt_avg_price->execute();

// Initialize the history_cal variable
$history_cal = 0;

// Check each $check_offer and update history_cal accordingly
while ($row_avg_price = $stmt_avg_price->fetch(PDO::FETCH_ASSOC)) {
    $product_id = $row_avg_price['product_id'];
    $average_price = $row_avg_price['average_price'];

    // Query to get offers that are 20% less than average_price for the specific product
    $sql_check_offer = 'SELECT product_price, creation_date
       FROM offers
       WHERE Users_user_id = :user_id
       AND product_product_id = :product_id
       AND product_price * 0.8 <= :average_price';

    $stmt_check_offer = $conn->prepare($sql_check_offer);
    $stmt_check_offer->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_check_offer->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt_check_offer->bindParam(':average_price', $average_price, PDO::PARAM_INT);
    $stmt_check_offer->execute();

    // Check each $check_offer and update history_cal accordingly
    while ($row_check_offer = $stmt_check_offer->fetch(PDO::FETCH_ASSOC)) {
        $check_offer = $row_check_offer['product_price'];
        $check_offer_date = $row_check_offer['creation_date'];

        // Check if the offer's creation date is 1 day ago
        $one_day_ago_check_offer = date('Y-m-d', strtotime('-1 day', strtotime($check_offer_date)));
        if ($one_day_ago_check_offer === $one_day_ago) {
            $history_cal += 50;
        }

        // Check if the offer's creation date is up to 7 days ago
        $seven_days_ago_check_offer = date('Y-m-d', strtotime('-7 days', strtotime($check_offer_date)));
        if ($check_offer_date <= $seven_days_ago_check_offer) {
            $history_cal += 20;
        }
    }
}

// Create the HTML table for the total score and product name
echo '<table>';
echo '<tr><th>Score from HISTORY for the Beginning</th></tr>';
echo '<tr><td>'.$history_cal.'</td></tr>';
echo '</table>';
?>
    </section>



    <footer style="clear: both;">
        <?php include 'components/footer.php'; ?>
    </footer>


    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

    <script>
        var swiper = new Swiper(".home-slider", {
            loop: true,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        var swiper = new Swiper(".category-slider", {
            loop: false,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                650: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 4,
                },
                1024: {
                    slidesPerView: 5,
                },
            },
        });

        var swiper = new Swiper(".products-slider", {
            loop: false,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                550: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

</body>

</html>