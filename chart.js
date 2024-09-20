fetch('charts/doughnut_chart.php')
  .then((response) => response.text())
  .then((rawData) => {
    console.log('Raw response from PHP:', rawData);

    try {
      const data = JSON.parse(rawData); 

      
      const totalDirectPoints = Number(data.totalDirectPoints);  
      const totalIndirectPoints = Number(data.totalIndirectPoints);
      const totalPoints = totalDirectPoints + totalIndirectPoints;  

      // Points earned in the last 7 days
      const directPoints = data.directPoints;  
      const indirectPoints = data.indirectPoints;

      const last7days = data.last7days || [];
      const noPointsInLast7Days = last7days.every(point => point === 0); 

      const Rchart = document.querySelector(".chart");

      const centerTextPlugin = {
        id: 'centerText',
        afterDatasetsDraw(chart) {
          const { ctx, chartArea: { top, bottom, left, right } } = chart;
          const xCenter = (left + right) / 2;
          const yCenter = (top + bottom) / 2;

      
          ctx.save();
          ctx.font = ' 18px Arial';
          ctx.fillStyle = '#333'; 
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';

          ctx.fillText(`${totalPoints} total Points`, xCenter, yCenter);
          ctx.restore();
        }
      };

      new Chart(Rchart, {
        type: 'doughnut',
        data: {
          labels: ['Direct Referrals', 'Indirect Referrals'],
          datasets: [
            {
              //points earned in the last 7 days in the chart
              data: noPointsInLast7Days ? [0, 100] : [directPoints, indirectPoints], 
              backgroundColor: noPointsInLast7Days ? ["#FFFFFF", "#FFFFFF"] : ["#007BFF", "#A5DEF1"],  
              borderColor: "gray", 
              borderWidth: noPointsInLast7Days ? 0.5 : 5,  
            },
          ],
        },
        options: {
          cutout: '75%',  
          rotation: -45,  
          hoverBorderWidth: 0,
          plugins: {
            legend: {
              display: false,  
            },
            tooltip: {
              enabled: true,  
            },
          },
          responsive: true,
          maintainAspectRatio: false, 
        },
        plugins: [centerTextPlugin],  
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
            pointRadius: 0,  // Points are not visible by default
            pointHoverRadius: 5,  // Points will appear when hovered
            pointBackgroundColor: "rgba(0, 122, 255, 1)",  // Point color when hovered
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
                return `${context.parsed.y} Points`;  // Show the value of the point
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


