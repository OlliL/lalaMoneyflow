{$HEADER}
      <div class="container">

        <div>
            <select class="form-control" id="selectmoneyflow" onchange="preFillForm(this.value)">
              <option value="-1">Neue Buchung</option>
            </select>
        </div>

        <div>&nbsp;</div>

        <form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
          <input type="hidden" name="action"                          value="add_moneyflow">
          <input type="hidden" name="realaction"                      value="save">
          <input type="hidden" name="all_data[predefmoneyflowid]" value="-1"               id="predefmoneyflowid" >
          <input type="hidden" name="all_data[checked]"           value="1">

          <div class="span2 well">

{section name=ERROR loop=$ERRORS}
            <div class="alert alert-danger" id="errors">
              {$ERRORS[ERROR]}
            </div>
{/section}

            <div class="form-group has-float-label">
              <div class='input-group date col-xs-12' id="bookingdate">
                <input type="text" class="form-control" name="all_data[bookingdate]" id="bookingdateSelect" required data-error="{#TEXT_305#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="bookingdateSelect">{#TEXT_16#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                  $(function () {
                      $('#bookingdate').datetimepicker({
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
              <div class='input-group date col-xs-12' id="invoicedate">
                <input type="text" class="form-control" name="all_data[invoicedate]" id="invoicedateSelect">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
              <label for="invoicedateSelect">{#TEXT_17#}</label>
              <div class="help-block with-errors"></div>
              <script type="text/javascript">
                  $(function () {
                      $('#invoicedate').datetimepicker({
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
                <input type="number" step="0.01" class="form-control" id="amount" name="all_data[amount]" required data-error="{#TEXT_306#}">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-euro"></span>
                </span>
              </div>
              <label for="amount">{#TEXT_18#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mcp_contractpartnerid]" id="mcp_contractpartnerid" onchange="setContractpartnerDefaults()" required data-error="{#TEXT_307#}">
                  <option value="">&nbsp;</option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
                  <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn" onClick="toggleOverlayContractpartner()">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
              <label for="mcp_contractpartnerid">{#TEXT_2#}</label>
              <div class="help-block with-errors"></div>
            </div>


            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control" id="comment" name="all_data[comment]" required data-error="{#TEXT_308#}">
              </div>
              <label for="comment">{#TEXT_21#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mpa_postingaccountid]" id="mpa_postingaccountid" required data-error="{#TEXT_309#}">
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
              <label for="mpa_postingaccountid">{#TEXT_232#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group has-float-label">
              <div class="input-group col-xs-12">
                <select class="form-control" name="all_data[mcs_capitalsourceid]" id="mcs_capitalsourceid" required data-error="{#TEXT_310#}">
{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
                  <option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
{/section}
                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
              <label for="mcs_capitalsourceid">{#TEXT_19#}</label>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group input-group col-lg-12">
                  <input id="private"  data-toggle="toggle" value="1" data-on="{#TEXT_209#}" data-off="{#TEXT_301#}" data-onstyle="danger" data-offstyle="success" type="checkbox" name="all_data[private]">
                  <input id="favorite" value="1" type="checkbox" name="all_data[save_as_predefmoneyflow]">
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn btn-default" onclick="preFillForm(-1)">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
      
      <div class="overlay overlay_addmoney_contractpartner" style="display: none">
        <div class="embedded embedded_contractpartner ">
{$EMBEDDED_ADD_CONTRACTPARTNER}
        </div>
      </div>

      <script>

        var jsonPreDefMoneyflows = {$JSON_PREDEFMONEYFLOWS};
        var jsonContractpartner = {$JSON_CONTRACTPARTNER};
        var jsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var currency = "{#CURRENCY#}";
        var today = "{$TODAY}";
        var onEmpty = "{#TEXT_302#}";
        var offEmpty = "{#TEXT_303#}";
        var onFavorite = "{#TEXT_311#}";
        var offFavorite = "{#TEXT_312#}";

        /* When the page is loaded, the booking form is set to the defaults which might be previous entered data or empty (if the page is initially loaded) */
        var BOOKING_DEFAULT = -2;
        /* This is used when in the select box "New booking" is selected explicitly to always null the form */
        var BOOKING_EMPTY = -1;


        function toggleOverlayContractpartner() {
          $('.overlay_addmoney_contractpartner').toggle();
        }

        function setContractpartnerDefaults() {
          var length = jsonContractpartner.length;
          var selectedValue = document.addmoney.mcp_contractpartnerid;
          
          for (i=0 ; i<length ; i++) {
            if (jsonContractpartner[i]["contractpartnerid"] == document.addmoney.mcp_contractpartnerid.value) {
              document.addmoney.comment.value = jsonContractpartner[i]["moneyflow_comment"];
              document.addmoney.mpa_postingaccountid.value = jsonContractpartner[i]["mpa_postingaccountid"];
              break;
            }
          }
        }

        function fillSelectMoneyflow(currency, jsonPreDefMoneyflows) {
          var jsonPredefmoneyflowsSize = jsonPreDefMoneyflows.length;

          var select = document.getElementById('selectmoneyflow');

          for (var i = 0; i < jsonPredefmoneyflowsSize; i++){
            var preDefMoneyflow = jsonPreDefMoneyflows[i];

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

        function deleteErrors() {
          var element = document.getElementById("errors");
          while ( element != null ) {
            element.outerHTML = "";
            delete element;
            element = document.getElementById("errors");
          }

          $(function() {
            $('#favorite').bootstrapToggle('destroy');
          })
        }

        function preFillForm(jsonPreDefMoneyflowIndex) {

          var favoriteOn = onEmpty;
          var favoriteOff = offEmpty;

          if ( jsonPreDefMoneyflowIndex == BOOKING_DEFAULT || jsonPreDefMoneyflowIndex == BOOKING_EMPTY ) {
            document.addmoney.predefmoneyflowid.value = -1;
            document.addmoney.bookingdateSelect.value = today;
            document.addmoney.invoicedateSelect.value = "";      
            document.addmoney.amount.value = "";
            document.addmoney.mcp_contractpartnerid.value = "";
            document.addmoney.comment.value = "";
            document.addmoney.mpa_postingaccountid.value = "";
            document.addmoney.mcs_capitalsourceid.selectedIndex = 0;

            if( jsonPreDefMoneyflowIndex == BOOKING_EMPTY) {
              deleteErrors();

              var select = document.getElementById('selectmoneyflow');
              select.selectedIndex = 0;
            } else {
              if ( "predefmoneyflowid" in jsonFormDefaults ) {
                document.addmoney.predefmoneyflowid.value = jsonFormDefaults["predefmoneyflowid"];
                if(jsonFormDefaults["predefmoneyflowid"] >= 0) {
                  favoriteOn = onFavorite;
                  favoriteOff = offFavorite;
                }
              }

              if ( "bookingdate" in jsonFormDefaults ) {
                document.addmoney.bookingdateSelect.value = jsonFormDefaults["bookingdate"];
              }

              if ( "invoicedate" in jsonFormDefaults ) {
                document.addmoney.invoicedateSelect.value = jsonFormDefaults["invoicedate"];
              }

              if ( "amount" in jsonFormDefaults ) {
                document.addmoney.amount.value = jsonFormDefaults["amount"];
              }

              if ( "mcp_contractpartnerid" in jsonFormDefaults ) {
                document.addmoney.mcp_contractpartnerid.value = jsonFormDefaults["mcp_contractpartnerid"];
              }

              if ( "comment" in jsonFormDefaults ) {
                document.addmoney.comment.value = jsonFormDefaults["comment"];
              }

              if ( "mpa_postingaccountid" in jsonFormDefaults ) {
                document.addmoney.mpa_postingaccountid.value = jsonFormDefaults["mpa_postingaccountid"];
              }

              if ( "mcs_capitalsourceid" in jsonFormDefaults ) {
                document.addmoney.mcs_capitalsourceid.value = jsonFormDefaults["mcs_capitalsourceid"];
              }

              if ( "save_as_predefmoneyflow" in jsonFormDefaults ) {
                if ( jsonFormDefaults["save_as_predefmoneyflow"] == "1" ) {
                  $(function() {
                    $('#favorite').prop('checked', true).change();
                  })
                }
              }

              if ( "private" in jsonFormDefaults ) {
                if ( jsonFormDefaults["private"] == "1" ) {
                  $(function() {
                    $('#private').prop('checked', true).change();
                  })
                }
              }

            }
          } else if  ( (+jsonPreDefMoneyflowIndex) >= 0 && (+jsonPreDefMoneyflowIndex) < jsonPreDefMoneyflows.length ) {  
            var predefmoneyflow = jsonPreDefMoneyflows[jsonPreDefMoneyflowIndex];

            document.addmoney.predefmoneyflowid.value = predefmoneyflow["predefmoneyflowid"];
            document.addmoney.amount.value = parseFloat(predefmoneyflow["amount"]).toFixed(2);
            document.addmoney.mcp_contractpartnerid.value = predefmoneyflow["mcp_contractpartnerid"];
            document.addmoney.comment.value = predefmoneyflow["comment"];
            document.addmoney.mpa_postingaccountid.value = predefmoneyflow["mpa_postingaccountid"];
            document.addmoney.mcs_capitalsourceid.value = predefmoneyflow["mcs_capitalsourceid"];

            favoriteOn = onFavorite;
            favoriteOff = offFavorite;

            $(function() {
              $('#favorite').prop('checked', false).change();
            })

            deleteErrors();
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

        $('form[name=addmoney]').validator();

        fillSelectMoneyflow(currency, jsonPreDefMoneyflows);
        preFillForm(BOOKING_DEFAULT);
         
        document.addmoney.amount.focus();

      </script>
{$FOOTER}

