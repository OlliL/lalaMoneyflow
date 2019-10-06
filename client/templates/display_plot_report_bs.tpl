{$HEADER}
    <script src="contrib/chart.js/Chart.min.js"></script>
    <script src="contrib/chartjs-plugin-labels.js"></script>

      <div class="container container-middle" style="position: relative; height: 80vh">
        <canvas id="myChart"></canvas>
      </div>

<script>
        // initially hide the empty chart on loading the page
        document.getElementById("myChart").style.display="none";

        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = false;

        var ctx = document.getElementById("myChart").getContext('2d');

        var myChart = new Chart(ctx, {
          type: '{$CHART_TYPE}',
          data: {
            labels: "",
            datasets: [{
              data: [],
              backgroundColor: []
            }]
          },
          options: {
            legend: {
              display: false
            },
            plugins: {
              labels: {
                render: 'label',
  //  arc: true,
    position: 'border',
    fontColor: '#000',
    position: 'outside',
    overlap: true,

              }
            }
          }
        });
        
          document.getElementById("myChart").style.display="";

         
          // CLEAR CHART
          
          myChart.data.labels.splice(0,myChart.data.labels.length);
          myChart.data.datasets.forEach((dataset) => {
            dataset.data.splice(0,dataset.data.length);
            dataset.data.splice(0,dataset.backgroundColor.length);
          });   



          // FILL CHART
          
          var chartLabels = {$CHART_LABELS};
          var chartData = {$CHART_DATA};
          var chartColors = {$CHART_COLORS};

          // Add settled data


          chartLabels.forEach((label) => {
            myChart.data.labels.push(label)
          });
          chartData.forEach((data) => {
            myChart.data.datasets[0].data.push(data);
          });
          
          chartColors.forEach((color) => {
            myChart.data.datasets[0].backgroundColor.push(color);
          });
          
          myChart.update();
        
      </script>
{$FOOTER}
