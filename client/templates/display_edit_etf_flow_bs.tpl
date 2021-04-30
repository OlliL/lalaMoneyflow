  {$HEADER}
      <div class="container container-small">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editetfflow" id="edtetfflwform">
          <input type="hidden" name="action"    value="edit_etf_flow">
          <input type="hidden" name="etfflowid" value="{$ETFFLOWID}">

          <div class="text-center">
            <h4>{if $ETFFLOWID > 0}{#TEXT_355#}{else}{#TEXT_354#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editEtfFlowErrorsGoHere">
              </div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[isin]" id="edtetfflwisin" required data-error="{#TEXT_344#}">
{section name=ETF loop=$ETF_VALUES}
                  <option value="{$ETF_VALUES[ETF].isin}">{$ETF_VALUES[ETF].name}</option>
{/section}
                </select>
              </div>
              <label for="calcetfisin">{#TEXT_331#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class='input-group date col-xs-12' id="edtmcpvalidfromDiv">
                <input type="text" class="form-control" name="all_data[date]" id="edtetfflwdate" required data-error="{#TEXT_305#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="edtetfflwdate">{#TEXT_16#}</label>
              <div class="help-block with-errors"></div>
              <script>
                $(function () {
                    $('#edtmcpvalidfromDiv').datetimepicker({
                      format: 'YYYY-MM-DD',
                      focusOnShow: false,
                      showClear: true,
                      showTodayButton: true,
                      showClose: true
                    });
                });
              </script>
            </div>

            <div class="form-group has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" lang="en" pattern="{literal}^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]:[0-9]{3}${/literal}" id="edtetfflwtime" name="all_data[time]" required data-error="{#TEXT_358#}">
                </div>
                <label for="deletfflwtime">{#TEXT_359#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.001" class="form-control" lang="en" id="edtetfflwamount" name="all_data[amount]" required data-error="{#TEXT_343#}" autofocus>
                </div>
                <label for="edtetfflwamount">{#TEXT_333#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.001" class="form-control" lang="en" id="edtetfflwprice" name="all_data[price]" required data-error="{#TEXT_360#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="edtetfflwprice">{#TEXT_357#}</label>
              <div class="help-block with-errors"></div>
            </div>


          </div>

          <div class="row">
            <div class="form-group col-sm-12 text-center">
              <span>
                <button type="button" class="btn"             onclick="btnEditEtfFlowCancel()">{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditEtfFlow()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                                 >{#TEXT_22#}</button>
              </span>
            </div>  
          </div>
        </form>
      </div>
      
      <script>

        var editEtfFlowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var editEtfFlowId = "{$ETFFLOWID}";

        function resetFormEditEtfFlow() {
          if ( +editEtfFlowId > 0 ) {
            preFillFormEditEtfFlow(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditEtfFlow(FORM_MODE_EMPTY);
          }
        }

        function preFillFormEditEtfFlow(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {

            document.editetfflow.edtetfflwdate.value = today;
            
            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editEtfFlowErrors");
            } else {
              for ( var key in editEtfFlowJsonFormDefaults ) {
                var element = document.getElementById( 'edtetfflw'+key );
                var value   = editEtfFlowJsonFormDefaults[key]
                if ( key == 'price' ) {
                  value = value.toFixed(3).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                if ( element !== null ) {
                  element.value = value;
                }
              }

              if('timestamp' in editEtfFlowJsonFormDefaults) {
                var timestamp = editEtfFlowJsonFormDefaults["timestamp"];
                var datetime = timestamp.split(" ");
                $('#edtetfflwdate').val(datetime[0]);
                $('#edtetfflwtime').val(datetime[1]);
              }
            }
          }

          $('#edtetfflwform').validator('reset');
          $('#edtetfflwform').validator('update');
        }

        function btnEditEtfFlowCancel() {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxEditEtfFlowSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxEditEtfFlowError(data) {
          clearErrorDiv('editEtfFlowErrors');
          populateErrorDiv(data.responseText,'editEtfFlowErrorsGoHere','editEtfFlowErrors');
        }

        $('#edtetfflwform').validator();
        preFillFormEditEtfFlow(FORM_MODE_DEFAULT);
        $('#edtetfflwform').ajaxForm({
            dataType: 'json',
            success: ajaxEditEtfFlowSuccess,
            error: ajaxEditEtfFlowError
        });



      </script>

{$FOOTER}