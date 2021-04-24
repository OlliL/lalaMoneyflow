  {$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_356#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="editetfflow" id="deletfflwform">
          <input type="hidden" name="action"    value="delete_etf_flow">
          <input type="hidden" name="etfflowid" value="{$ETFFLOWID}">

          <div class="span2 well">

            <div id="deleteEtfFlowErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-4" style="font-weight:700;font-size:10.5px">{#TEXT_331#}</div>
              <div class="col-sm-10" id="deletfflwisin_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-4" style="font-weight:700;font-size:10.5px">{#TEXT_16#}</div>
              <div class="col-sm-10" id="deletfflwdate_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-4" style="font-weight:700;font-size:10.5px">{#TEXT_359#}</div>
              <div class="col-sm-10" id="deletfflwtime_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-4" style="font-weight:700;font-size:10.5px">{#TEXT_333#}</div>
              <div class="col-sm-10" id="deletfflwamount_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-4" style="font-weight:700;font-size:10.5px">{#TEXT_357#}</div>
              <div class="col-sm-10" id="deletfflwprice_div"></div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnDeleteMoneyflowCancel()"    >{#TEXT_315#}</button>
              <button type="submit" class="btn btn-danger"                                          >{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>

      <script>
        var deleteEtfFlowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var deleteEtfData = {$ETF_VALUES};
        var deleteEtfFlowId = "{$ETFFLOWID}";

        function setElement(element, value) {
            if ( element !== null ) {
              if ( element.tagName == 'DIV' ) {
                element.innerHTML = value;
              } else {
                element.value = value;
              }
            }
        }

        function preFillFormDeleteEtfFlow() {
            for ( var key in deleteEtfFlowJsonFormDefaults ) {
              value = deleteEtfFlowJsonFormDefaults[key];
              if ( key == 'price' ) {
                value = value.toFixed(3).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " " + currency;
              } else if ( key == 'isin' ) {
                for( const etf of deleteEtfData ) {
                  if ( etf['isin'] == value ) {
                    value = etf['name']
                  }
                }
              }
                
              var element = document.getElementById( 'deletfflw'+key );
              setElement(element, value);

              element = document.getElementById( 'deletfflw'+key+'_div' );
              setElement(element, value);
            }

            if('timestamp' in deleteEtfFlowJsonFormDefaults) {
              var timestamp = deleteEtfFlowJsonFormDefaults["timestamp"];
              var datetime = timestamp.split(" ");
              $('#deletfflwdate_div').html(datetime[0]);
              $('#deletfflwtime_div').html(datetime[1]);
            }
        }

        function btnDeleteEtfFlowCancel() {
          window.close();
        }

        function ajaxDeleteEtfFlowSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxDeleteEtfFlowError(data) {
        alert(data.responseText);
          clearErrorDiv('deleteEtfFlowErrors');
          populateErrorDiv(data.responseText,'deleteEtfFlowErrorsGoHere','deleteEtfFlowErrors');
        }

        preFillFormDeleteEtfFlow();
        $('#deletfflwform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteEtfFlowSuccess,
            error: ajaxDeleteEtfFlowError
        });



      </script>

{$FOOTER}