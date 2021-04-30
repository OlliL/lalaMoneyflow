  {$HEADER}
      <div class="container container-wide">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="addimpmonrecp" id="addimpmonrecpform">
          <input type="hidden" name="action"    value="add_imported_moneyflow_receipt">

          <div class="text-center">
            <h4>{#TEXT_362#}</h4>
          </div>

          <div class="row text-center">
            <div class="col-xs-12">{#TEXT_363#}
            </div>
          </div>
          
          <br>

          <div class="row">
            <div class="col-xs-12" id="addImpMonRecpErrorsGoHere">
            </div>
          </div>

          <div class="form-group text-center">
            <div class="col-xs-12">
              <span class="btn btn-default btn-file">
                <input id="file-input" name="all_data[]" type="file" class="file" multiple data-show-upload="true" data-show-caption="true">
              </span>
            </div>
          </div>
        </form>
      </div>
      
      <script>
        function ajaxAddImpMonRecpSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxAddImpMonRecpError(data) {
          clearErrorDiv('addImpMonRecpErrors');
          populateErrorDiv(data.responseText,'addImpMonRecpErrorsGoHere','addImpMonRecpErrors');
        }

        $('#addimpmonrecpform').ajaxForm({
            dataType: 'json',
            success: ajaxAddImpMonRecpSuccess,
            error: ajaxAddImpMonRecpError
        });
        
        // initialize with defaults
	$("#file-input").fileinput();
      </script>

{$FOOTER}