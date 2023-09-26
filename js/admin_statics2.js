// Load the Google Charts library
google.charts.load('current', { 'packages': ['bar'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    // Parse the offersData JSON
    var offersData = JSON.parse(document.getElementById('chartDataB').value);

    // Create a data table with the necessary columns
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Category');
    data.addColumn('number', 'Average Discount (%)'); 

    // Add data to the data table
    for (var i = 0; i < offersData.length; i++) {
        data.addRow([offersData[i].product_id.toString(), parseFloat(offersData[i].average_price)]);
    }

    // Get the average discount from the hidden input field
    var avgDiscount = parseFloat(document.getElementById('avgDiscount').value);

    // Set chart options, including the average discount in the title
    var options = {
        chart: {
            title: 'Average Discount (%) is ' + avgDiscount + '%', // Include the average discount in the title
        }
    };

    // Instantiate and draw the chart
    var chart = new google.charts.Bar(document.getElementById('discountChart'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}
