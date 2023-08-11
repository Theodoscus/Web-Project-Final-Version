document.addEventListener("DOMContentLoaded", function() {
    // Retrieve the chart data and dates array for the first chart from hidden inputs
    const chartDataInput = document.getElementById('chartData');
    const datesArrayInput = document.getElementById('datesArray');

    const offersData = JSON.parse(chartDataInput.value);
    const datesArray = JSON.parse(datesArrayInput.value);

    // Create the chart for the first section
    new Chart('offersChart', {
        type: 'line',
        data: {
            labels: datesArray,
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

    // Retrieve the chart data and dates array for the SECOND chart from hidden inputs
    const discountDataInput = document.getElementById('chartDataB');
    const discountDatesArrayInput = document.getElementById('datesArrayB');
    
    const discountData = JSON.parse(discountDataInput.value);
    const discountDatesArray = JSON.parse(discountDatesArrayInput.value);
    
    // Create the chart for the second section
    new Chart('discountChart', {
        type: 'line',
        data: {
            labels: discountDatesArray,
            datasets: [{
                label: 'Average Discount (%)',
                data: Object.values(discountData),
                borderColor: 'rgba(255, 99, 132, 1)',
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
                        text: 'Average Discount (%)',
                    },
                },
            },
        },
    });

    // Submit the form using JavaScript when the user clicks the "Submit" button for the first chart
    document.getElementById('dateForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const urlParams = new URLSearchParams(formData).toString();
        const currentUrl = window.location.href.split('?')[0];
        window.location.href = `${currentUrl}?${urlParams}`;
    });

    // Submit the form using JavaScript when the user clicks the "Submit" button for the second chart
    document.getElementById('categoryForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const urlParams = new URLSearchParams(formData).toString();
        const currentUrl = window.location.href.split('?')[0];
        window.location.href = `${currentUrl}?${urlParams}`;
    });
});
