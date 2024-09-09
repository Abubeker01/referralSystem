


const chartData = {
  labels: ["direct", "indirect"],
  data: [20, 30],
};

const Rchart = document.querySelector(".chart");

new Chart(Rchart, {
  type: "doughnut",
  data: {
    labels: chartData.labels,
    datasets: [
      {
        label: "referals",
        data: chartData.data,
      },
    ],
  },
  options: {
    borderWidth: 5,
    borderRadius: 2,
    hoverBorderWidth:0,
    plugins: {
        legend:{
              position:'bottom'
        }
     
    },
  },
});

// Fetch the data from PHP
fetch('chart.php')
    .then(response => response.json())
    .then(data => {
        // Process data for Chart.js
        const labels = data.map(row => row.date); // Date labels
        const totalPoints = data.map(row => Number(row.total_points));  // Convert to numbers

        // Create the chart
        const ctx = document.getElementById('referralChart').getContext('2d');
        const referralChart = new Chart(ctx, {
            type: 'line', // Line chart type
            data: {
                labels: labels, // X-axis labels
                datasets: [{
                    label: 'Total Referrals',
                    data: totalPoints, // Y-axis data points
                    borderColor: 'rgba(75, 192, 192, 1)', // Line color
                    fill: false, // Disable filling the area under the line
                    tension: 0.4, // Make the line smooth (wave-like)
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time', // Use a time-based X-axis
                        time: {
                            unit: 'day', // Use daily intervals
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true, // Start Y-axis at 0
                        title: {
                            display: true,
                            text: 'Total Points'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                    },
                }
            }
        });
    })
    .catch(error => console.error('Error fetching data:', error));
