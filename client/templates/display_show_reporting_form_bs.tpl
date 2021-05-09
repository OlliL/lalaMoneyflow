{$HEADER}
    <script src="contrib/chart.js/Chart.min.js"></script>

      <form action="{$ENV_INDEX_PHP}" method="POST" id="showrepform">
        <input type="hidden" name="action" value="plot_report">


        <div class="text-center">
          <h4>{#TEXT_254#}</h4>
        </div>

        <div class="container container-small">
          <div class="span2 well">
            <div class="row">
              <div class="col-xs-12" id="plotTrendsErrorsGoHere">
              </div>
            </div>

            <div class="row text-center">
              <div class="form-group col-md-6 col-xs-12">
                <input id="showrepmonth" data-width="200px" data-toggle="toggle" value="1" data-on="{#TEXT_370#}" data-off="{#TEXT_371#}" type="checkbox" name="aggregate_month">
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <input id="showreppostingaccountmode" data-width="200px" data-toggle="toggle" value="1" data-on="{#TEXT_369#}" data-off="{#TEXT_258#}" type="checkbox" name="account_mode">
              </div>
            </div>
            
            <div class="row text-center" id="showrepmonthview">
              <div class="form-group col-md-5 col-xs-5">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="showrepstartdateDiv">
                    <input type="text" class="form-control" name="startdate" id="showrepstartdate" required data-error="{#TEXT_329#}" value="{$START_DATE}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="showrepstartdate">{#TEXT_69#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script>
                    $(function () {
                        $('#showrepstartdateDiv').datetimepicker({
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

              <div class="form-group col-md-2 col-xs-2">
                <div class="text-center">
                  <button type="button" class="btn btn-default" aria-label="Left Align" onClick="showrepCopyDate()">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  </button>
                </div>
              </div>

              <div class="form-group col-md-5 col-xs-5">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="showrependdateDiv">
                    <input type="text" class="form-control" name="enddate" id="showrependdate" required data-error="{#TEXT_330#}" value="{$END_DATE}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="showrependdate">{#TEXT_70#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script>
                    $(function () {
                        $('#showrependdateDiv').datetimepicker({
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

            <div class="row text-center" id="showrepyearview"  style="display: none;">
              <div class="form-group col-md-5 col-xs-5">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="showrepstartyearDiv">
                    <input type="text" class="form-control" name="startyear" id="showrepstartyear" required data-error="{#TEXT_329#}" value="{$START_YEAR}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="showrepstartyear">{#TEXT_69#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script>
                    $(function () {
                        $('#showrepstartyearDiv').datetimepicker({
                          format: "YYYY",
                          viewMode: "years", 
                          focusOnShow: false,
                          showClear: true,
                          showTodayButton: true,
                          showClose: true
                        });
                    });
                </script>
              </div>

              <div class="form-group col-md-2 col-xs-2">
                <div class="text-center">
                  <button type="button" class="btn btn-default" aria-label="Left Align" onClick="showrepCopyYear()">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  </button>
                </div>
              </div>

              <div class="form-group col-md-5 col-xs-5">
                <span class="has-float-label">
                  <div class='input-group date col-xs-12' id="showrependyearDiv">
                    <input type="text" class="form-control" name="endyear" id="showrependyear" required data-error="{#TEXT_330#}" value="{$END_YEAR}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <label for="showrependyear">{#TEXT_70#}</label>
                </span>
                <div class="help-block with-errors"></div>
                <script>
                    $(function () {
                        $('#showrependyearDiv').datetimepicker({
                          format: "YYYY",
                          viewMode: "years", 
                          focusOnShow: false,
                          showClear: true,
                          showTodayButton: true,
                          showClose: true
                        });
                    });
                </script>
              </div>
              
            </div>

          </div>
        </div>
        
        <div class="container container-wide" id="plotReportMultipleAccounts">
          <div class="span2 well">
            <div class="row">
              <div class="form-group col-md-5 col-xs-12">
                <span class="has-float-label">
                  <div class="input-group col-xs-12">
                    <select class="form-control" name="mpa_postingaccountid_yes[]" id="showrepmpa_postingaccountid_yes" size="7" multiple>
{section name=ACCOUNT loop=$ACCOUNTS_YES}
                      <option value="{$ACCOUNTS_YES[ACCOUNT].postingaccountid}"> {$ACCOUNTS_YES[ACCOUNT].name}</option>
{/section}
                    </select>
                  </div>
                  <label for="showrepmpa_postingaccountid_yes">{#TEXT_259#}</label>
                </span>
                <div class="help-block with-errors"></div>
              </div>

              <div class="form-group col-md-2 col-xs-12">
                <div class="text-center">
                  <button type="button" class="btn btn-default" onclick="showrepmove('showrepmpa_postingaccountid_yes','showrepmpa_postingaccountid_no', true)">
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                  <br>
                  <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-chevron-right" onclick="showrepmove('showrepmpa_postingaccountid_yes','showrepmpa_postingaccountid_no', false)"></span>
                  </button>
                  <br>
                  <br>
                  <button type="button" class="btn btn-default" onclick="showrepmove('showrepmpa_postingaccountid_no','showrepmpa_postingaccountid_yes', false)">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                  </button>
                  <br>
                  <button type="button" class="btn btn-default" onclick="showrepmove('showrepmpa_postingaccountid_no','showrepmpa_postingaccountid_yes', true)">
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </div>
              </div>
              
              <div class="form-group col-md-5 col-xs-12">
                <span class="has-float-label">
                  <div class="input-group col-xs-12">
                    <select class="form-control" name=account" id="showrepmpa_postingaccountid_no" size="7" multiple>
{section name=ACCOUNT loop=$ACCOUNTS_NO}
                      <option value="{$ACCOUNTS_NO[ACCOUNT].postingaccountid}"> {$ACCOUNTS_NO[ACCOUNT].name}</option>
{/section}
                    </select>
                  </div>
                  <label for="showrepmpa_postingaccountid_no">{#TEXT_260#}</label>
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
        </div>
        
        <div class="container container-small" id="plotReportSingleAccount" style="display: none;">
          <div class="span2 well">
            <div class="row">
              <div class="form-group col-md-12 col-xs-12">
                <div id="showrepmpa_postingaccountidDiv">
                  <span class="has-float-label">
                    <div class="input-group col-xs-12">
                      <select class="form-control" name="account" id="showrepmpa_postingaccountid" data-error="{#TEXT_309#}">
                        <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
                        <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
                      </select>
                    </div>
                    <label for="showrepmpa_postingaccountid">{#TEXT_232#}</label>
                  </span>
                  <div class="help-block with-errors"></div>
                </div>
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
        </div>


      </form>

      <div class="container container-middle" style="position: relative; height: 55vh">
        <canvas id="myChart"></canvas>
      </div>

{literal}
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

               
        function plotReportShowMultipleAccounts() {
          $('#plotReportSingleAccount').hide()
          $('#plotReportMultipleAccounts').show()
          $('#plotReportShowSingleAccountButton').removeClass('active')
          $('#plotReportShowMultipleAccountsButton').addClass('active')
          $('#showrepmpa_postingaccountid').prop('required',false);

          $('#showrepform').validator('reset');
          $('#showrepform').validator('update');
        }
        
        function plotReportShowSingleAccount() {
          $('#plotReportMultipleAccounts').hide()
          $('#plotReportSingleAccount').show()
          $('#plotReportShowMultipleAccountsButton').removeClass('active')
          $('#plotReportShowSingleAccountButton').addClass('active')
          $('#showrepmpa_postingaccountid').prop('required',true);

          $('#showrepform').validator('reset');
          $('#showrepform').validator('update');
        }
        
        function yearMode() {
          $('#showrepmonthview').hide()
          $('#showrepyearview').show()
        }
        
        function monthMode() {
          $('#showrepyearview').hide()
          $('#showrepmonthview').show()
        }
        
        function showrepCopyDate() {
          $('#showrependdate').val($('#showrepstartdate').val());
        }

        function showrepCopyYear() {
          $('#showrependyear').val($('#showrepstartyear').val());
        }
        

        function fillHiddenInputsBeforeSubmit(arr) {
          var accounts_yes = "";
          var accounts_no = "";
          
          $("#showrepmpa_postingaccountid_yes option").each(function() {
            accounts_yes = accounts_yes + $(this).val() + ","
          });

          $("#showrepmpa_postingaccountid_no option").each(function() {
            accounts_no = accounts_no + $(this).val() + ","
          });

          arr.push({name: 'accounts_yes', value: accounts_yes });
          arr.push({name: 'accounts_no' , value: accounts_no });
        }        
        
	function sortlist(list) {
	    var elem = document.getElementById(list);
	    var tmpAry = [];

	    for (var i=0;i<elem.options.length;i++)
	        tmpAry.push(elem.options[i]);

	    tmpAry.sort(function(a,b){ return (a.text.toUpperCase() < b.text.toUpperCase())?-1:1; });

	    while (elem.options.length > 0)
                elem.options[0] = null;

	    for (var i=0;i<tmpAry.length;i++) {
	        elem.options[i] = tmpAry[i];
	    }

	    return;
	}

	function showrepmove(from, to, all) {
	    /* add selected item to the 'to' selectbox */
	    for (i = 0; i < document.getElementById(from).length; i++) {
	        if (all || document.getElementById(from).options[i].selected == true) {
	            document.getElementById(to).options[document.getElementById(to).length] = new Option(
	                document.getElementById(from).options[i].text, document.getElementById(from).options[i].value );
	        }
	    }
	
	    /* remove selected item from the 'from' selectbox */
	    $("#" + from + " > option").each(function() {
              if(this.selected) {
                this.remove();
              }
            });

	    sortlist(to);
	}

      
        function ajaxPlotTrendsSuccess(data) {

          document.getElementById("myChart").style.display="";

         
          // CLEAR CHART
          
          myChart.data.labels.splice(0,myChart.data.labels.length);
          myChart.data.datasets.forEach((dataset) => {
            dataset.data.splice(0,dataset.data.length);
            dataset.data.splice(0,dataset.backgroundColor.length);
          });   


          // FILL CHART
          
          var chartLabels = data["chartLabels"];
          var chartData = data["chartData"];
          var chartColors = data["chartColors"];
          var title = data["chartTitle"];

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
        }

        function ajaxPlotTrendsError(data) {
          clearErrorDiv("plotTrendsErrors");
          populateErrorDiv(data.responseText,'plotTrendsErrorsGoHere','plotTrendsErrors');
        }

        $(function() {
          $('#showrepmonth').change(function() {
            if($(this).prop('checked')) {
              yearMode();
            } else {
              monthMode();
            }
      
          })
        })

        $(function() {
          $('#showreppostingaccountmode').change(function() {
            if($(this).prop('checked')) {
              plotReportShowSingleAccount();
            } else {
              plotReportShowMultipleAccounts();
            }
          })
        })
        
        $('#showrepform').validator();
        $('#showrepform').ajaxForm({
            beforeSubmit: function(arr, $form, options) {
              clearErrorDiv("plotTrendsErrors");
              return fillHiddenInputsBeforeSubmit(arr);
            },
            dataType: 'json',
            success: ajaxPlotTrendsSuccess,
            error: ajaxPlotTrendsError
        });
      </script>
{/literal}
{$FOOTER}
