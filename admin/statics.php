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
   <meta name="viewport" content="wproduct_idth=device-wproduct_idth, initial-scale=1.0">
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
   <label for="bdaymonth">Select Year and Month:</label>
   <input type="month" id="bdaymonth" name="bdaymonth">
   <input type="submit" value="Submit">
</div>

<!-- Add a container for the chart -->
<div class="chart-container">
    <canvas id="offersChart"></canvas>
</div>

<script>
   // Sample data - replace this with the actual data fetched from your server
   const offersData = [
       { date: '2023-07-01', count: 5 },
       { date: '2023-07-02', count: 8 },
       // ... more data ...
   ];

   // Process the data to get the number of offers for each day
   const chartData = offersData.reduce((acc, item) => {
       const date = new Date(item.date);
       const day = date.getDate();
       acc[day] = (acc[day] || 0) + item.count;
       return acc;
   }, {});

   // Create an array of dates from 1 to the last day of the month (replace with the actual month's last day)
   const daysInMonth = 31; // Update this dynamically based on the selected month
   const datesArray = Array.from({ length: daysInMonth }, (_, i) => i + 1);

   // Create the chart
   new Chart('offersChart', {
       type: 'line', // You can change this to 'bar' for a bar chart
       data: {
           labels: datesArray,
           datasets: [{
               label: 'Number of Offers',
               data: datesArray.map(day => chartData[day] || 0),
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
</script>
</body>
</html>
