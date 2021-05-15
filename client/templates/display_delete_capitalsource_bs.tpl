{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_39#}</h4>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="deletecapitalsource" id="delmcsform">
          <input type="hidden" name="action"             value="delete_capitalsource_submit">
          <input type="hidden" name="capitalsourceid"    id="delmcscapitalsourceid">

          <div class="span2 well">

            <div id="deleteCapitalsourceErrorsGoHere">
            </div>

            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_21#}</div>
              <div class="col-sm-10" id="delmcscomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_30#}</div>
              <div class="col-sm-10" id="delmcstypecomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_31#}</div>
              <div class="col-sm-10" id="delmcsstatecomment_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_32#}</div>
              <div class="col-sm-10" id="delmcsaccountnumber_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_33#}</div>
              <div class="col-sm-10" id="delmcsbankcode_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_34#}</div>
              <div class="col-sm-10" id="delmcsvalidfrom_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_35#}</div>
              <div class="col-sm-10" id="delmcsvalidtil_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_210#}</div>
              <div class="col-sm-10" id="delmcsatt_group_use_div"></div>
            </div>
            <div class="row">
              <div class="col-sm-2 col-xs-5" style="font-weight:700;font-size:10.5px">{#TEXT_282#}</div>
              <div class="col-sm-10" id="delmcsimport_allowed_div"></div>
            </div>
              
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnDeleteCapitalsourceCancel()"    >{#TEXT_315#}</button>
              <button type="submit" class="btn btn-danger"                                              >{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>

      <script>

        var deleteCapitalsourceJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var attGroupUse = ["{#TEXT_26#}" , "{#TEXT_25#}" ];
        var importAllowed = ["{#TEXT_26#}" , "{#TEXT_28#}" , "{#TEXT_298#}" ];

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
          if( key == "att_group_use" ) {
            return attGroupUse[value];
          } else if ( key == "import_allowed" ) {
            return importAllowed[value];
          }
        }

        function preFillFormDeleteCapitalsource() {
          for ( var key in deleteCapitalsourceJsonFormDefaults ) {
            value = deleteCapitalsourceJsonFormDefaults[key];

            if(key == "att_group_use" || key == "import_allowed") {
              if(value == "0") {
                color = "red";
              } else {
                color = "green";
              }
              value = '<b><font color="' + color + '">' + getMeaning(key, value) + "</font></b>";
            }
            
            var element = document.getElementById( 'delmcs'+key );
            setElement(element, value);

            element = document.getElementById( 'delmcs'+key+'_div' );
            setElement(element, value);
          }
        }

        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeleteCapitalsourceCancel() {
          window.close();
        }

        function ajaxDeleteCapitalsourceSuccess(data) {
          opener.location.reload(true);
          window.close();
        }

        function ajaxDeleteCapitalsourceError(data) {
          clearErrorDiv('deleteCapitalsourceErrors');
          populateErrorDiv(data.responseText,'deleteCapitalsourceErrorsGoHere','deleteCapitalsourceErrors');
        }

        preFillFormDeleteCapitalsource();
        
        $("div.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmcsform').validator();
        $('#delmcsform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteCapitalsourceSuccess,
            error: ajaxDeleteCapitalsourceError
        });

{/literal}
      </script>
{$FOOTER}

