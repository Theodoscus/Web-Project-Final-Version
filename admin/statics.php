<?php
include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

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

        <script>
            // Chart data fetched from PHP
            const offersData = <?php echo json_encode($chartData); ?>;

            // Create the chart
            new Chart('offersChart', {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($datesArray); ?>,
                    datasets: [{
                        label: 'Number of Offers',
                        data: Object.values(offersData),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of Month',
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Offers',
                            },
                        },
                    },
                },
            });

            // Submit the form using JavaScript when the user clicks the "Submit" button
            document.getElementById('dateForm').addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(event.target);
                const urlParams = new URLSearchParams(formData).toString();
                const currentUrl = window.location.href.split('?')[0];
                window.location.href = `${currentUrl}?${urlParams}`;
            });
        </script>
    </section>

    <!-- -------------------------------------------------------------------------------------------------------------------------------- -->
    <section class="b-section">
        <?php
// Retrieve today's date without hours and minutes
$today = date('Y-m-d');

// Calculate the date 7 days before
$seven_days_ago = date('Y-m-d', strtotime('-7 days', strtotime($today)));

// Fetch categories from the database
$select_categories = $conn->prepare('SELECT * FROM category');
$select_categories->execute();
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);

// Fetch subcategories based on the selected category
$selectedCategoryId = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : 0);

$select_subcategories = $conn->prepare('SELECT subcategory_id, subcategory_name, category_category_id FROM subcategory WHERE category_category_id = ?');
$select_subcategories->execute([$selectedCategoryId]);
$subcategories = $select_subcategories->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for the graph based on the selected category and subcategory
$selectedSubcategoryId = '';
if (isset($_POST['submit'])) {
    $selectedSubcategoryId = $_POST['subcategory'];

    $select_offers = $conn->prepare('SELECT product.product_id, ROUND(AVG(offers.product_price), 2) AS average_price
                                        FROM offers
                                        JOIN product ON offers.product_product_id = product.product_id
                                        JOIN subcategory ON product.subcategory_subcategory_id = subcategory.subcategory_id
                                        WHERE (subcategory.category_category_id = :category OR :category = 0)
                                        AND (product.subcategory_subcategory_id = :subcategory OR :subcategory = 0)
                                        AND DATE(offers.creation_date) >= :seven_days_ago AND DATE(offers.creation_date) <= :today
                                        GROUP BY product.product_id');
    $select_offers->execute([
        'category' => $selectedCategoryId,
        'subcategory' => $selectedSubcategoryId,
        'seven_days_ago' => $seven_days_ago,
        'today' => $today,
    ]);
    $offersData = $select_offers->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default data when no subcategory is selected
    $offersData = [];
}
?>

        <h1 class="heading">Average Discount (%)</h1>
        <div class="selection-container">
    <form method="post" action="">
        <div class="inputBox">
            <label for="category">Category</label>
            <select name="category" id="category" class="box" required>
                <option value="0">Select Category</option>
                <?php foreach ($categories as $category) {?>
                    <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $selectedCategoryId) ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div class="inputBox">
            <label for="subcategory">Subcategory</label>
            <select name="subcategory" id="subcategory" class="box" required>
                <option value="0">Select Subcategory</option>
                <?php foreach ($subcategories as $subcategory) {
                    if ($subcategory['category_category_id'] == $selectedCategoryId) {
                        echo '<option value="'.$subcategory['subcategory_id'].'">'.$subcategory['subcategory_name'].'</option>';
                    }
                }?>
            </select>
        </div>
        <div class="inputBox">
            <input type="submit" value="Submit" name="submit">
        </div>
    </form>
</div>

<script>
    const categorySelect = document.getElementById('category');
    const subcategorySelect = document.getElementById('subcategory');
    
    categorySelect.addEventListener('change', () => {
        const selectedCategoryId = categorySelect.value;
        
        // Clear existing options
        subcategorySelect.innerHTML = '<option value="0">Select Subcategory</option>';
        
        // Populate subcategories based on selected category
        <?php foreach ($subcategories as $subcategory) { ?>
            if (<?php echo $subcategory['category_category_id']; ?> == selectedCategoryId) {
                subcategorySelect.innerHTML += '<option value="<?php echo $subcategory['subcategory_id']; ?>"><?php echo $subcategory['subcategory_name']; ?></option>';
            }
        <?php } ?>
    });
</script>



        <!-- Add a container for the chart -->
        <div class="chart-container">
            <canvas id="discountChart"></canvas>
        </div>
    </section>





</body>

</html>