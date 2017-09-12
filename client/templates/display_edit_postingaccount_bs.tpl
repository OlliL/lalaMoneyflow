{if !$IS_EMBEDDED}
  {$HEADER}
{/if}
      <div class="container">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editpostingaccount">
          <input type="hidden" name="action"            value="edit_postingaccount_submit">
          <input type="hidden" name="postingaccountid"  value="{$POSTINGACCOUNTID}">

          <div class="text-center">
            <h4>{if $POSTINGACCOUNTID > 0}{#TEXT_321#}{else}{#TEXT_320#}{/if}</h4>
         </div>

          <div class="span2 well">

            <div id="editPostingAccountErrorsGoHere">
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edpostaccname" name="all_data[name]" required data-error="{#TEXT_319#}">
              </div>
              <label for="edpostaccname">{#TEXT_232#}</label>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnEditPostingAccountCancel()"    >{#TEXT_315#}</button>
              <button type="button" class="btn btn-default" onclick="resetFormEditPostingAccount()">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
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

        function deleteEditPostingAccountErrors() {
          var element = document.getElementById("editPostingAccountErrors");
          while ( element != null ) {
            element.outerHTML = "";
            delete element;
            element = document.getElementById("editPostingAccountErrors");
          }
        }

        function preFillFormEditPostingAccount(jsonPreDefMoneyflowIndex) {

          if ( jsonPreDefMoneyflowIndex == FORM_MODE_DEFAULT || jsonPreDefMoneyflowIndex == FORM_MODE_EMPTY ) {
            document.editpostingaccount.edpostaccname.value = "";

            if( jsonPreDefMoneyflowIndex == FORM_MODE_EMPTY) {
              deleteEditPostingAccountErrors();
            } else {
              if ( "name" in editPostingAccountJsonFormDefaults ) {
                document.editpostingaccount.edpostaccname.value = editPostingAccountJsonFormDefaults["name"];
              }
            }
          }

          $('form[name=editpostingaccount]').validator('reset');
          $('form[name=editpostingaccount]').validator('update');
        }


        preFillFormEditPostingAccount(FORM_MODE_DEFAULT);

        $('form[name=editpostingaccount]').validator();
      
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
                                       ,document.editpostingaccount.edpostaccname.value );
          }
          preFillFormEditPostingAccount(FORM_MODE_EMPTY);
          hideOverlayPostingAccount();
{else}
          window.close();
{/if}
        }

        function ajaxEditPostingAccountError(data) {
          deleteEditPostingAccountErrors();
          var responseText = $.parseJSON(data.responseText);
          var length = responseText.length;

          element = document.getElementById("editPostingAccountErrorsGoHere");
  
          for(i=0 ; i < length ; i++ ) {
          	var errorDiv = document.createElement('div');
          	errorDiv.id = 'editPostingAccountErrors';
          	errorDiv.className = 'alert alert-danger';
          	errorDiv.innerHTML = responseText[i]; 
          	element.appendChild(errorDiv);
          }
        }


        $('form[name=editpostingaccount]').ajaxForm({
            dataType: 'json',
            success: ajaxEditPostingAccountSuccess,
            error: ajaxEditPostingAccountError
        });

      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

