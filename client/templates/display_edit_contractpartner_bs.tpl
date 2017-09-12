{if !$IS_EMBEDDED}
  {$HEADER}
{/if}
      <div class="container">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcontractpartner">
          <input type="hidden" name="action"            value="edit_contractpartner_submit">
          <input type="hidden" name="contractpartnerid" value="{$CONTRACTPARTNERID}">

          <div class="text-center">
            <h4>{if $CONTRACTPARTNERID > 0}{#TEXT_46#}{else}{#TEXT_11#}{/if}</h4>
         </div>

          <div class="span2 well">

            <div id="editContractpartnerErrorsGoHere">
            </div>


            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edcontname" name="all_data[name]" required data-error="{#TEXT_313#}">
              </div>
              <label for="edcontname">{#TEXT_41#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="edcontmoneyflow_comment" name="all_data[moneyflow_comment]">
              </div>
              <label for="edcontmoneyflow_comment">{#TEXT_272#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mpa_postingaccountid]" id="edcontmpa_postingaccountid">
                  <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
                  <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
                </select>
{if $IS_ADMIN }
                <span class="input-group-btn">
                  <button type="button" class="btn">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
{/if}
              </div>
              <label for="edcontmpa_postingaccountid">{#TEXT_316#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="panel-group">
              <div class="panel panel-default">
              
                <div class="panel-heading">
                  <a data-toggle="collapse" href="#edcontcollapse1">{#TEXT_314#}</a>
                </div>
                
                <div id="edcontcollapse1" class="panel-collapse collapse panel-footer">
                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="edcontstreet" name="all_data[street]">
                    </div>
                    <label for="edcontstreet">{#TEXT_42#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="number" class="form-control" id="edcontpostcode" name="all_data[postcode]">
                    </div>
                    <label for="edcontpostcode">{#TEXT_43#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="edconttown" name="all_data[town]">
                    </div>
                    <label for="edconttown">{#TEXT_44#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="edcontcountry" name="all_data[country]">
                    </div>
                    <label for="edcontcountry">{#TEXT_45#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class='input-group date col-xs-12' id="edcontvalidfromDiv">
                      <input type="text" class="form-control" name="all_data[validfrom]" id="edcontvalidfrom" required data-error="{#TEXT_238#}">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                    <label for="edcontvalidfrom">{#TEXT_34#}</label>
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">
                      $(function () {
                          $('#edcontvalidfromDiv').datetimepicker({
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
                    <div class='input-group date col-xs-12' id="edcontvalidtilDiv">
                      <input type="text" class="form-control" name="all_data[validtil]" id="edcontvalidtil" required data-error="{#TEXT_239#}">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                    <label for="edcontvalidtil">{#TEXT_35#}</label>
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">
                      $(function () {
                        $('#edcontvalidtilDiv').datetimepicker({
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
              </div>
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn"             onclick="btnEditContractpartnerCancel()"    >{#TEXT_315#}</button>
              <button type="button" class="btn btn-default" onclick="resetFormEditContractpartner()">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
      
      <script>

        var editContractpartnerJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var editContractpartnerId = "{$CONTRACTPARTNERID}";

        function resetFormEditContractpartner() {
          if ( +editContractpartnerId > 0 ) {
            preFillFormEditContractpartner(FORM_MODE_DEFAULT);
          } else {
            preFillFormEditContractpartner(FORM_MODE_EMPTY);
          }
        }

        function preFillFormEditContractpartner(formMode) {

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.editcontractpartner.edcontname.value = "";
            document.editcontractpartner.edcontstreet.value = "";
            document.editcontractpartner.edcontpostcode.value = "";
            document.editcontractpartner.edconttown.value = "";
            document.editcontractpartner.edcontcountry.value = "";
            document.editcontractpartner.edcontvalidfrom.value = today;
            document.editcontractpartner.edcontvalidtil.value = maxDate;      
            document.editcontractpartner.edcontmoneyflow_comment.value = "";
            document.editcontractpartner.edcontmpa_postingaccountid.value = "";

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editContractpartnerErrors");
            } else {
              for ( var key in editContractpartnerJsonFormDefaults ) {
                var element = document.getElementById( 'edcont'+key );
                if ( element !== null ) {
                  element.value = editContractpartnerJsonFormDefaults[key];
                }
              }
            }
          }

          $('form[name=editcontractpartner]').validator('reset');
          $('form[name=editcontractpartner]').validator('update');
        }

        function btnEditContractpartnerCancel() {
{if $IS_EMBEDDED}
          preFillFormEditContractpartner(FORM_MODE_EMPTY);
          hideOverlayContractpartner();
{else}
          window.close();
{/if}
        }

        function ajaxEditContractpartnerSuccess(data) {
{if $IS_EMBEDDED}
          if(data != null) {
            updateContractpartnerSelect(data["contractpartnerid"]
                                       ,document.editcontractpartner.edcontname.value
                                       ,document.editcontractpartner.edcontmoneyflow_comment.value
                                       ,document.editcontractpartner.edcontmpa_postingaccountid.value );
          }
          preFillFormEditContractpartner(FORM_MODE_EMPTY);
          hideOverlayContractpartner();
{else}
          window.close();
{/if}
        }

        function ajaxEditContractpartnerError(data) {
          clearErrorDiv('editContractpartnerErrors');
          populateErrorDiv(data.responseText,'editContractpartnerErrorsGoHere','editContractpartnerErrors');
        }


        $('form[name=editcontractpartner]').ajaxForm({
            dataType: 'json',
            success: ajaxEditContractpartnerSuccess,
            error: ajaxEditContractpartnerError
        });



        preFillFormEditContractpartner(FORM_MODE_DEFAULT);
        $('form[name=editcontractpartner]').validator();
      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

