{if !$IS_EMBEDDED}
  {$HEADER}
      <div class="container container-small">
{else}
      <div>
{/if}

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editpredefmoneyflow" id="edtmpmform">
          <input type="hidden" name="action"            value="edit_predefmoneyflow_submit">
          <input type="hidden" name="predefmoneyflowid" value="{$PREDEFMONEYFLOWID}">

          <div class="text-center">
            <h4>{if $PREDEFMONEYFLOWID > 0}{#TEXT_49#}{else}{#TEXT_12#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editPreDefMoneyflowErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group col-xs-12">
                <span class="has-float-label">
                  <div class="input-group col-xs-12">
                    <input type="number" step="0.01" class="form-control" lang="en" id="edtmpmamount" name="all_data[amount]" required data-error="{#TEXT_306#}" autofocus>
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-euro"></span>
                    </span>
                  </div>
                  <label for="edtmpmamount">{#TEXT_18#}</label>
                </span>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcp_contractpartnerid]" id="edtmpmmcp_contractpartnerid" required data-error="{#TEXT_307#}">
                    <option value="">&nbsp;</option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
                    <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
                  </select>
                  <span class="input-group-btn">
                    <button type="button" class="btn" onclick="showOverlayContractpartner()">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </span>
                </div>
                <label for="edtmpmmcp_contractpartnerid">{#TEXT_2#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmpmcomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
               </div>
               <label for="edtmpmcomment">{#TEXT_21#}</label>
               <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mpa_postingaccountid]" id="edtmpmmpa_postingaccountid" required data-error="{#TEXT_309#}">
                    <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
                    <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
                  </select>
{if $IS_ADMIN }
                  <span class="input-group-btn">
                    <button type="button" class="btn" onclick="showOverlayPostingAccount()">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </span>
{/if}
                </div>
                <label for="edtmpmmpa_postingaccountid">{#TEXT_316#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
            <div class="form-group col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcs_capitalsourceid]" id="edtmpmmcs_capitalsourceid" required data-error="{#TEXT_310#}">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
                    <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
                  </select>
                  <span class="input-group-btn">
                    <button type="button" class="btn" onclick="showOverlayCapitalsource()">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </span>
                </div>
                <label for="edtmpmmcs_capitalsourceid">{#TEXT_19#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            </div>

            <div class="row">
            <div class="form-group col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[once_a_month]" id="edtmpmonce_a_month">
                    <option value="0">{#TEXT_26#}</option>
                    <option value="1">{#TEXT_25#}</option>
                  </select>
                </div>
                <label for="edtmpmonce_a_month">{#TEXT_206#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            </div>

          </div>
          <div class="row">
            <div class="form-group col-sm-12 text-center">
              <span>
                <button type="button" class="btn"             onclick="btnEditPreDefMoneyflowCancel()">{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditPreDefMoneyflow()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                                         >{#TEXT_22#}</button>
              </span>
            </div>  
          </div>
        </form>
      </div>
      
      <script>

        var editPreDefMoneyflowJsonFormDefaults = {$PRE_JSON_FORM_DEFAULTS};
        var editPreDefMoneyflowId = "{$PREDEFMONEYFLOWID}";

        function resetFormEditPreDefMoneyflow() {
          if ( +editPreDefMoneyflowId > 0 ) {
            preFillFormEditPreDefMoneyflow(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditPreDefMoneyflow(FORM_MODE_EMPTY);
          }
        }

        function preFillFormEditPreDefMoneyflow(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.editpredefmoneyflow.edtmpmamount.value = "";
            document.editpredefmoneyflow.edtmpmmcp_contractpartnerid.value = "";
            document.editpredefmoneyflow.edtmpmcomment.value = "";
            document.editpredefmoneyflow.edtmpmmpa_postingaccountid.value = "";
            document.editpredefmoneyflow.edtmpmmcs_capitalsourceid.value = "";
            document.editpredefmoneyflow.edtmpmonce_a_month.value = 0;

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editPreDefMoneyflowErrors");
            } else {
              for ( var key in editPreDefMoneyflowJsonFormDefaults ) {
                value = editPreDefMoneyflowJsonFormDefaults[key];
                if( key == 'once_a_month' && value === null ) {
                  value = 0;
                } else if( key == 'amount' ) {
                  value = parseFloat(value).toFixed(2);
                }
                var element = document.getElementById( 'edtmpm'+key );
                if ( element !== null ) {
                  element.value = value;
                }
              }
            }
          }

          $('#edtmpmform').validator('reset');
          $('#edtmpmform').validator('update');
        }

        function btnEditPreDefMoneyflowCancel() {
          window.close();
        }

        function ajaxEditPreDefMoneyflowSuccess(data) {
          opener.location.reload(true);
          window.close();
        }

        function ajaxEditPreDefMoneyflowError(data) {
          clearErrorDiv('editPreDefMoneyflowErrors');
          populateErrorDiv(data.responseText,'editPreDefMoneyflowErrorsGoHere','editPreDefMoneyflowErrors');
        }

        $('#edtmpmform').validator();
        preFillFormEditPreDefMoneyflow(FORM_MODE_DEFAULT);
        $('#edtmpmform').ajaxForm({
            dataType: 'json',
            success: ajaxEditPreDefMoneyflowSuccess,
            error: ajaxEditPreDefMoneyflowError
        });

      </script>
{$FOOTER}

