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

    <!-- here we create detailed bidding history of the player  -->
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
echo '<tr><td class="total-score" colspan="2">'.$total_likes.'</td></tr>';
echo '</table>';

// here we create "Total score from likes/deslikes for the user from this month"
// Get the first and last day of the current month
$first_day = date('Y-m-01');
$last_day = date('Y-m-t');

// Query to calculate the total likes/deslikes score from this month
$sql_month = 'SELECT
                    offers.total_likes * 5 - offers.total_dislikes AS month_likes
                FROM offers
                JOIN product ON offers.product_product_id = product.product_id
                JOIN likeactivity ON likeactivity.offers_offer_id = offers.offer_id
                WHERE offers.Users_user_id = :user_id
                AND likeactivity.date >= :first_day
                AND likeactivity.date <= :last_day
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

    <!-- here we create "Total score from History for the user from This month" -->
    <section class="score-activity">
        <?php
// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Retrieve today's date without hours and minutes
$today = date('Y-m-d');

// Calculate the date 1 day before
$one_day_ago = date('Y-m-d', strtotime('-1 day', strtotime($today)));

// Calculate the date 7 days before
$seven_days_ago = date('Y-m-d', strtotime('-7 days', strtotime($today)));

// Calculate the first day of the current month
$first_day_of_month = date('Y-m-01');

// Query to get the average price for the offers in the specified date range and product for 1 day ago
$sql_avg_price_1day = 'SELECT product.product_id, ROUND(AVG(offers.product_price), 2) AS average_price
                      FROM offers
                      JOIN product ON offers.product_product_id = product.product_id
                      WHERE DATE(offers.creation_date) >= :one_day_ago AND DATE(offers.creation_date) <= :today
                      GROUP BY product.product_id';

$stmt_avg_price_1day = $conn->prepare($sql_avg_price_1day);
$stmt_avg_price_1day->bindParam(':one_day_ago', $one_day_ago, PDO::PARAM_STR);
$stmt_avg_price_1day->bindParam(':today', $today, PDO::PARAM_STR);
$stmt_avg_price_1day->execute();

// Query to get the average price for the offers in the specified date range and product for 7 days ago
$sql_avg_price_7day = 'SELECT product.product_id, ROUND(AVG(offers.product_price), 2) AS average_price
                      FROM offers
                      JOIN product ON offers.product_product_id = product.product_id
                      WHERE DATE(offers.creation_date) >= :seven_days_ago AND DATE(offers.creation_date) <= :today
                      GROUP BY product.product_id';

$stmt_avg_price_7day = $conn->prepare($sql_avg_price_7day);
$stmt_avg_price_7day->bindParam(':seven_days_ago', $seven_days_ago, PDO::PARAM_STR);
$stmt_avg_price_7day->bindParam(':today', $today, PDO::PARAM_STR);
$stmt_avg_price_7day->execute();

// Initialize the history_cal variable for the entire history
$history_cal = 0;

// Initialize the month_history_cal variable for this month
$month_history_cal = 0;
$check_offer_1day = 0;

while ($row_avg_price_1day = $stmt_avg_price_1day->fetch(PDO::FETCH_ASSOC)) {
    $product_id = $row_avg_price_1day['product_id'];
    $average_price_1day = $row_avg_price_1day['average_price'];

    // Query to get offers from today for the specific user and product
    $sql_check_offer_1day = 'SELECT product_price
                             FROM offers
                             WHERE Users_user_id = :user_id
                             AND product_product_id = :product_id
                             AND DATE(offers.creation_date) = :today';

    $stmt_check_offer_1day = $conn->prepare($sql_check_offer_1day);
    $stmt_check_offer_1day->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_check_offer_1day->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt_check_offer_1day->bindParam(':today', $today, PDO::PARAM_STR);
    $stmt_check_offer_1day->execute();

    // Check each $check_offer and update history_cal and month_history_cal accordingly (1 day ago)
    while ($row_check_offer_1day = $stmt_check_offer_1day->fetch(PDO::FETCH_ASSOC)) {
        $check_offer_1day = $row_check_offer_1day['product_price'];

        // Check if the offer's price is 20% less than the average price for that specific product (1 day ago)
        if ($check_offer_1day <= $average_price_1day * 0.8) {
            $history_cal += 50; // Add 50 to history_cal if the condition is met
            // Check if the offer's price is 20% less than the average price for that specific product (within this month)
            if (substr($today, 0, 7) == substr($one_day_ago, 0, 7)) {
                $month_history_cal += 50; // Add 50 to month_history_cal if the condition is met
            }
        }
    }

    // Check the average price for 7 days ago
    if ($row_avg_price_7day = $stmt_avg_price_7day->fetch(PDO::FETCH_ASSOC)) {
        $average_price_7day = $row_avg_price_7day['average_price'];

        // Check if the offer's price is 20% less than the average price for that specific product (7 days ago)
        if ($check_offer_1day <= $average_price_7day * 0.8) {
            $history_cal += 20; // Add 20 to history_cal if the condition is met
            // Check if the offer's price is 20% less than the average price for that specific product (within this month)
            if (substr($today, 0, 7) == substr($seven_days_ago, 0, 7)) {
                $month_history_cal += 20; // Add 20 to month_history_cal if the condition is met
            }
        }
    }
}

// Create the HTML table for the total score and product name
echo '<table>';
echo '<tr><th>Score from HISTORY </th></tr>';
echo '<tr><td>Total History Score: '.$history_cal.'</td></tr>';
echo '<tr><td>History Score for this Month: '.$month_history_cal.'</td></tr>';
echo '</table>';

?>
    </section>
    
    </section>


    <!-- here we create "Total score for the user " -->
    <section class="score-activity">
        <?php
// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// -------------------------------------------------------------------------------------

// Get the current date
$current_date = date('Y-m-d');

// Calculate the total score for this month
$month_score = $month_likes + $month_history_cal;

// Calculate the total score for the entire history
$total_Score = $history_cal + $total_likes;

// Check if there's an existing entry for this user and the current month
$sql_check_existing = 'SELECT score_id, month_score, date FROM score_activity WHERE Users_user_id = :user_id ORDER BY date DESC LIMIT 1';
$stmt_check_existing = $conn->prepare($sql_check_existing);
$stmt_check_existing->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check_existing->execute();
$existing_entry = $stmt_check_existing->fetch(PDO::FETCH_ASSOC);

// Get the current month
$current_month = date('Y-m', strtotime($current_date));

if ($existing_entry) {
    // If an entry exists, check if the current date has the same month as the last entry
    $last_entry_date = $existing_entry['date'];
    $last_entry_month = date('Y-m', strtotime($last_entry_date));

    if ($current_month === $last_entry_month) {
        // If it's the same month, update the existing row
        $existing_score_id = $existing_entry['score_id'];
        $sql_update_score = 'UPDATE score_activity SET month_score = :month_score, date = :current_date, score = :total_Score WHERE score_id = :score_id';
        $stmt_update_score = $conn->prepare($sql_update_score);
        $stmt_update_score->bindParam(':month_score', $month_score, PDO::PARAM_INT);
        $stmt_update_score->bindParam(':current_date', $current_date, PDO::PARAM_STR);
        $stmt_update_score->bindParam(':total_Score', $total_Score, PDO::PARAM_INT);
        $stmt_update_score->bindParam(':score_id', $existing_score_id, PDO::PARAM_INT);
        $stmt_update_score->execute();
    } else {
        // If it's a new month, insert a new row and update the current score_id
        $sql_insert_score = 'INSERT INTO score_activity (Users_user_id, month_score, date, score) VALUES (:user_id, :month_score, :current_date, :total_Score)';
        $stmt_insert_score = $conn->prepare($sql_insert_score);
        $stmt_insert_score->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_insert_score->bindParam(':month_score', $month_score, PDO::PARAM_INT);
        $stmt_insert_score->bindParam(':current_date', $current_date, PDO::PARAM_STR);
        $stmt_insert_score->bindParam(':total_Score', $total_Score, PDO::PARAM_INT);
        $stmt_insert_score->execute();
    }
} else {
    // If there's no existing entry for this user, insert a new row
    $sql_insert_score = 'INSERT INTO score_activity (Users_user_id, month_score, date, score) VALUES (:user_id, :month_score, :current_date, :total_Score)';
    $stmt_insert_score = $conn->prepare($sql_insert_score);
    $stmt_insert_score->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_insert_score->bindParam(':month_score', $month_score, PDO::PARAM_INT);
    $stmt_insert_score->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt_insert_score->bindParam(':total_Score', $total_Score, PDO::PARAM_INT);
    $stmt_insert_score->execute();
}

// ------------------------------------------------------------------------------------

// Create the HTML table for the total score and product name
echo '<table>';
echo '<tr><th>Score</th></tr>';
echo '<tr><td>Total Score: '.$total_Score.'</td></tr>';
echo '<tr><td>Monthly Score: '.$month_score.'</td></tr>';
echo '</table>';

?>
    </section>

        <!-- here we create Tokens from Beginnig -->
        <section class="score-activity">
        <?php
// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Query to get the count of user IDs
$sql_num_users = 'SELECT COUNT(users.user_id) AS num_users
                  FROM users
                  WHERE users.user_type = "user" AND users.signup_date IS NOT NULL';

$stmt_num_users = $conn->prepare($sql_num_users);
$stmt_num_users->execute();

// Fetch the result
$row_num_users = $stmt_num_users->fetch(PDO::FETCH_ASSOC);
$num_users = $row_num_users['num_users'];

// Calculate month_tokens
$month_tokens = $num_users * 50;
$month_tokens80 = $month_tokens * 0.8;
echo '<tr><td>Monthly Tokesn: '.$month_tokens.', Number of users : '.$num_users.' , 80% of total_tokens:  '.$month_tokens80.' </td></tr>';

?>
    </section>

      <!-- here we create Tokens from Previous Month -->
      <section class="score-activity">
        <?php
// Retrieve the user ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

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