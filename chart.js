fetch('charts/doughnut_chart.php')
  .then((response) => response.text())  // Use text to check what is returned
  .then((rawData) => {
    console.log('Raw response from PHP:', rawData);  // Log the raw response to see if it contains unexpected characters
    
    // Try parsing it as JSON
    try {
      const data = JSON.parse(rawData);
      
      const directPoints = data.directPoints;  
      const indirectPoints = data.indirectPoints;
      const totalPoints = directPoints + indirectPoints;
      const last7days = data.last7DaysPoints || [];
      const noPointsInLast7Days = last7days.every(point => point === 0);

      const Rchart = document.querySelector(".chart");

      new Chart(Rchart, {
        type: "doughnut",
        data: {
          labels: ['Direct Referrals', 'Indirect Referrals'],
          datasets: [
            {
              data: noPointsInLast7Days ? [0, 100] : [directPoints, indirectPoints],  // Show full white if no points in last 7 days
              backgroundColor: noPointsInLast7Days ? ["#FFFFFF", "#FFFFFF"] : ["#007BFF", "#A5DEF1"],  // Blue and white or all white
              borderColor: "gray",  // Add light gray border for contrast
              borderWidth: noPointsInLast7Days ? 0.5 : 5,  // Slight border when white t
            },
          ],
        },
        options: {
          cutout: "75%",
          rotation: -45,
          hoverBorderWidth: 0,
          plugins: {
            legend: {
              position: "bottom",
            },
            tooltip: {
              enabled: false,
            },
          },
          responsive: true,
          maintainAspectRatio: false,
        },
      });
    } catch (error) {
      console.error('Error parsing JSON:', error);
    }
  })
  .catch((error) => {
    console.error("Error fetching the chart data:", error);
  });


// Fetch the data from PHP
fetch("charts/chart.php")
  .then((response) => response.json())
  .then((data) => {
    const labels = data.map((row) => row.date);
    const totalPoints = data.map((row) => Number(row.total_points));

    const ctx = document.getElementById("referralChart").getContext("2d");
    const referralChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Total Referrals",
            data: totalPoints,
            borderColor: "rgba(0, 122, 255, 1)", // Blue color
            fill: false,
            tension: 0.4, // Smooth curve
            pointRadius: 0, // Hide points
          },
        ],
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 10,
            top: 10,
            bottom: 20,
          },
        },
        scales: {
          x: {
            type: "time",
            time: {
              unit: "day",
              stepSize: 6, // Adjust the X-axis to display 6 days
              minUnit: "day",
              displayFormats: {
                day: 'MMM D' // Example format for dates
              },
              min: labels[0], // Start date
              max: labels[labels.length - 1], // End date
            },
            grid: {
              display: false, // No grid lines
            },
            title: {
              display: true,
              text: "Date",
            },
          },
          y: {
            beginAtZero: true,
            grid: {
              display: false, // No grid lines
            },
            title: {
              display: true,
              text: "Total Points",
            },
            ticks: {
              stepSize: 40, // Set the interval to 40 points
            },
          },
        },
        plugins: {
          legend: {
            display: false, // Hide legend
          },
          tooltip: {
            enabled: true,
            callbacks: {
              label: function (context) {
                return `${context.parsed.y} Points`; // Format tooltip
              },
            },
          },
        },
        animation: {
          duration: 1000,
          easing: "easeOutCubic", // Smooth animation
        },
      },
    });
  })
  .catch((error) => console.error("Error fetching data:", error));

