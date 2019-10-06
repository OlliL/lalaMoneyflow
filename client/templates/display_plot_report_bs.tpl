{$HEADER}
    <script src="contrib/chart.js/Chart.min.js"></script>

      <div class="container container-middle" style="position: relative; height: 95vh">
        <canvas id="myChart"></canvas>
      </div>

<script>
        // initially hide the empty chart on loading the page
        document.getElementById("myChart").style.display="none";

        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = false;

        var ctx = document.getElementById("myChart").getContext('2d');

        var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: "",
            datasets: [{
              data: [],
              backgroundColor: []
            }]
          },
          options: {
            title: {
              display: true,
              text: ''
            },
            legend: {
              display: false
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
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
          var title = "{$CHART_TITLE}"

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
          
          var parser = new DOMParser;
          var dom = parser.parseFromString(
            '<!doctype html><body>' + title,
            'text/html');
          var decodedString = dom.body.textContent;

          myChart.options.title.text = decodedString;
          
          
          myChart.update();
        
      </script>
{$FOOTER}
