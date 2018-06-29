{if !$IS_EMBEDDED}
  {$HEADER}
      <div class="container container-small">
{else}
      <div>
{/if}

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcontractpartner" id="edtmcpform">
          <input type="hidden" name="action"            value="edit_contractpartner_submit">
          <input type="hidden" name="contractpartnerid" value="{$CONTRACTPARTNERID}">

          <div class="text-center">
            <h4>{if $CONTRACTPARTNERID > 0}{#TEXT_46#}{else}{#TEXT_11#}{/if}</h4>
          </div>

          <div class="well">
            <div class="row">
              <div class="col-xs-12" id="editContractpartnerErrorsGoHere">
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcpname" name="all_data[name]" required data-error="{#TEXT_313#}">
                </div>
                <label for="edtmcpname">{#TEXT_41#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="edtmcpmoneyflow_comment" name="all_data[moneyflow_comment]">
               </div>
               <label for="edtmcpmoneyflow_comment">{#TEXT_272#}</label>
               <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="form-group has-float-label col-xs-12">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mpa_postingaccountid]" id="edtmcpmpa_postingaccountid">
                    <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$HEAD_POSTINGACCOUNT_VALUES}
                    <option value="{$HEAD_POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$HEAD_POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
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
                <label for="edtmcpmpa_postingaccountid">{#TEXT_316#}</label>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="row">
              <div class="panel-group">
                <div class="panel panel-default">
              
                  <div class="panel-heading">
                    <a data-toggle="collapse" href="#edtmcpcollapse1">{#TEXT_314#}</a>
                  </div>
                
                  <div id="edtmcpcollapse1" class="panel-collapse collapse panel-footer">
                    <div class="form-group has-float-label">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control" id="edtmcpstreet" name="all_data[street]">
                      </div>
                      <label for="edtmcpstreet">{#TEXT_42#}</label>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group has-float-label">
                      <div class="input-group col-xs-12">
                        <input type="number" class="form-control" id="edtmcppostcode" name="all_data[postcode]">
                      </div>
                      <label for="edtmcppostcode">{#TEXT_43#}</label>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group has-float-label">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control" id="edtmcptown" name="all_data[town]">
                      </div>
                      <label for="edtmcptown">{#TEXT_44#}</label>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group has-float-label">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control" id="edtmcpcountry" name="all_data[country]">
                      </div>
                      <label for="edtmcpcountry">{#TEXT_45#}</label>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group has-float-label">
                      <div class='input-group date col-xs-12' id="edtmcpvalidfromDiv">
                        <input type="text" class="form-control" name="all_data[validfrom]" id="edtmcpvalidfrom" required data-error="{#TEXT_238#}">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <label for="edtmcpvalidfrom">{#TEXT_34#}</label>
                      <div class="help-block with-errors"></div>
                      <script>
                        $(function () {
                            $('#edtmcpvalidfromDiv').datetimepicker({
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
                      <div class='input-group date col-xs-12' id="edtmcpvalidtilDiv">
                        <input type="text" class="form-control" name="all_data[validtil]" id="edtmcpvalidtil" required data-error="{#TEXT_239#}">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                      <label for="edtmcpvalidtil">{#TEXT_35#}</label>
                      <div class="help-block with-errors"></div>
                      <script>
                        $(function () {
                          $('#edtmcpvalidtilDiv').datetimepicker({
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
          </div>

          <div class="row">
            <div class="form-group col-sm-12 text-center">
              <span>
                <button type="button" class="btn"             onclick="btnEditContractpartnerCancel()">{#TEXT_315#}</button>
                <button type="button" class="btn btn-default" onclick="resetFormEditContractpartner()">{#TEXT_304#}</button>
                <button type="submit" class="btn btn-primary"                                         >{#TEXT_22#}</button>
              </span>
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
            document.editcontractpartner.edtmcpname.value = "";
            document.editcontractpartner.edtmcpstreet.value = "";
            document.editcontractpartner.edtmcppostcode.value = "";
            document.editcontractpartner.edtmcptown.value = "";
            document.editcontractpartner.edtmcpcountry.value = "";
            document.editcontractpartner.edtmcpvalidfrom.value = today;
            document.editcontractpartner.edtmcpvalidtil.value = maxDate;      
            document.editcontractpartner.edtmcpmoneyflow_comment.value = "";
            document.editcontractpartner.edtmcpmpa_postingaccountid.value = "";

            if ( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("editContractpartnerErrors");
            } else {
              for ( var key in editContractpartnerJsonFormDefaults ) {
                var element = document.getElementById( 'edtmcp'+key );
                if ( element !== null ) {
                  element.value = editContractpartnerJsonFormDefaults[key];
                }
              }
            }
          }

          $('#edtmcpform').validator('reset');
          $('#edtmcpform').validator('update');
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
                                       ,document.editcontractpartner.edtmcpname.value
                                       ,document.editcontractpartner.edtmcpmoneyflow_comment.value
                                       ,document.editcontractpartner.edtmcpmpa_postingaccountid.value );
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

        $('#edtmcpform').validator();
        preFillFormEditContractpartner(FORM_MODE_DEFAULT);
        $('#edtmcpform').ajaxForm({
            dataType: 'json',
            success: ajaxEditContractpartnerSuccess,
            error: ajaxEditContractpartnerError
        });



      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

