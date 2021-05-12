{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_7#}</h4>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="searchmoneyflow" id="sermnfform">
          <input type="hidden" name="action" value="do_search">

          <div class="span2 well">

            <div id="searchMoneyflowErrorsGoHere">
            </div>

     <div class="row">
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="sermnfstartdateDiv">
                  <input type="text" class="form-control" name="startdate" id="sermnfstartdate" required data-error="{#TEXT_329#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="sermnfstartdate">{#TEXT_69#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#sermnfstartdateDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="sermnfenddateDiv">
                  <input type="text" class="form-control" name="enddate" id="sermnfenddate" required data-error="{#TEXT_330#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="sermnfenddate">{#TEXT_17#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#sermnfenddateDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true, 
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="contractpartner" id="sermnfmcp_contractpartnerid">
                    <option value="">&nbsp;</option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
                    <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
                  </select>
                </div>
                <label for="sermnfmcp_contractpartnerid">{#TEXT_2#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <div id="sermnfmpa_postingaccountidDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="postingaccount" id="sermnfmpa_postingaccountid">
                    <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
                    <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
                  </select>
                </div>
                <label for="sermnfmpa_postingaccountid">{#TEXT_232#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>


    </div>
    <div class="row">

            <div class="form-group col-md-3 col-xs-12">
              <div id="sermnfcommentDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="sermnfpattern" name="searchstring">
                </div>
                <label for="sermnfcomment">{#TEXT_21#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>
            <div class="form-group col-md-1 col-xs-12">
              <div class="checkbox">
                <label><input type="checkbox" name="equal" id="sermnfequal"> {#TEXT_76#}</label>
              </div>
            </div>
            <div class="form-group col-md-3 col-xs-12">
              <div class="checkbox">
                <label><input type="checkbox" name="casesensitive" id="sermnfcasesensitive"> {#TEXT_77#}</label>
              </div>
            </div>
            <div class="form-group col-md-2 col-xs-12">
              <div class="checkbox">
                <label><input type="checkbox" name="regexp" id="sermnfregexp"> {#TEXT_78#}</label>
              </div>
            </div>
            <div class="form-group col-md-3 col-xs-12">
              <div class="checkbox">
                <label><input type="checkbox" name="minus" id="sermnfminus"> {#TEXT_79#}</label>
              </div>
            </div>
 
    </div>
    <div class="row">

            <div class="form-group col-md-3 col-xs-12">
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">{#TEXT_83#}</button>
              </div>
            </div>  
            <div class="form-group col-md-3 col-xs-12">
              <div id="sermnfgrouping1Div">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="grouping1" id="sermnfgrouping1">
                    <option value="">&nbsp;</option>
                    <option value="year"           > {#TEXT_57#}</option>
                    <option value="month"          > {#TEXT_56#}</option>
                    <option value="contractpartner"> {#TEXT_2#}</option>
                  </select>
                </div>
                <label for="sermnfgrouping1">{#TEXT_80#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>
            <div class="form-group col-md-3 col-xs-12">
              <div id="sermnfgrouping2Div">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="grouping2" id="sermnfgrouping2">
                    <option value="">&nbsp;</option>
                    <option value="year"           > {#TEXT_57#}</option>
                    <option value="month"          > {#TEXT_56#}</option>
                    <option value="contractpartner"> {#TEXT_2#}</option>
                  </select>
                </div>
                <label for="sermnfgrouping2">{#TEXT_103#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>
            <div class="form-group col-md-3 col-xs-12">
              <div id="sermnforderDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="order" id="sermnforder">
                    <option value="">&nbsp;</option>
                    <option value="grouping"> {#TEXT_105#}</option>
                    <option value="amount"  > {#TEXT_18#}</option>
                    <option value="comment" > {#TEXT_21#}</option>
                  </select>
                </div>
                <label for="sermnforder">{#TEXT_104#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

    </div>

        </form>
      </div>

{if $SEARCH_DONE == 1}
        <div class="row">
          <div class="col-xs-12">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_375#}</h4>
              </div>
              <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      {if $COLUMNS.year  == "1"}<th class="text-center">{#TEXT_81#}</th>{/if}
                      {if $COLUMNS.month == "1"}<th class="text-center">{#TEXT_82#}</th>{/if}
                      {if $COLUMNS.name  == "1"}<th class="text-center">{#TEXT_2#}</th>{/if}
                      <th class="text-center">{#TEXT_18#}</th>
                      <th class="text-center">{#TEXT_21#}</th>
                    </tr>
                  </thead>
                  <tbody>
{section name=DATA loop=$RESULTS}
                    <tr>
                      {if $COLUMNS.year  == "1"}<td class="text-center">{$RESULTS[DATA].year}</td>{/if}
                      {if $COLUMNS.month == "1"}<td class="text-center">{$RESULTS[DATA].month}</td>{/if}
                      {if $COLUMNS.name  == "1"}<td class="text-center">{$RESULTS[DATA].name}</td>{/if}
                      <td class="text-right of_number_to_be_evaluated" style="white-space: nowrap;">{$RESULTS[DATA].amount|number_format} {#CURRENCY#}</td>
                      <td>{$RESULTS[DATA].comment}</td>
{/section}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
{/if}

<script>
        var searchParams = {$SEARCHPARAMS};
{literal}
        function preFillFormSearchMoneyflow(formMode) {
          document.searchmoneyflow.sermnfstartdate.value = today;
          document.searchmoneyflow.sermnfenddate.value = today;
          
          for ( var key in searchParams ) {
            value = searchParams[key];
            switch (key) {
              case "equal": 
              case "casesensitive":
              case "regexp":
              case "minus": 
                            if( value == '1' ) {
                              $('#sermnf'+key).prop('checked', true);
                            } else {
                              $('#sermnf'+key).prop('checked', false);
                            }
                            break;
              default:
                            $('#sermnf'+key).val(value);
                            break;
            }
          }

          $('#sermnfform').validator('reset');
          $('#sermnfform').validator('update');
        }

        preFillFormSearchMoneyflow(FORM_MODE_DEFAULT);

        $('#sermnfform').validator();

        $("td.of_number_to_be_evaluated:contains('-')").addClass('red');
</script>

{/literal}
{$FOOTER}

