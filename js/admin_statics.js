document.addEventListener("DOMContentLoaded", function () {
  // Retrieve the chart data and dates array for the first chart from hidden inputs
  const chartDataInput = document.getElementById("chartData");
  const datesArrayInput = document.getElementById("datesArray");

  const offersData = JSON.parse(chartDataInput.value);
  const datesArray = JSON.parse(datesArrayInput.value);

  // Create the chart for the first section
  new Chart("offersChart", {
    type: "line",
    data: {
      labels: datesArray,
      datasets: [
        {
          label: "Number of Offers",
          data: Object.values(offersData),
          backgroundColor: "rgba(41, 128, 185, 0.7)", 
          borderColor: "rgba(41, 128, 185, 1)",
          borderWidth: 1,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          title: {
            display: true,
            text: "Day of Month",
          },
        },
        y: {
          title: {
            display: true,
            text: "Number of Offers",
          },
        },
      },
    },
  });
});
