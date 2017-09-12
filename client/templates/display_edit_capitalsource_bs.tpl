{if !$IS_EMBEDDED}
  {$HEADER}
{/if}
      <div class="container">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcapitalsource">
          <input type="hidden" name="action"            value="edit_capitalsource_submit">
          <input type="hidden" name="capitalsourceid"   value="{$CAPITALSOURCEID}">

          <div class="text-center">
            <h4>{if $CAPITALSOURCEID > 0}{#TEXT_38#}{else}{#TEXT_10#}{/if}</h4>
         </div>

          <div class="span2 well">

            <div id="editCapitalsourceErrorsGoHere">
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edcapsrccomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
              </div>
              <label for="edcapsrccomment">{#TEXT_21#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[type]" id="edcapsrctype" required data-error="{#TEXT_322#}">
                  <option value="">&nbsp;</option>
{section name=TYPE loop=$TYPE_VALUES}
                  <option value="{$TYPE_VALUES[TYPE].value}" > {$TYPE_VALUES[TYPE].text}</option>
{/section}
                </select>
              </div>
              <label for="edcapsrctype">{#TEXT_30#}</label>
              <div class="help-block with-errors"></div>
            </div>
          
            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[state]" id="edcapsrcstate" required data-error="{#TEXT_323#}">
                  <option value="">&nbsp;</option>
{section name=STATE loop=$STATE_VALUES}
                  <option value="{$STATE_VALUES[STATE].value}" > {$STATE_VALUES[STATE].text}</option>
{/section}
                </select>
              </div>
              <label for="edcapsrcstate">{#TEXT_31#}</label>
              <div class="help-block with-errors"></div>
            </div>
          
            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edcapsrcaccountnumber" name="all_data[accountnumber]"">
              </div>
              <label for="edcapsrcaccountnumber">{#TEXT_32#}</label>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edcapsrcbankcode" name="all_data[bankcode]"">
              </div>
              <label for="edcapsrcbankcode">{#TEXT_33#}</label>
            </div>

            <div class="form-group has-float-label">
              <div class='input-group date col-xs-12' id="edcapsrcvalidfromDiv">
                <input type="text" class="form-control" name="all_data[validfrom]" id="edcapsrcvalidfrom" required data-error="{#TEXT_238#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="edcapsrcvalidfrom">{#TEXT_34#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                $(function () {
                  $('#edcapsrcvalidfromDiv').datetimepicker({
                    format: 'YYYY-MM-DD',
                    focusOnShow: false,
                    showClear: true,
                    showTodayButton: true,
                    showClose: true
                  });
                });
              </script>
            </div>

            <div class="form-group has-float-label">
              <div class='input-group date col-xs-12' id="edcapsrcvalidtilDiv">
                <input type="text" class="form-control" name="all_data[validtil]" id="edcapsrcvalidtil" required data-error="{#TEXT_239#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="edcapsrctilfromSelect">{#TEXT_35#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                $(function () {
                  $('#edcapsrcvalidtilDiv').datetimepicker({
                    format: 'YYYY-MM-DD',
                    focusOnShow: false,
                    showClear: true,
                    showTodayButton: true,
                    showClose: true
                  });
                });
              </script>
            </div>

             <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[att_group_use]" id="edcapsrcatt_group_use" required data-error="{#TEXT_326#}">
                  <option value="">&nbsp;</option>
                  <option value=0>{#TEXT_26#}</option>
                  <option value=1>{#TEXT_25#}</option>
                </select>
              </div>
              <label for="edcapsrcatt_group_use">{#TEXT_210#}</label>
              <div class="help-block with-errors"></div>
            </div>
          
             <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[import_allowed]" id="edcapsrcimport_allowed" required data-error="{#TEXT_327#}">
                  <option value="">&nbsp;</option>
                  <option value=0>{#TEXT_26#}</option>
                  <option value=1>{#TEXT_28#}</option>
                  <option value=2>{#TEXT_298#}</option>
                </select>
              </div>
              <label for="edcapsrcimport_allowed">{#TEXT_282#}</label>
              <div class="help-block with-errors"></div>
            </div>
          
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnEditCapitalsourceCancel()"    >{#TEXT_315#}</button>
              <button type="button" class="btn btn-default" onclick="resetFormEditCapitalsource()">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
      
      <script>

        var editCapitalsourceJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var editCapitalsourceId = "{$CAPITALSOURCEID}";

        function resetFormEditCapitalsource() {
          if ( +editCapitalsourceId > 0 ) {
            preFillFormEditCapitalsource(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditCapitalsource(FORM_MODE_EMPTY);
          }
        }
        
        function preFillFormEditCapitalsource(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.editcapitalsource.edcapsrccomment.value = "";
            document.editcapitalsource.edcapsrctype.selectedIndex = 0;
            document.editcapitalsource.edcapsrcstate.selectedIndex = 0;
            document.editcapitalsource.edcapsrcaccountnumber.value = "";
            document.editcapitalsource.edcapsrcbankcode.value = "";
            document.editcapitalsource.edcapsrcvalidfrom.value = today;
            document.editcapitalsource.edcapsrcvalidtil.value = maxDate;      
            document.editcapitalsource.edcapsrcatt_group_use.selectedIndex = 0;
            document.editcapitalsource.edcapsrcimport_allowed.selectedIndex = 0;

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editCapitalsourceErrors");
            } else {
              for ( var key in editCapitalsourceJsonFormDefaults ) {
                var element = document.getElementById( 'edcapsrc'+key );
                if ( element !== null ) {
                  element.value = editCapitalsourceJsonFormDefaults[key];
                }
              }
            }
          }

          $('form[name=editcapitalsource]').validator('reset');
          $('form[name=editcapitalsource]').validator('update');
        }
      
        function btnEditCapitalsourceCancel() {
{if $IS_EMBEDDED}
          preFillFormEditCapitalsource(FORM_MODE_EMPTY);
          hideOverlayCapitalsource();
{else}
          window.close();
{/if}
        }

        function ajaxEditCapitalsourceSuccess(data) {
{if $IS_EMBEDDED}
          /* Credit Capitalsources may not be booked - so do not add them to the select-list */
          if(data != null && document.editcapitalsource.edcapsrctype.value != 5) {
            updateCapitalsourceSelect(data["capitalsourceid"]
                                       ,document.editcapitalsource.edcapsrccomment.value );
          }
          preFillFormEditCapitalsource(FORM_MODE_EMPTY);
          hideOverlayCapitalsource();
{else}
          window.close();
{/if}
        }

        function ajaxEditCapitalsourceError(data) {
          clearErrorDiv('editCapitalsourceErrors');
          populateErrorDiv(data.responseText,'editCapitalsourceErrorsGoHere','editCapitalsourceErrors');
        }

        $('form[name=editcapitalsource]').ajaxForm({
            dataType: 'json',
            success: ajaxEditCapitalsourceSuccess,
            error: ajaxEditCapitalsourceError
        });


        preFillFormEditCapitalsource(FORM_MODE_DEFAULT);
        $('form[name=editcapitalsource]').validator();
      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

