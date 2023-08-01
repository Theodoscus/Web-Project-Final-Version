<?php
include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}

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
</body>
</html>
