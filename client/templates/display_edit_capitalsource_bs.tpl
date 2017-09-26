{if !$IS_EMBEDDED}
  {$HEADER}
      <div class="container container-small">
{else}
      <div>
{/if}

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcapitalsource" id="edtmcsform">
          <input type="hidden" name="action"            value="edit_capitalsource_submit">
          <input type="hidden" name="capitalsourceid"   value="{$CAPITALSOURCEID}">

          <div class="text-center">
            <h4>{if $CAPITALSOURCEID > 0}{#TEXT_38#}{else}{#TEXT_10#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editCapitalsourceErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcscomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
                </div>
                <label for="edtmcscomment">{#TEXT_21#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[type]" id="edtmcstype" required data-error="{#TEXT_322#}">
                    <option value="">&nbsp;</option>
{section name=TYPE loop=$TYPE_VALUES}
                    <option value="{$TYPE_VALUES[TYPE].value}" > {$TYPE_VALUES[TYPE].text}</option>
{/section}
                  </select>
                </div>
                <label for="edtmcstype">{#TEXT_30#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[state]" id="edtmcsstate" required data-error="{#TEXT_323#}">
                    <option value="">&nbsp;</option>
{section name=STATE loop=$STATE_VALUES}
                    <option value="{$STATE_VALUES[STATE].value}" > {$STATE_VALUES[STATE].text}</option>
{/section}
                  </select>
                </div>
                <label for="edtmcsstate">{#TEXT_31#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcsaccountnumber" name="all_data[accountnumber]">
                </div>
                <label for="edtmcsaccountnumber">{#TEXT_32#}</label>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcsbankcode" name="all_data[bankcode]">
                </div>
                <label for="edtmcsbankcode">{#TEXT_33#}</label>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class='input-group date col-xs-12' id="edtmcsvalidfromDiv">
                  <input type="text" class="form-control" name="all_data[validfrom]" id="edtmcsvalidfrom" required data-error="{#TEXT_238#}">
                  <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                </div>
                <label for="edtmcsvalidfrom">{#TEXT_34#}</label>
                <div class="help-block with-errors"></div>
                <script type="text/javascript">
                  $(function () {
                    $('#edtmcsvalidfromDiv').datetimepicker({
                      format: 'YYYY-MM-DD',
                      focusOnShow: false,
                      showClear: true,
                      showTodayButton: true,
                      showClose: true
                    });
                  });
                </script>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class='input-group date col-xs-12' id="edtmcsvalidtilDiv">
                  <input type="text" class="form-control" name="all_data[validtil]" id="edtmcsvalidtil" required data-error="{#TEXT_239#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="edtmcsvalidtil">{#TEXT_35#}</label>
                <div class="help-block with-errors"></div>
                <script type="text/javascript">
                  $(function () {
                    $('#edtmcsvalidtilDiv').datetimepicker({
                      format: 'YYYY-MM-DD',
                      focusOnShow: false,
                      showClear: true,
                      showTodayButton: true,
                      showClose: true
                    });
                  });
                </script>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[att_group_use]" id="edtmcsatt_group_use" required data-error="{#TEXT_326#}">
                    <option value="">&nbsp;</option>
                    <option value=0>{#TEXT_26#}</option>
                    <option value=1>{#TEXT_25#}</option>
                  </select>
                </div>
                <label for="edtmcsatt_group_use">{#TEXT_210#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[import_allowed]" id="edtmcsimport_allowed" required data-error="{#TEXT_327#}">
                    <option value="">&nbsp;</option>
                    <option value=0>{#TEXT_26#}</option>
                    <option value=1>{#TEXT_28#}</option>
                    <option value=2>{#TEXT_298#}</option>
                  </select>
                </div>
                <label for="edtmcsimport_allowed">{#TEXT_282#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn"             onclick="btnEditCapitalsourceCancel()"    >{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditCapitalsource()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
              </div>  
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
            document.editcapitalsource.edtmcscomment.value = "";
            document.editcapitalsource.edtmcstype.selectedIndex = 0;
            document.editcapitalsource.edtmcsstate.selectedIndex = 0;
            document.editcapitalsource.edtmcsaccountnumber.value = "";
            document.editcapitalsource.edtmcsbankcode.value = "";
            document.editcapitalsource.edtmcsvalidfrom.value = today;
            document.editcapitalsource.edtmcsvalidtil.value = maxDate;      
            document.editcapitalsource.edtmcsatt_group_use.selectedIndex = 0;
            document.editcapitalsource.edtmcsimport_allowed.selectedIndex = 0;

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editCapitalsourceErrors");
            } else {
              for ( var key in editCapitalsourceJsonFormDefaults ) {
                var element = document.getElementById( 'edtmcs'+key );
                if ( element !== null ) {
                  element.value = editCapitalsourceJsonFormDefaults[key];
                }
              }
            }
          }

          $('#edtmcsform').validator('reset');
          $('#edtmcsform').validator('update');
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
          if(data != null && document.editcapitalsource.edtmcstype.value != 5) {
            updateCapitalsourceSelect(data["capitalsourceid"]
                                       ,document.editcapitalsource.edtmcscomment.value );
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

        preFillFormEditCapitalsource(FORM_MODE_DEFAULT);
        $('#edtmcsform').validator();
        $('#edtmcsform').ajaxForm({
            dataType: 'json',
            success: ajaxEditCapitalsourceSuccess,
            error: ajaxEditCapitalsourceError
        });
      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

