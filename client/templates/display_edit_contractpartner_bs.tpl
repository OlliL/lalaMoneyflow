{if !$IS_EMBEDDED}
  {$HEADER}
{/if}
      <div class="container">

        <form action="{$ENV_INDEX_PHP}" method="POST" name="editcontractpartner">
          <input type="hidden" name="action"            value="edit_contractpartner">
          <input type="hidden" name="realaction"        value="save">
          <input type="hidden" name="contractpartnerid" value="{$CONTRACTPARTNERID}">
          <input type="hidden" name="REFERER"           value="{$ENV_REFERER}">

          <div class="text-center">
            <h4>{if $CONTRACTPARTNERID > 0}{#TEXT_46#}{else}{#TEXT_11#}{/if}</h4>
         </div>

          <div class="span2 well">

{section name=ERROR loop=$ERRORS}
            <div class="alert alert-danger" id="errors">
              {$ERRORS[ERROR]}
            </div>
{/section}


            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="name" name="all_data[name]" required data-error="{#TEXT_313#}">
              </div>
              <label for="name">{#TEXT_41#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="moneyflow_comment" name="all_data[moneyflow_comment]">
              </div>
              <label for="moneyflow_comment">{#TEXT_272#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mpa_postingaccountid]" id="mpa_postingaccountid">
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
              <label for="mpa_postingaccountid">{#TEXT_316#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="panel-group">
              <div class="panel panel-default">
              
                <div class="panel-heading">
                  <a data-toggle="collapse" href="#collapse1">{#TEXT_314#}</a>
                </div>
                
                <div id="collapse1" class="panel-collapse collapse panel-footer">
                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="street" name="all_data[street]">
                    </div>
                    <label for="street">{#TEXT_42#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="number" class="form-control" id="postcode" name="all_data[postcode]">
                    </div>
                    <label for="postcode">{#TEXT_43#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="town" name="all_data[town]">
                    </div>
                    <label for="town">{#TEXT_44#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class="input-group col-xs-12">
                      <input type="text" class="form-control" id="country" name="all_data[country]">
                    </div>
                    <label for="country">{#TEXT_45#}</label>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group has-float-label">
                    <div class='input-group date col-xs-12' id="validfrom">
                      <input type="text" class="form-control" name="all_data[validfrom]" id="validfromSelect" required data-error="{#TEXT_238#}">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                    <label for="validfromSelect">{#TEXT_34#}</label>
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">
                        $(function () {
                            $('#validfrom').datetimepicker({
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
                    <div class='input-group date col-xs-12' id="validtil">
                      <input type="text" class="form-control" name="all_data[validtil]" id="validtilSelect" required data-error="{#TEXT_239#}">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                    <label for="validtilSelect">{#TEXT_35#}</label>
                    <div class="help-block with-errors"></div>
                    <script type="text/javascript">
                        $(function () {
                            $('#validtil').datetimepicker({
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
              <button type="button" class="btn"             onclick="btnCancel()"    >{#TEXT_315#}</button>
              <button type="button" class="btn btn-default" onclick="preFillForm(-1)">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
      
      <div class="overlay_editcontract_postingaccount" style="display: none"></div>

      <script>

{if $CLOSE == 1}
        opener.location = "{$ENV_REFERER}" ;
        window.open('','_self').close();
{else}

        var jsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var today = "{$TODAY}";

        /* When the page is loaded, the booking form is set to the defaults which might be previous entered data or empty (if the page is initially loaded) */
        var FORM_MODE_DEFAULT = -2;
        /* This is used when in the select box "New booking" is selected explicitly to always null the form */
        var FORM_MODE_EMPTY = -1;

        function btnCancel() {
          $('form[name=editcontractpartner]').validator('destroy');
{if $IS_EMBEDDED}
         $('.overlay_addmoney_contractpartner').toggle();
{else}
          window.close();
{/if}
        }
        function deleteErrors() {
          var element = document.getElementById("errors");
          while ( element != null ) {
            element.outerHTML = "";
            delete element;
            element = document.getElementById("errors");
          }
        }

        function preFillForm(jsonPreDefMoneyflowIndex) {

          if ( jsonPreDefMoneyflowIndex == FORM_MODE_DEFAULT || jsonPreDefMoneyflowIndex == FORM_MODE_EMPTY ) {
            document.editcontractpartner.name.value = "";
            document.editcontractpartner.street.value = "";
            document.editcontractpartner.postcode.value = "";
            document.editcontractpartner.town.value = "";
            document.editcontractpartner.country.value = "";
            document.editcontractpartner.validfromSelect.value = today;
            document.editcontractpartner.validtilSelect.value = "2999-12-31";      
            document.editcontractpartner.moneyflow_comment.value = "";
            document.editcontractpartner.mpa_postingaccountid.value = "";

            if( jsonPreDefMoneyflowIndex == FORM_MODE_EMPTY) {
              deleteErrors();
            } else {
              if ( "name" in jsonFormDefaults ) {
                document.editcontractpartner.name.value = jsonFormDefaults["name"];
              }
              if ( "street" in jsonFormDefaults ) {
                document.editcontractpartner.street.value = jsonFormDefaults["street"];
              }
              if ( "postcode" in jsonFormDefaults ) {
                document.editcontractpartner.postcode.value = jsonFormDefaults["postcode"];
              }
              if ( "town" in jsonFormDefaults ) {
                document.editcontractpartner.town.value = jsonFormDefaults["town"];
              }
              if ( "country" in jsonFormDefaults ) {
                document.editcontractpartner.country.value = jsonFormDefaults["country"];
              }
              if ( "validfrom" in jsonFormDefaults ) {
                document.editcontractpartner.validfromSelect.value = jsonFormDefaults["validfrom"];
              }
              if ( "validtil" in jsonFormDefaults ) {
                document.editcontractpartner.validtilSelect.value = jsonFormDefaults["validtil"];
              }
              if ( "moneyflow_comment" in jsonFormDefaults ) {
                document.editcontractpartner.moneyflow_comment.value = jsonFormDefaults["moneyflow_comment"];
              }
              if ( "mpa_postingaccountid" in jsonFormDefaults ) {
                document.editcontractpartner.mpa_postingaccountid.value = jsonFormDefaults["mpa_postingaccountid"];
              }
            }
          }

          $('form[name=editcontractpartner]').validator('reset');
          $('form[name=editcontractpartner]').validator('update');
        }


        preFillForm(FORM_MODE_DEFAULT);

        $('form[name=editcontractpartner]').validator();
      //  $('.overlay_editcontract_postingaccount').toggle();
{/if}
      </script>

{if !$IS_EMBEDDED}
  {$FOOTER}
{/if}

