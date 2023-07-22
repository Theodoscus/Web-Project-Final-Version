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

    <!-- here we create the History of like/deslike activity of user -->
    <section class="like-activity">
        <?php
        // Prepare the SQL query to retrieve the user ID
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = '';
        }

        // Prepare the SQL query to retrieve the offer data
        $sql = 'SELECT likeactivity.like_type AS `Like`, product.product_name AS `Product`, offers.creation_date AS `Date`
      FROM offers
JOIN product ON offers.product_product_id = product.product_id
JOIN likeactivity ON offers.offer_id = likeactivity.offers_offer_id
WHERE likeactivity.Users_user_id = ?
ORDER BY offers.offer_id DESC LIMIT 6';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $user_id);

        // Execute the query
        $stmt->execute();

        // Create the HTML table
        echo '<table>';
        echo '<tr><th>Like</th><th>Product</th><th>Date</th></tr>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr><td>' . $row['Like'] . '</td><td>' . $row['Product'] . '</td><td>' . $row['Date'] . '</td></tr>';
        }
        echo '</table>';
        ?>
    </section>


    <!-- here we create the Scoreactivity of every user -->
    <section class="score-activity">
        <?php
        // Retrieve the user ID from the session
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = '';
        }
        // Query to calculate the total score and product name
        $sql_total = 'SELECT offers.product_price, offers.total_likes, offers.total_dislikes,
    SUM(offers.total_likes * 5 - offers.total_dislikes) AS total_score,
    product.product_name
    FROM offers
    JOIN users ON offers.Users_user_id = users.user_id
    JOIN score_activity ON score_activity.Users_user_id = offers.Users_user_id AND score_activity.offers_offer_id = offers.offer_id
    JOIN product ON offers.product_product_id = product.product_id
    WHERE offers.Users_user_id = :user_id
    GROUP BY product.product_id
    ORDER BY product.product_id ASC';

        // Query to calculate the monthly score and product name
        $sql_monthly = "SELECT offers.product_price, offers.total_likes, offers.total_dislikes,
  SUM(offers.total_likes * 5 - offers.total_dislikes) AS total_score,
  DATE_FORMAT(offers.creation_date, '%Y-%m') AS offer_month,
  SUM(CASE WHEN DATE_FORMAT(offers.creation_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
      THEN offers.total_likes * 5 - offers.total_dislikes ELSE 0 END) AS monthly_score,
  product.product_name
FROM offers
JOIN users ON offers.Users_user_id = users.user_id
JOIN score_activity ON score_activity.Users_user_id = offers.Users_user_id AND score_activity.offers_offer_id = offers.offer_id
JOIN product ON offers.product_product_id = product.product_id
WHERE offers.Users_user_id = :user_id
GROUP BY offer_month, product.product_id
ORDER BY offer_month DESC
LIMIT 12";

        // Prepare and execute the total score query
        $stmt_total = $conn->prepare($sql_total);
        $stmt_total->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_total->execute();

        // Prepare and execute the monthly score query
        $stmt_monthly = $conn->prepare($sql_monthly);
        $stmt_monthly->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_monthly->execute();

        // Fetch all the results into an array
        $rows_total = $stmt_total->fetchAll(PDO::FETCH_ASSOC);
        $rows_monthly = $stmt_monthly->fetchAll(PDO::FETCH_ASSOC);

        // Calculate the total score
        $total_score = 0;
        foreach ($rows_total as $row) {
            $total_score += $row['total_score'];
        }

        // Create the HTML table for the total score and product name
        echo '<table>';
        echo '<tr><th>Total score from likes/dislikes</th><th>Product Name</th></tr>';
        foreach ($rows_total as $row) {
            echo '<tr><td>' . $row['total_score'] . '</td><td>' . $row['product_name'] . '</td></tr>';
        }
        echo '<tr><td class="total-score">Total score for all the offers are: ' . $total_score . '</td><td></td></tr>';
        echo '</table>';

        // Create the HTML table for the monthly score and product name
        echo '<table>';
        echo '<tr><th>Monthly score from Likes/Dislikes</th><th>Product Name</th><th>Month</th></tr>';
        foreach ($rows_monthly as $row) {
            if ($row['monthly_score'] == 0) {
                echo '<tr><td>Empty</td><td>' . $row['product_name'] . '</td><td>' . $row['offer_month'] . '</td></tr>';
            } else {
                echo '<tr><td>' . $row['monthly_score'] . '</td><td>' . $row['product_name'] . '</td><td>' . $row['offer_month'] . '</td></tr>';
            }
        }
        echo '</table>';

        ?>
    </section>

    <!-- here we create the Scoreactivity of every user -->
    <section class="score-activity">
        <?php
        // Retrieve the user ID from the session
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = '';
        }

        // Query to calculate the score, product name, and creation date for offers made in the last 24 hours
        $sql_one_day = 'SELECT offers.offer_id, offers.product_price, offers.total_likes, offers.total_dislikes,
    product.product_name, product.subcategory_subcategory_id, offers.creation_date
    FROM offers
    JOIN users ON offers.Users_user_id = users.user_id
    JOIN score_activity ON score_activity.Users_user_id = offers.Users_user_id AND score_activity.offers_offer_id = offers.offer_id
    JOIN product ON offers.product_product_id = product.product_id
    WHERE offers.Users_user_id = :user_id AND offers.creation_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)
    ORDER BY offers.offer_id ASC';

        // Prepare and execute the query
        $stmt_one_day = $conn->prepare($sql_one_day);
        $stmt_one_day->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_one_day->execute();

        // Fetch all the results into an array
        $rows_one_day = $stmt_one_day->fetchAll(PDO::FETCH_ASSOC);

        // Calculate the total score
        $total_score_one_day = 0;

        // Create the HTML table
        echo '<table>';
        echo '<tr><th>Score</th><th>Product Name</th><th>Hour</th></tr>';

        foreach ($rows_one_day as $i => $row) {
            $score = 0;

            if ($i == 0 || $row['subcategory_subcategory_id'] != $rows_one_day[$i - 1]['subcategory_subcategory_id']) {
                // This is the first offer for this subcategory in the list, so initialize the reference price
                $ref_price = $row['product_price'];
                $ref_date = $row['creation_date'];
            }

            if ($row['creation_date'] > date('Y-m-d H:i:s', strtotime('-1 week'))) {
                // Check if the offer price is less than 80% of the reference price
                $price_ratio = $row['product_price'] / $ref_price;
                if ($price_ratio < 0.8) {
                    $score = 50;
                } else if ($price_ratio < 0.9) {
                    $score = 20;
                }
            } else {
                // The offer is more than 1 week old, so assign a score of 20
                $score = 20;
            }

            echo '<tr><td>' . $score . '</td><td>' . $row['product_name'] . '</td><td>' . $row['creation_date'] . '</td></tr>';
            $total_score_one_day += $score;
        }

        echo '<tr><td class="total-score">Total score for the last 24 hours is: ' . $total_score_one_day . '</td><td></td><td></td></tr>';
        echo '</table>';

        ?>

    </section>

    <section>
        <?php
        $total_score_from_beginning = $total_score + $total_score_one_day;
        echo '<section class="score-section">';
        echo '<table>';
        echo '<tr><th>Score activity</th></tr>';
        echo '<tr><td class="total-score">Total score for all the offers are: ' . $total_score . '</td><td></td></tr>';
        echo '<tr><td class="total-score">Total score for the last 24 hours is: ' . $total_score_one_day . '</td><td></td><td></td></tr>';
        echo '<tr><td class="total-score">The score from the beginning of the record is equal to: ' . $total_score_from_beginning . '</td><td></td><td></td></tr>';
        echo '</table>';
        echo '</section>';
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