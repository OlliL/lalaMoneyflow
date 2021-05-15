  {$HEADER}
      <div class="container container-small">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcontractpartneraccount" id="edtmcaform">
          <input type="hidden" name="action"            value="edit_contractpartneraccount_submit">
          <input type="hidden" name="contractpartneraccountid" value="{$CONTRACTPARTNERACCOUNTID}">
          <input type="hidden" name="contractpartnerid"        value="{$CONTRACTPARTNERID}">

          <div class="text-center">
            <h4>{if $CONTRACTPARTNERACCOUNTID > 0}{#TEXT_264#}{else}{#TEXT_265#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editContractpartnerAccountErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcaaccountnumber" name="all_data[accountnumber]" required data-error="{#TEXT_274#}">
                </div>
                <label for="edtmcaaccountnumber">{#TEXT_32#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcabankcode" name="all_data[bankcode]" required data-error="{#TEXT_275#}">
               </div>
               <label for="edtmcabankcode">{#TEXT_33#}</label>
               <div class="help-block with-errors"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-sm-12 text-center">
              <span>
                <button type="button" class="btn"             onclick="btnEditContractpartnerAccountCancel()">{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditContractpartnerAccount()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                                                >{#TEXT_22#}</button>
              </span>
            </div>  
          </div>
        </form>
      </div>
      
      <script>

        var editContractpartnerJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var editContractpartnerAccountId = "{$CONTRACTPARTNERACCOUNTID}";

        function resetFormEditContractpartnerAccount() {
          if ( +editContractpartnerAccountId > 0 ) {
            preFillFormEditContractpartnerAccount(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditContractpartnerAccount(FORM_MODE_EMPTY);
          }
        }

        function preFillFormEditContractpartnerAccount(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.editcontractpartneraccount.edtmcaaccountnumber.value = "";
            document.editcontractpartneraccount.edtmcabankcode.value = "";

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editContractpartnerAccountErrors");
            } else {
              for ( var key in editContractpartnerJsonFormDefaults ) {
                var element = document.getElementById( 'edtmca'+key );
                if ( element !== null ) {
                  element.value = editContractpartnerJsonFormDefaults[key];
                }
              }
            }
          }

          $('#edtmcaform').validator('reset');
          $('#edtmcaform').validator('update');
        }

        function btnEditContractpartnerAccountCancel() {
          window.close();
        }

        function ajaxEditContractpartnerAccountSuccess(data) {
          opener.location.reload(true);
          window.close();
        }

        function ajaxEditContractpartnerAccountError(data) {
          clearErrorDiv('editContractpartnerAccountErrors');
          populateErrorDiv(data.responseText,'editContractpartnerAccountErrorsGoHere','editContractpartnerAccountErrors');
        }

        $('#edtmcaform').validator();
        preFillFormEditContractpartnerAccount(FORM_MODE_DEFAULT);
        $('#edtmcaform').ajaxForm({
            dataType: 'json',
            success: ajaxEditContractpartnerAccountSuccess,
            error: ajaxEditContractpartnerAccountError
        });
      </script>

{$FOOTER}