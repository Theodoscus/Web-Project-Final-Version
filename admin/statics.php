<?php
include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['admin_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Include Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>


<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="section-a">
        <?php

        // Get the selected year and month from the query parameter
        if (isset($_GET['date'])) {
            $selected_date = $_GET['date'];
        } else {
            // Default to the current year and month if not provided
            $selected_date = date('Y-m');
        }

        // Get the first and last day of the selected month
        $first_day = date('Y-m-01', strtotime($selected_date));
        $last_day = date('Y-m-t', strtotime($selected_date));

        // Fetch data from the database for the selected month
        $select_offers = $conn->prepare('SELECT DATE(creation_date) AS date, COUNT(offer_id) AS count FROM offers WHERE creation_date >= ? AND creation_date <= ? GROUP BY DATE(creation_date)');
        $select_offers->execute([$first_day, $last_day]);
        $offersData = $select_offers->fetchAll(PDO::FETCH_ASSOC);

        // Create an array of dates from 1 to the last day of the month
        $daysInMonth = date('t', strtotime($selected_date));
        $datesArray = range(1, $daysInMonth);

        // Process the data to get the number of offers for each day
        $chartData = array_fill(1, $daysInMonth, 0);
        foreach ($offersData as $offer) {
            $day = (int) date('j', strtotime($offer['date']));
            $chartData[$day] = (int) $offer['count'];
        }

        ?>

        <!-- Add a container for the month field -->
        <div class="month-container">
            <form id="dateForm" method="get">
                <label for="bdaymonth">Select Year and Month:</label>
                <input type="month" id="bdaymonth" name="date" value="<?php echo $selected_date; ?>">
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Add a container for the chart -->
        <div class="chart-container">
            <canvas id="offersChart"></canvas>
        </div>
        <input type="hidden" id="chartData" value="<?php echo htmlentities(json_encode($chartData)); ?>">
        <input type="hidden" id="datesArray" value="<?php echo htmlentities(json_encode($datesArray)); ?>">

    </section>

    <!-- -------------------------------------------------------------------------------------------------------------------------------- -->
    <section class="b-section">
        <h1 class="heading">Average Discount (%)</h1>
        <div class="selection-container">
            <form method="post" action="">
                <div class="inputBox">
                    <label for="category">Κατηγορία</label>
                    <select name="category_select" id="category_select" class="box" required>
                        <option selected disabled value="0">Επιλέξτε Κατηγορία</option>
                        <?php
                        $stmt = $conn->prepare('SELECT * FROM category ORDER BY category_name');
                        $stmt->execute();
                        $categoriesList = $stmt->fetchAll();

                        foreach ($categoriesList as $category) {
                            $selected = ($selectedCategoryId == $category['category_id']) ? 'selected' : '';
                            echo "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="inputBox">
                    <label for="subcategory">Υποκατηγορία</label>
                    <select name="subcategory_select" id="subcategory_select" class="box" required>
                        <option selected disabled value="0">Επιλέξτε Υποκατηγορία</option>
                        <?php
                        foreach ($subcategories as $subcategory) {
                            $selected = ($selectedSubcategoryId == $subcategory['subcategory_id']) ? 'selected' : '';
                            echo "<option value='" . $subcategory['subcategory_id'] . "' $selected>" . $subcategory['subcategory_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="inputBox">
                    <label for="week_select">Choose the week you want:</label>
                    <select name="week_select" id="week_select" class="box" required>
                        <option value="current_week">Current Week</option>
                        <option value="one_week_ago">1 Week Ago</option>
                        <option value="two_weeks_ago">2 Weeks Ago</option>
                        <option value="three_weeks_ago">3 Weeks Ago</option>
                    </select>
                </div>
                <div class="inputBox">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>

        <?php

        function get_avg_week_priceSubcategory($selectedSubcategoryId, $seven_days_ago, $today)
        {
            include '../components/connect.php';
            $select_product_avg_week_price = $conn->prepare('SELECT offers.product_price 
        FROM offers
        JOIN product ON offers.product_product_id = product.product_id
        WHERE product.subcategory_subcategory_id = ? AND DATE(offers.creation_date) >= ? AND DATE(offers.creation_date) <= ?');
            $select_product_avg_week_price->execute([$selectedSubcategoryId, $seven_days_ago, $today]);

            $total_price = 0;
            $total_prods = 0;

            if ($select_product_avg_week_price->rowCount() > 0) {
                while ($fetch_product_avg_week_price = $select_product_avg_week_price->fetch(PDO::FETCH_ASSOC)) {
                    $total_price += $fetch_product_avg_week_price['product_price'];
                    ++$total_prods;
                }

                $avg_week_price = $total_price / $total_prods;
                return $avg_week_price;
            } else {
                return INF;
            }
        }

        function get_avg_week_priceCategory($selectedCategoryId, $seven_days_ago, $today)
        {
            include '../components/connect.php';
            $select_product_avg_week_price = $conn->prepare('SELECT offers.product_price 
        FROM offers
        JOIN product ON offers.product_product_id = product.product_id
        JOIN subcategory ON product.subcategory_subcategory_id = subcategory.subcategory_id
        WHERE subcategory.category_category_id = ?
        AND DATE(offers.creation_date) >= ? AND DATE(offers.creation_date) <= ?');
            $select_product_avg_week_price->execute([$selectedCategoryId, $seven_days_ago, $today]);

            $total_price = 0;
            $total_prods = 0;

            if ($select_product_avg_week_price->rowCount() > 0) {
                while ($fetch_product_avg_week_price = $select_product_avg_week_price->fetch(PDO::FETCH_ASSOC)) {
                    $total_price += $fetch_product_avg_week_price['product_price'];
                    ++$total_prods;
                }

                $avg_week_price = $total_price / $total_prods;
                return $avg_week_price;
            } else {
                return INF;
            }
        }


        // Retrieve today's date without hours and minutes
        $today = date('Y-m-d');

        // Calculate the date 7 days before
        $seven_days_ago = date('Y-m-d', strtotime('-7 days', strtotime($today)));

        // Calculate the date 14 days before
        $fourteen_days_ago = date('Y-m-d', strtotime('-14 days', strtotime($today)));

        // Calculate the date 21 days before
        $twentyone_days_ago = date('Y-m-d', strtotime('-21 days', strtotime($today)));

        // Calculate the date 28 days before
        $twentyeight_days_ago = date('Y-m-d', strtotime('-28 days', strtotime($today)));

        // Initialize variables for selected category and subcategory
        $selectedCategoryId = $selectedSubcategoryId = '';

        // Check if form is submitted and update selected IDs
        if (isset($_POST['submit'])) {
            $selectedCategoryId = $_POST['category_select'];
            $selectedSubcategoryId = isset($_POST['subcategory_select']) ? $_POST['subcategory_select'] : '';

            echo "Selected Category ID: $selectedCategoryId<br>";

            // Only display Subcategory ID if it's selected
            if (!empty($selectedSubcategoryId)) {
                echo "Selected Subcategory ID: $selectedSubcategoryId<br>";
            }

            // Fetch subcategories based on the selected category
            $select_subcategories = $conn->prepare('SELECT subcategory_id, subcategory_name, category_category_id FROM subcategory WHERE category_category_id = ?');
            $select_subcategories->execute([$selectedCategoryId]);
            $subcategories = $select_subcategories->fetchAll(PDO::FETCH_ASSOC);

            // Fetch data for the graph based on the selected category and subcategory
            if ($selectedSubcategoryId) {
                $selected_offers = $conn->prepare('SELECT product.product_id, ROUND(AVG(offers.product_price), 2) AS average_price
                FROM offers
                JOIN product ON offers.product_product_id = product.product_id
                JOIN subcategory ON product.subcategory_subcategory_id = subcategory.subcategory_id
                WHERE product.subcategory_subcategory_id = :subcategoryID
                -- AND DATE(offers.creation_date) >= :seven_days_ago AND DATE(offers.creation_date) <= :today
                GROUP BY product.product_id');
                $selected_offers->execute([
                    'subcategoryID' => $selectedSubcategoryId,
                    // 'seven_days_ago' => $seven_days_ago,
                    // 'today' => $today,
                ]);
            } else {
                $selected_offers = $conn->prepare('SELECT product.product_id, ROUND(AVG(offers.product_price), 2) AS average_price
                FROM offers
                JOIN product ON offers.product_product_id = product.product_id
                JOIN subcategory ON product.subcategory_subcategory_id = subcategory.subcategory_id
                WHERE subcategory.category_category_id = :categoryID
                -- AND DATE(offers.creation_date) >= :seven_days_ago AND DATE(offers.creation_date) <= :today
                GROUP BY product.product_id');
                $selected_offers->execute([
                    'categoryID' => $selectedCategoryId,
                    // 'seven_days_ago' => $seven_days_ago,
                    // 'today' => $today,
                ]);
            }

            // Fetch offers data and calculate avg_discount
            $offersData = $selected_offers->fetchAll(PDO::FETCH_ASSOC);

            // Initialize selected offers sum for calculation
            $selected_offers_sum = 0;

            // Check if neither category nor subcategory is selected
            if (empty($selectedCategoryId) && empty($selectedSubcategoryId)) {
                echo "You must select a Category or a Subcategory, please!";
            } else {
                // Calculate average week price based on user's selection
                if (!empty($selectedSubcategoryId)) {
                    if ($_POST['week_select'] === 'current_week') {
                        $avg_week_priceSub = get_avg_week_priceSubcategory($selectedSubcategoryId, $seven_days_ago, $today);
                    } elseif ($_POST['week_select'] === 'one_week_ago') {
                        $avg_week_priceSub = get_avg_week_priceSubcategory($selectedSubcategoryId, $fourteen_days_ago, $seven_days_ago);
                    } elseif ($_POST['week_select'] === 'two_weeks_ago') {
                        $avg_week_priceSub = get_avg_week_priceSubcategory($selectedSubcategoryId, $twentyone_days_ago, $fourteen_days_ago);
                    } elseif ($_POST['week_select'] === 'three_weeks_ago') {
                        $avg_week_priceSub = get_avg_week_priceSubcategory($selectedSubcategoryId, $twentyeight_days_ago, $twentyone_days_ago);
                    }
                    // Echo the value of $avg_week_priceSub
                    echo "Average Week Price (Subcategory): $avg_week_priceSub<br>";
                }

                if (empty($selectedSubcategoryId)) {
                    if ($_POST['week_select'] === 'current_week') {
                        $avg_week_priceCat = get_avg_week_priceCategory($selectedCategoryId, $seven_days_ago, $today);
                    } elseif ($_POST['week_select'] === 'one_week_ago') {
                        $avg_week_priceCat = get_avg_week_priceCategory($selectedCategoryId, $fourteen_days_ago, $seven_days_ago);
                    } elseif ($_POST['week_select'] === 'two_weeks_ago') {
                        $avg_week_priceCat = get_avg_week_priceCategory($selectedCategoryId, $twentyone_days_ago, $fourteen_days_ago);
                    } elseif ($_POST['week_select'] === 'three_weeks_ago') {
                        $avg_week_priceCat = get_avg_week_priceCategory($selectedCategoryId, $twentyeight_days_ago, $twentyone_days_ago);
                    }
                    // Echo the value of $avg_week_priceCat
                    echo "Average Week Price (Category): $avg_week_priceCat<br>";
                }


                // Calculate sum of selected offers
                foreach ($offersData as $offer) {
                    $selected_offers_sum += $offer['average_price'];
                }

                // Echo the value of $selected_offers_sum
                echo "Sum of Selected Offers: $selected_offers_sum<br>";

                // Calculate avg_discount
                if (!empty($selectedSubcategoryId)) {
                    $avg_discount = !empty($avg_week_priceSub) ? (($selected_offers_sum - $avg_week_priceSub) / $avg_week_priceSub) * 100 : 0;
                } elseif (!empty($selectedCategoryId)) {
                    $avg_discount = !empty($avg_week_priceCat) ? (($selected_offers_sum - $avg_week_priceCat) / $avg_week_priceCat) * 100 : 0;
                } else {
                    $avg_discount = 0;
                }

                // Echo the value of $avg_discount
                echo "Average Discount: $avg_discount%";
            }


            // Debug: Print offersData and avg_discount to browser console
            echo "<script>console.log(" . json_encode($offersData) . ");</script>";
            echo "<script>console.log('Average Discount: $avg_discount%');</script>";
        }
        ?>

        <!-- Google Charts script -->
        <div class="chart-container">
            <div id="discountChart"></div>
        </div>

        <input type="hidden" id="chartDataB" value="<?php echo htmlentities(json_encode($offersData)); ?>">
    </section>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/admin_statics.js"></script>
    <script src="../js/admin_statics2.js"></script>
    <script src="../js/admin_ajax.js"></script>

</body>

</html>