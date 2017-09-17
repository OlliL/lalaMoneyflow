{if !$IS_EMBEDDED}
  {$HEADER}
      <div class="container container-small">
{else}
      <div>
{/if}

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editpostingaccount" id="edtmpaform">
          <input type="hidden" name="action"            value="edit_postingaccount_submit">
          <input type="hidden" name="postingaccountid"  value="{$POSTINGACCOUNTID}">

          <div class="text-center">
            <h4>{if $POSTINGACCOUNTID > 0}{#TEXT_321#}{else}{#TEXT_320#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editPostingAccountErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmpaname" name="all_data[name]" required data-error="{#TEXT_319#}">
                </div>
                <label for="edtmpaname">{#TEXT_232#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn"             onclick="btnEditPostingAccountCancel()">{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditPostingAccount()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                                        >{#TEXT_22#}</button>
              </div>
            </div>  
          </div>  

        </form>
      </div>
      
      <script>

        var editPostingAccountJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var editPostingAccountId = "{$POSTINGACCOUNTID}";

        function resetFormEditPostingAccount() {
          if ( +editPostingAccountId > 0 ) {
            preFillFormEditPostingAccount(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditPostingAccount(FORM_MODE_EMPTY);
          }
        }

        function preFillFormEditPostingAccount(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.editpostingaccount.edtmpaname.value = "";

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editPostingAccountErrors");
            } else {
              for ( var key in editPostingAccountJsonFormDefaults ) {
                var element = document.getElementById( 'edtmpa'+key );
                if ( element !== null ) {
                  element.value = editPostingAccountJsonFormDefaults[key];
                }
              }
            }
          }

          $('#edtmpaform').validator('reset');
          $('#edtmpaform').validator('update');
        }

        function btnEditPostingAccountCancel() {
{if $IS_EMBEDDED}
          preFillFormEditPostingAccount(FORM_MODE_EMPTY);
          hideOverlayPostingAccount();
{else}
          window.close();
{/if}
        }

        function ajaxEditPostingAccountSuccess(data) {
{if $IS_EMBEDDED}
          if(data != null) {
            updatePostingAccountSelect(data["postingaccountid"]
                                       ,document.editpostingaccount.edtmpaname.value );
          }
          preFillFormEditPostingAccount(FORM_MODE_EMPTY);
          hideOverlayPostingAccount();
{else}
          window.close();
{/if}
        }

        function ajaxEditPostingAccountError(data) {
          clearErrorDiv("editPostingAccountErrors");
          populateErrorDiv(data.responseText,'editPostingAccountErrorsGoHere','editPostingAccountErrors');
        }

        preFillFormEditPostingAccount(FORM_MODE_DEFAULT);
        $('#edtmpaform').validator();
        $('#edtmpaform').ajaxForm({
            dataType: 'json',
            success: ajaxEditPostingAccountSuccess,
            error: ajaxEditPostingAccountError
        });
      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

