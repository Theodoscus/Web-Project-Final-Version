// Load the Google Charts library
google.charts.load('current', { 'packages': ['bar'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    // Parse the offersData JSON
    var offersData = JSON.parse(document.getElementById('chartDataB').value);

    // Create a data table with the necessary columns
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Product ID');
    data.addColumn('number', 'Average Price');

    // Add data to the data table
    for (var i = 0; i < offersData.length; i++) {
        data.addRow([offersData[i].product_id.toString(), parseFloat(offersData[i].average_price)]);
    }

    // Set chart options
    var options = {
        chart: {
            title: 'Average Discount (%)',
        }
    };

    // Instantiate and draw the chart
    var chart = new google.charts.Bar(document.getElementById('discountChart'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}
