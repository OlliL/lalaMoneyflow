{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_48#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="deletecontractpartner" id="delmcpform">
          <input type="hidden" name="action"         value="delete_contractpartner_submit">
          <input type="hidden" name="contractpartnerid"    id="delmcpcontractpartnerid">

          <div class="span2 well">

            <div id="deleteContractpartnerErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_41#}</div>
              <div class="col-sm-10" id="delmcpname_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_42#}</div>
              <div class="col-sm-10" id="delmcpstreet_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_43#}</div>
              <div class="col-sm-10" id="delmcppostcode_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_44#}</div>
              <div class="col-sm-10" id="delmcptown_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_45#}</div>
              <div class="col-sm-10" id="delmcpcountry_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_34#}</div>
              <div class="col-sm-10" id="delmcpvalidfrom_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_35#}</div>
              <div class="col-sm-10" id="delmcpvalidtil_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_272#}</div>
              <div class="col-sm-10" id="delmcpmoneyflow_comment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_232#}</div>
              <div class="col-sm-10" id="delmcpmpa_postingaccountname_div"></div>
            </div>
              
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnDeleteContractpartnerCancel()"    >{#TEXT_315#}</button>
              <button type="submit" class="btn btn-danger"                                          >{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>

      <script>

        var deleteContractpartnerJsonFormDefaults = {$JSON_FORM_DEFAULTS};

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
              
       function preFillFormDeleteContractpartner() {
          for ( var key in deleteContractpartnerJsonFormDefaults ) {
            value = deleteContractpartnerJsonFormDefaults[key];
            
            var element = document.getElementById( 'delmcp'+key );
            setElement(element, value);

            element = document.getElementById( 'delmcp'+key+'_div' );
            setElement(element, value);
          }
        }

        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeleteContractpartnerCancel() {
          window.close();
        }

        function ajaxDeleteContractpartnerSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxDeleteContractpartnerError(data) {
        console.log(data);
          clearErrorDiv('deleteContractpartnerErrors');
          populateErrorDiv(data.responseText,'deleteContractpartnerErrorsGoHere','deleteContractpartnerErrors');
        }

        preFillFormDeleteContractpartner();
        
        $("div.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmcpform').validator();
        $('#delmcpform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteContractpartnerSuccess,
            error: ajaxDeleteContractpartnerError
        });

{/literal}
      </script>
{$FOOTER}

