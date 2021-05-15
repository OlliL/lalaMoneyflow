{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_51#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="deletepredefmoneyflow" id="delmpmform">
          <input type="hidden" name="action"               value="delete_predefmoneyflow_submit">
          <input type="hidden" name="predefmoneyflowid"    id="delmpmpredefmoneyflowid">

          <div class="span2 well">

            <div id="deletePreDefMoneyflowErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_18#}</div>
              <div class="col-sm-10" id="delmpmamount_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_2#}</div>
              <div class="col-sm-10" id="delmpmcontractpartnername_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_21#}</div>
              <div class="col-sm-10" id="delmpmcomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_232#}</div>
              <div class="col-sm-10" id="delmpmpostingaccountname_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_19#}</div>
              <div class="col-sm-10" id="delmpmcapitalsourcecomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_206#}</div>
              <div class="col-sm-10" id="delmpmonce_a_month_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_207#}</div>
              <div class="col-sm-10" id="delmpmlast_used_div"></div>
            </div>
              
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnDeletePreDefMoneyflowCancel()"    >{#TEXT_315#}</button>
              <button type="submit" class="btn btn-danger"                                                >{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>

      <script>

        var deletePreDefMoneyflowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var onceAMonth = ["{#TEXT_26#}" , "{#TEXT_25#}" ];

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

        function getMeaning(key, value) {
          if( key == "once_a_month" ) {
            return onceAMonth[value];
          }
        }
              
       function preFillFormDeletePreDefMoneyflow() {
          for ( var key in deletePreDefMoneyflowJsonFormDefaults ) {
            value = deletePreDefMoneyflowJsonFormDefaults[key];
            if(key == 'once_a_month') {
              value = getMeaning(key, +value);
            }
            
            var element = document.getElementById( 'delmpm'+key );
            setElement(element, value);

            element = document.getElementById( 'delmpm'+key+'_div' );
            setElement(element, value);
          }
        }

        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeletePreDefMoneyflowCancel() {
          window.close();
        }

        function ajaxDeletePreDefMoneyflowSuccess(data) {
          opener.location.reload(true);
          window.close();
        }

        function ajaxDeletePreDefMoneyflowError(data) {
          clearErrorDiv('deletePreDefMoneyflowErrors');
          populateErrorDiv(data.responseText,'deletePreDefMoneyflowErrorsGoHere','deletePreDefMoneyflowErrors');
        }

        preFillFormDeletePreDefMoneyflow();
        
        $("div.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmpmform').validator();
        $('#delmpmform').ajaxForm({
            dataType: 'json',
            success: ajaxDeletePreDefMoneyflowSuccess,
            error: ajaxDeletePreDefMoneyflowError
        });

{/literal}
      </script>
{$FOOTER}

