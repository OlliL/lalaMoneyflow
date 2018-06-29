{$HEADER}
    <script type="text/javascript" src="contrib/chart.js/Chart.min.js"></script>


      <div class="container container-small">
        <div class="text-center">
          <h4>{#TEXT_6#}</h4>
        </div>

        <form action="{$ENV_INDEX_PHP}" method="POST" id="plttrdform">
          <input type="hidden" name="action" value="plot_graph_bs">
          <input type="hidden" name="realaction" value="plot">

          <div class="span2 well">
            <div class="row">
              <div class="col-xs-12" id="plotTrendsErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-6 col-xs-12">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="plttrdstartdateDiv">
                    <input type="text" class="form-control" name="startdate" id="plttrdstartdate" required data-error="XXXXXXXXXXXXX!" value="{$START_DATE}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="plttrdstartdate">{#TEXT_69#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script type="text/javascript">
                    $(function () {
                        $('#plttrdstartdateDiv').datetimepicker({
                          format: "MM/YYYY",
                          viewMode: "months", 
                          focusOnShow: false,
                          showClear: true,
                          showTodayButton: true,
                          showClose: true
                        });
                    });
                </script>
              </div>


              <div class="form-group col-md-6 col-xs-12">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="plttrdenddateDiv">
                    <input type="text" class="form-control" name="enddate" id="plttrdenddate" required data-error="XXXXXXXXXXXXX!" value="{$END_DATE}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="plttrdenddate">{#TEXT_70#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script type="text/javascript">
                    $(function () {
                        $('#plttrdenddateDiv').datetimepicker({
                          format: "MM/YYYY",
                          viewMode: "months", 
                          focusOnShow: false,
                          showClear: true,
                          showTodayButton: true,
                          showClose: true
                        });
                    });
                </script>
              </div>
            </div>


            <div class="row">
              <div class="form-group col-md-12 col-xs-12">
                <span class="has-float-label">
                  <div class="input-group col-xs-12">
                    <select class="form-control" name="mcs_capitalsourceid[]" id="addmnfmcs_capitalsourceid" required data-error="{#TEXT_310#}"  size="5" multiple>
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
                      <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"
  {foreach $SELECTED_CAPITALSOURCEIDS as $id}
    {if $id == $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}selected{/if}
  {/foreach}
                      > {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
                    </select>
                  </div>
                  <label for="addmnfmcs_capitalsourceid">{#TEXT_19#}</label>
                </span>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-primary">{#TEXT_71#}</button>
                </div>
              </div>  
            </div>  

          </div>
        </form>
      </div>
        
      <div class="container container-middle">
        <canvas id="myChart" width="200" height="100"></canvas>    
      </div>

<script>


document.getElementById("myChart").style.display="none";

var ctx = document.getElementById("myChart").getContext('2d');

var gradientFill0 = ctx.createLinearGradient(0, 500, 0, 0);
gradientFill0.addColorStop(0, "rgba(176, 196, 222, 1)");
gradientFill0.addColorStop(1, "rgba(230, 230, 250, 1)");

var gradientFill1 = ctx.createLinearGradient(0, 500, 0, 0);
gradientFill1.addColorStop(0, "rgba(104, 155, 222, 1)");
gradientFill1.addColorStop(1, "rgba(174, 174, 250, 1)");

var myChart = new Chart(ctx, {
    		type: 'line',
			data: {
				labels: "",
				datasets: [{
					label: 'Settled',
					data: '',
					fill: true,
					borderColor: '#B0C4DE',
					backgroundColor: gradientFill0
				},
				{
					label: 'Calculated',
					data: '',
					fill: true,
					borderColor: '#689bde',
					backgroundColor: gradientFill1
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '{#TEXT_171#}'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '{#TEXT_172#}'
						}
					}]
				}
			}
});
</script>

      <script>    
        function ajaxPlotTrendsSuccess(data) {

          document.getElementById("myChart").style.display="";

          var labels = data.settled.map(function(e) {
            if(e.month < 10) {
              return "0" + e.month + "/" + e.year;
            } else {
              return e.month + "/" + e.year;
            }
          });

          var datas = data.settled.map(function(e) {
            return e.amount;
          });


         
          // CLEAR CHART
          myChart.data.labels.splice(0,myChart.data.labels.length);
          myChart.data.datasets.forEach((dataset) => {
            dataset.data.splice(0,dataset.data.length);
          });   




          // FILL CHART
          labels.forEach((label) => {
            myChart.data.labels.push(label)
          });
          datas.forEach((data) => {
            myChart.data.datasets[0].data.push(data);
            myChart.data.datasets[1].data.push(null);
          });
          
          if(data.calculated != null && data.calculated.length > 0) {
            var calc_labels = data.calculated.map(function(e) {
              if(e.month < 10) {
                return "0" + e.month + "/" + e.year;
              } else {
                return e.month + "/" + e.year;
              }
            });
 
            var calc_datas = data.calculated.map(function(e) {
              return e.amount;
            });


            calc_labels.forEach((label) => {
              myChart.data.labels.push(label)
            });
            
            myChart.data.datasets[1].data.pop();
            myChart.data.datasets[1].data.push(datas[datas.length-1]);
             
            calc_datas.forEach((data) => {
              myChart.data.datasets[1].data.push(calc_datas);
            });
          } else {
            myChart.data.datasets[1].hidden = true;
          }

          var parser = new DOMParser;
          var dom = parser.parseFromString(
            '<!doctype html><body>' + "{#TEXT_168#} " + myChart.data.labels[0] + "{#TEXT_170#}" + myChart.data.labels[myChart.data.labels.length-1],
            'text/html');
          var decodedString = dom.body.textContent;

          myChart.options.title.text = decodedString;

          myChart.update();
        }

        function ajaxPlotTrendsError(data) {
          alert(data);
          clearErrorDiv("plotTrendsErrors");
          populateErrorDiv(data.responseText,'plotTrendsErrorsGoHere','plotTrendsErrors');
        }

        $('#plttrdform').validator();
        $('#plttrdform').ajaxForm({
            dataType: 'json',
            success: ajaxPlotTrendsSuccess,
            error: ajaxPlotTrendsError
        });
      </script>
{$FOOTER}
