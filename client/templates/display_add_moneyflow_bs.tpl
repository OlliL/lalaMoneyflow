{$HEADER}
      <div class="container">

        <div>
            <select class="form-control" id="selectmoneyflow" onchange="preFillFormAddMoneyflow(this.value)">
              <option value="-1">Neue Buchung</option>
            </select>
        </div>

        <div>&nbsp;</div>

        <form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
          <input type="hidden" name="action"                          value="add_moneyflow">
          <input type="hidden" name="realaction"                      value="save">
          <input type="hidden" name="all_data[predefmoneyflowid]" value="-1"               id="addmnfpredefmoneyflowid" >
          <input type="hidden" name="all_data[checked]"           value="1">

          <div class="span2 well">

            <div id="addmoneyErrorsGoHere">
{section name=ERROR loop=$ERRORS}
              <div class="alert alert-danger" id="addmoneyErrors">
                {$ERRORS[ERROR]}
              </div>
{/section}
            </div>

            <div class="form-group has-float-label">
              <div class='input-group date col-xs-12' id="addmnfbookingdateDiv">
                <input type="text" class="form-control" name="all_data[bookingdate]" id="addmnfbookingdate" required data-error="{#TEXT_305#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="addmnfbookingdate">{#TEXT_16#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                  $(function () {
                      $('#addmnfbookingdateDiv').datetimepicker({
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
              <div class='input-group date col-xs-12' id="addmnfinvoicedateDiv">
                <input type="text" class="form-control" name="all_data[invoicedate]" id="addmnfinvoicedate">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="addmnfinvoicedate">{#TEXT_17#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                  $(function () {
                      $('#addmnfinvoicedateDiv').datetimepicker({
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
                <input type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[amount]" required data-error="{#TEXT_306#}" autofocus>
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-euro"></span>
                </span>
              </div>
              <label for="addmnfamount">{#TEXT_18#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mcp_contractpartnerid]" id="addmnfmcp_contractpartnerid" onchange="setContractpartnerDefaults()" required data-error="{#TEXT_307#}">
                  <option value="">&nbsp;</option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
                  <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="showOverlayContractpartner()">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
              <label for="addmnfmcp_contractpartnerid">{#TEXT_2#}</label>
              <div class="help-block with-errors"></div>
            </div>


            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="addmnfcomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
              </div>
              <label for="addmnfcomment">{#TEXT_21#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mpa_postingaccountid]" id="addmnfmpa_postingaccountid" required data-error="{#TEXT_309#}">
                  <option value="">&nbsp;</option>
{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
                  <option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}"> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
{/section}
                </select>
{if $IS_ADMIN }
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="showOverlayPostingAccount()">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
{/if}
              </div>
              <label for="addmnfmpa_postingaccountid">{#TEXT_232#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mcs_capitalsourceid]" id="addmnfmcs_capitalsourceid" required data-error="{#TEXT_310#}">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
                  <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="showOverlayCapitalsource()">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
              <label for="addmnfmcs_capitalsourceid">{#TEXT_19#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group input-group col-lg-12">
                  <input id="private"  data-toggle="toggle" value="1" data-on="{#TEXT_209#}" data-off="{#TEXT_301#}" data-onstyle="danger" data-offstyle="success" type="checkbox" name="all_data[private]">
                  <input id="favorite" value="1" type="checkbox" name="all_data[save_as_predefmoneyflow]">
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn btn-default" onclick="resetFormAddMoneyflow()">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
      

      <script>

        var addMoneyflowJsonPreDefMoneyflows = {$JSON_PREDEFMONEYFLOWS};
        var addMoneyflowJsonContractpartner = {$JSON_CONTRACTPARTNER};
        var addMoneyflowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var onEmpty = "{#TEXT_302#}";
        var offEmpty = "{#TEXT_303#}";
        var onFavorite = "{#TEXT_311#}";
        var offFavorite = "{#TEXT_312#}";


        function resetFormAddMoneyflow() {
          preFillFormAddMoneyflow(FORM_MODE_EMPTY);
        }

        function setContractpartnerDefaults() {
          var length = addMoneyflowJsonContractpartner.length;
          var selectedValue = document.addmoney.addmnfmcp_contractpartnerid;
          
          for (i=0 ; i<length ; i++) {
            if (addMoneyflowJsonContractpartner[i]["contractpartnerid"] == document.addmoney.addmnfmcp_contractpartnerid.value) {
              if ( addMoneyflowJsonContractpartner[i]["moneyflow_comment"] != null ) {
                document.addmoney.addmnfcomment.value = addMoneyflowJsonContractpartner[i]["moneyflow_comment"];
              }
              if ( addMoneyflowJsonContractpartner[i]["mpa_postingaccountid"] != null ) {
                document.addmoney.addmnfmpa_postingaccountid.value = addMoneyflowJsonContractpartner[i]["mpa_postingaccountid"];
              }
              break;
            }
          }
        }

        function fillSelectMoneyflow(currency, addMoneyflowJsonPreDefMoneyflows) {
          var jsonPredefmoneyflowsSize = addMoneyflowJsonPreDefMoneyflows.length;

          var select = document.getElementById('selectmoneyflow');

          for (var i = 0; i < jsonPredefmoneyflowsSize; i++){
            var preDefMoneyflow = addMoneyflowJsonPreDefMoneyflows[i];

            var opt = document.createElement('option');
            opt.value = i;
            opt.innerHTML =  preDefMoneyflow["contractpartnername"] +
                             " | " + 
                             parseFloat(preDefMoneyflow["amount"]).toFixed(2) + 
                             currency + 
                             " | " + 
                             preDefMoneyflow["comment"];

            select.appendChild(opt);
          }
        }

        function preFillFormAddMoneyflow(formMode) {

          var favoriteOn = onEmpty;
          var favoriteOff = offEmpty;

          $(function() {
            $('#favorite').bootstrapToggle('destroy');
          })

          if ( formMode == FORM_MODE_DEFAULT || formMode == FORM_MODE_EMPTY ) {
            document.addmoney.addmnfpredefmoneyflowid.value = -1;
            document.addmoney.addmnfbookingdate.value = today;
            document.addmoney.addmnfinvoicedate.value = "";      
            document.addmoney.addmnfamount.value = "";
            document.addmoney.addmnfmcp_contractpartnerid.value = "";
            document.addmoney.addmnfcomment.value = "";
            document.addmoney.addmnfmpa_postingaccountid.value = "";
            document.addmoney.addmnfmcs_capitalsourceid.selectedIndex = 0;

            if( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("addmoneyErrors");
              $(function() {
                $('#favorite').prop('checked', false).change();
              })

              var select = document.getElementById('selectmoneyflow');
              select.selectedIndex = 0;
            } else {
              for ( var key in addMoneyflowJsonFormDefaults ) {
                var element = document.getElementById( 'addmnf'+key );

                if ( element !== null ) {
                  if ( key == "save_as_predefmoneyflow") {
                    if ( addMoneyflowJsonFormDefaults["save_as_predefmoneyflow"] == "1" ) {
                      $(function() {
                        $('#favorite').prop('checked', true).change();
                      })
                    }
                  } else if ( key == "private") {
                    if ( addMoneyflowJsonFormDefaults["private"] == "1" ) {
                      $(function() {
                        $('#private').prop('checked', true).change();
                      })
                    }
                  } else {
                    element.value = addMoneyflowJsonFormDefaults[key];
                  }
                }
              }
              
              if ( document.addmoney.addmnfpredefmoneyflowid >= 0 ) {
                favoriteOn = onFavorite;
                favoriteOff = offFavorite;
              }
             }
          } else if  ( (+formMode) >= 0 && (+formMode) < addMoneyflowJsonPreDefMoneyflows.length ) {  
            var predefmoneyflow = addMoneyflowJsonPreDefMoneyflows[formMode];

            document.addmoney.addmnfpredefmoneyflowid.value = predefmoneyflow["predefmoneyflowid"];
            document.addmoney.addmnfamount.value = parseFloat(predefmoneyflow["amount"]).toFixed(2);
            document.addmoney.addmnfmcp_contractpartnerid.value = predefmoneyflow["mcp_contractpartnerid"];
            document.addmoney.addmnfcomment.value = predefmoneyflow["comment"];
            document.addmoney.addmnfmpa_postingaccountid.value = predefmoneyflow["mpa_postingaccountid"];
            document.addmoney.addmnfmcs_capitalsourceid.value = predefmoneyflow["mcs_capitalsourceid"];

            favoriteOn = onFavorite;
            favoriteOff = offFavorite;

            clearErrorDiv("addmoneyErrors");
            $(function() {
              $('#favorite').prop('checked', false).change();
            })
          }

          $('form[name=addmoney]').validator('reset');
          $('form[name=addmoney]').validator('update');

          $(function() {
            $('#favorite').bootstrapToggle({
              on: favoriteOn,
              off: favoriteOff
            });
          })
        }

        fillSelectMoneyflow(currency, addMoneyflowJsonPreDefMoneyflows);
        preFillFormAddMoneyflow(FORM_MODE_DEFAULT);
        $('form[name=addmoney]').validator();
      </script>
{$FOOTER}

