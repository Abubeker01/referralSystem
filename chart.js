fetch('charts/doughnut_chart.php')
  .then((response) => response.text())
  .then((rawData) => {
    console.log('Raw response from PHP:', rawData);
    
    try {
      const data = JSON.parse(rawData);  // Parse JSON response
      
      // Fetch points from parsed JSON data
      const directPoints = data.directPoints;  
      const indirectPoints = data.indirectPoints;
      const totalPoints = directPoints + indirectPoints;
      const last7days = data.last7days || [];
      const noPointsInLast7Days = last7days.every(point => point === 0);  // Check if all points are 0

      const Rchart = document.querySelector(".chart");

      new Chart(Rchart, {
        type: "doughnut",
        data: {
          labels: ['Direct Referrals', 'Indirect Referrals'],
          datasets: [
            {
              data: noPointsInLast7Days ? [0, 100] : [directPoints, indirectPoints], 
              backgroundColor: noPointsInLast7Days ? ["#FFFFFF", "#FFFFFF"] : ["#007BFF", "#A5DEF1"],  // Color when no points
              borderColor: "gray", 
              borderWidth: noPointsInLast7Days ? 0.5 : 5,  // Border width changes if no points
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
            borderColor: "rgba(0, 122, 255, 1)", 
            fill: false,
            tension: 0.4, 
            pointRadius: 0,
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
              stepSize: 6,
              minUnit: "day",
              displayFormats: {
                day: 'MMM D'
              },
              min: labels[0], 
              max: labels[labels.length - 1],
            },
            grid: {
              display: false, 
            },
            title: {
              display: true,
              text: "Date",
            },
          },
          y: {
            beginAtZero: true,
            grid: {
              display: false, 
            },
            title: {
              display: true,
              text: "Total Points",
            },
            ticks: {
              stepSize: 40,
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            enabled: true,
            callbacks: {
              label: function (context) {
                return `${context.parsed.y} Points`;
              },
            },
          },
        },
        animation: {
          duration: 1000,
          easing: "easeOutCubic",
        },
      },
    });
  })
  .catch((error) => console.error("Error fetching data:", error));

