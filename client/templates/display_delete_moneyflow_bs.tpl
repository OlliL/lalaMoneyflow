{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_24#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="deletemoneyflow" id="delmnfform">
          <input type="hidden" name="action"         value="delete_moneyflow_submit">
          <input type="hidden" name="moneyflowid"    value="{$MONEYFLOWID}">

          <div class="span2 well">

            <div id="deleteMoneyflowErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_16#}</div>
              <div class="col-sm-10" id="delmnfbookingdate_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_17#}</div>
              <div class="col-sm-10" id="delmnfinvoicedate_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_2#}</div>
              <div class="col-sm-10" id="delmnfcontractpartnername_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_19#}</div>
              <div class="col-sm-10" id="delmnfcapitalsourcecomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_18#}</div>
              <div class="col-sm-10  of_number_to_be_evaluated" id="delmnfamount_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_21#}</div>
              <div class="col-sm-10" id="delmnfcomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_232#}</div>
              <div class="col-sm-10" id="delmnfpostingaccountname_div"></div>
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

        var deleteMoneyflowJsonFormDefaults = {$JSON_FORM_DEFAULTS};

{literal}
        function setElement(element, value) {
            if ( element !== null ) {
              if ( element.tagName == 'DIV' ) {
                element.innerHTML = value;
              } else {
                element.value = value;
              }
            }
        }
              
       function preFillFormDeleteMoneyflow() {
          for ( var key in deleteMoneyflowJsonFormDefaults ) {
            value = deleteMoneyflowJsonFormDefaults[key];
            
            if ( key == 'amount' ) {
              value = value.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " " + currency;
            }
            
            var element = document.getElementById( 'delmnf'+key );
            setElement(element, value);

            element = document.getElementById( 'delmnf'+key+'_div' );
            setElement(element, value);
          }
        }

        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeleteMoneyflowCancel() {
          window.close();
        }

        function ajaxDeleteMoneyflowSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxDeleteMoneyflowError(data) {
          clearErrorDiv('deleteMoneyflowErrors');
          populateErrorDiv(data.responseText,'deleteMoneyflowErrorsGoHere','deleteMoneyflowErrors');
        }

        preFillFormDeleteMoneyflow();
        
        $("div.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmnfform').validator();
        $('#delmnfform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteMoneyflowSuccess,
            error: ajaxDeleteMoneyflowError
        });

{/literal}
      </script>
{$FOOTER}

