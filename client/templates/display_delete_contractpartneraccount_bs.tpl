{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_266#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="deletecontractpartneraccount" id="delmcaform">
          <input type="hidden" name="action"         value="delete_contractpartneraccount_submit">
          <input type="hidden" name="contractpartneraccountid"  id="delmcacontractpartneraccountid">

          <div class="span2 well">

            <div id="deleteContractpartnerAccountErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_32#}</div>
              <div class="col-sm-10" id="delmcaaccountnumber_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_33#}</div>
              <div class="col-sm-10" id="delmcabankcode_div"></div>
            </div>
                         
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnDeleteContractpartnerAccountCancel()"    >{#TEXT_315#}</button>
              <button type="submit" class="btn btn-danger"                                                       >{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>

      <script>

        var deleteContractpartnerAccountJsonFormDefaults = {$JSON_FORM_DEFAULTS};

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
              
       function preFillFormDeleteContractpartnerAccount() {
          for ( var key in deleteContractpartnerAccountJsonFormDefaults ) {
            value = deleteContractpartnerAccountJsonFormDefaults[key];
            
            var element = document.getElementById( 'delmca'+key );
            setElement(element, value);

            element = document.getElementById( 'delmca'+key+'_div' );
            setElement(element, value);
          }
        }

        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeleteContractpartnerAccountCancel() {
          window.close();
        }

        function ajaxDeleteContractpartnerAccountSuccess(data) {
          opener.location.reload(true);
          window.close();
        }

        function ajaxDeleteContractpartnerAccountError(data) {
          clearErrorDiv('deleteContractpartnerAccountErrors');
          populateErrorDiv(data.responseText,'deleteContractpartnerAccountErrorsGoHere','deleteContractpartnerAccountErrors');
        }

        preFillFormDeleteContractpartnerAccount();
        
        $("div.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmcaform').validator();
        $('#delmcaform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteContractpartnerAccountSuccess,
            error: ajaxDeleteContractpartnerAccountError
        });

{/literal}
      </script>
{$FOOTER}