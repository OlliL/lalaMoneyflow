{$HEADER}
      <div class="container container-wide">
        <div class="row">
    	  <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3 col-xs-12">
              <select class="form-control" id="selectmoneyflow" onchange="preFillFormAddMoneyflow(this.value)">
                <option value="-1">Neue Buchung</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="addmoneyflow" id="addmnfform">
          <input type="hidden" name="action"                          value="add_moneyflow_submit">
          <input type="hidden" name="realaction"                      value="save">
          <input type="hidden" name="all_data[predefmoneyflowid]"     value="-1"               id="addmnfpredefmoneyflowid" >

          <div class="span2 well">

            <div id="addMoneyflowErrorsGoHere">
            </div>

     <div class="row">
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="addmnfbookingdateDiv">
                  <input type="text" class="form-control" name="all_data[bookingdate]" id="addmnfbookingdate" required data-error="{#TEXT_305#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="addmnfbookingdate">{#TEXT_16#}</label>
              </span>
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

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="addmnfinvoicedateDiv">
                  <input type="text" class="form-control" name="all_data[invoicedate]" id="addmnfinvoicedate">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="addmnfinvoicedate">{#TEXT_17#}</label>
              </span>
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

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcp_contractpartnerid]" id="addmnfmcp_contractpartnerid" onchange="setContractpartnerDefaults()" required data-error="{#TEXT_307#}">
                    <option value="">&nbsp;</option>
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
                    <option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
{/section}
                  </select>
                  <span class="input-group-btn">
<!--                    <button type="button" class="btn" onclick="showOverlayContractpartner()"> -->
                    <button type="button" class="btn" data-toggle="modal" data-target="#contractpartnerModal">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </span>
                </div>
                <label for="addmnfmcp_contractpartnerid">{#TEXT_2#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
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
              </span>
              <div class="help-block with-errors"></div>
            </div>

    </div>
    <div class="row">

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[amount]" required data-error="{#TEXT_306#}" autofocus onChange="calculateRemainingAmount()">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="addmnfamount">{#TEXT_18#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <div id="addmnfcommentDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="addmnfcomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
                </div>
                <label for="addmnfcomment">{#TEXT_21#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <div id="addmnfmpa_postingaccountidDiv">
              <span class="has-float-label">
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
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="col-md-3 col-xs-12">
                  <input id="private"  data-toggle="toggle" value="1" data-on="{#TEXT_209#}" data-off="{#TEXT_301#}" data-onstyle="danger" data-offstyle="success" type="checkbox" name="all_data[private]">
                  <input id="favorite" value="1" type="checkbox" name="all_data[save_as_predefmoneyflow]">
            </div>
    </div>

            <div class="panel-group hide-on-small">
              <div class="panel panel-default">
              
                <div class="panel-heading">
                  <a data-toggle="collapse" href="#addmnfsplitentries">Unterbuchungen</a>
                </div>
                
                <div id="addmnfsplitentries" class="panel-collapse collapse panel-footer">
                </div>

              </div>
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

{literal}    
<script id="template" type="x-tmpl-mustache">
          <div class="row" id="addmnfsub{{splitEntryIndex}}">
            <div class="form-group has-float-label col-md-1 col-xs-12">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="removeSplitEntryLine({{splitEntryIndex}})">
                    <span class="glyphicon glyphicon-minus"></span>
                  </button>
                  <button type="button" class="btn" onclick="addSplitEntryLine()" id="addmnfmoresplitentries{{splitEntryIndex}}">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
            </div>
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.01" class="form-control" id="addmnfsubamount{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][amount]"  data-error="{{{amountError}}}" onChange="calculateRemainingAmount({{splitEntryIndex}});checkIfRequired({{splitEntryIndex}})">
                </div>
                <label for="addmnfsubamount{{splitEntryIndex}}">{{amountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="addmnfsubcomment{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][comment]"  data-error="{{{commentError}}}" onChange="checkIfRequired({{splitEntryIndex}})">
                </div>
                <label for="addmnfsubcomment{{splitEntryIndex}}">{{commentLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_subdata[{{splitEntryIndex}}][mpa_postingaccountid]" id="addmnfsubmpa_postingaccountid{{splitEntryIndex}}"  data-error="{{{postingAccountError}}}" onChange="checkIfRequired({{splitEntryIndex}})">
                    <option value="">&nbsp;</option>
{{#postingAccounts}}
                    <option value="{{postingaccountid}}">{{name}}</option>
{{/postingAccounts}}
                  </select>
                </div>
                <label for="addmnfsubmpa_postingaccountid{{splitEntryIndex}}">{{postingAccountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            
            <div class="form-group col-md-2 col-xs-12" id="addmnfremainingDiv{{splitEntryIndex}}">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="copyRemainer()">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                  </button>
                </span>
                <span class="has-float-label">
                  <input type="text" class="form-control" id="addmnfremaining{{splitEntryIndex}}" readonly pattern="0">
                  <label for="addmnfremaining{{splitEntryIndex}}">Rest</label>
                </span>                
              </div>
            </div>
          </div>
</script>
{/literal}

      <script>

        var addMoneyflowJsonPreDefMoneyflows = {$JSON_PREDEFMONEYFLOWS};
        var addMoneyflowJsonContractpartner = {$JSON_CONTRACTPARTNER};
        var addMoneyflowJsonPostingAccounts = {$JSON_POSTINGACCOUNTS};
        var addMoneyflowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var onEmpty = "{#TEXT_302#}";
        var offEmpty = "{#TEXT_303#}";
        var onFavorite = "{#TEXT_311#}";
        var offFavorite = "{#TEXT_312#}";

        var shownSplitEntryRows = [];
        var filledSplitEntryRows = [];
        var splitEntriesTemplateData = {
          splitEntryIndex: -1,
          amountError: "{#TEXT_306#}",
          amountLabel: "{#TEXT_18#}",
          commentError: "{#TEXT_308#}",
          commentLabel: "{#TEXT_21#}",
          postingAccountError: "{#TEXT_309#}",
          postingAccountLabel: "{#TEXT_232#}",
          postingAccounts: addMoneyflowJsonPostingAccounts
        };
        

{literal}
        function resetFormAddMoneyflow() {
          preFillFormAddMoneyflow(FORM_MODE_EMPTY);
          initSplitEntries();
        }

        function setContractpartnerDefaults() {
          var length = addMoneyflowJsonContractpartner.length;
          var selectedValue = document.addmoneyflow.addmnfmcp_contractpartnerid;
          
          for (i=0 ; i<length ; i++) {
            if (addMoneyflowJsonContractpartner[i]["contractpartnerid"] == document.addmoneyflow.addmnfmcp_contractpartnerid.value) {
              if ( addMoneyflowJsonContractpartner[i]["moneyflow_comment"] != null ) {
                document.addmoneyflow.addmnfcomment.value = addMoneyflowJsonContractpartner[i]["moneyflow_comment"];
              }
              if ( addMoneyflowJsonContractpartner[i]["mpa_postingaccountid"] != null ) {
                document.addmoneyflow.addmnfmpa_postingaccountid.value = addMoneyflowJsonContractpartner[i]["mpa_postingaccountid"];
              }
              break;
            }
          }
          $('#addmnfform').validator('reset');
          $('#addmnfform').validator('update');
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
            document.addmoneyflow.addmnfpredefmoneyflowid.value = -1;
            document.addmoneyflow.addmnfbookingdate.value = today;
            document.addmoneyflow.addmnfinvoicedate.value = "";      
            document.addmoneyflow.addmnfamount.value = "";
            document.addmoneyflow.addmnfmcp_contractpartnerid.value = "";
            document.addmoneyflow.addmnfcomment.value = "";
            document.addmoneyflow.addmnfmpa_postingaccountid.value = "";
            document.addmoneyflow.addmnfmcs_capitalsourceid.selectedIndex = 0;

            if( formMode == FORM_MODE_EMPTY) {
              clearErrorDiv("addMoneyflowErrors");
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
              
              if ( document.addmoneyflow.addmnfpredefmoneyflowid >= 0 ) {
                favoriteOn = onFavorite;
                favoriteOff = offFavorite;
              }
             }
          } else if  ( (+formMode) >= 0 && (+formMode) < addMoneyflowJsonPreDefMoneyflows.length ) {  
            var predefmoneyflow = addMoneyflowJsonPreDefMoneyflows[formMode];

            document.addmoneyflow.addmnfpredefmoneyflowid.value = predefmoneyflow["predefmoneyflowid"];
            document.addmoneyflow.addmnfamount.value = parseFloat(predefmoneyflow["amount"]).toFixed(2);
            document.addmoneyflow.addmnfmcp_contractpartnerid.value = predefmoneyflow["mcp_contractpartnerid"];
            document.addmoneyflow.addmnfcomment.value = predefmoneyflow["comment"];
            document.addmoneyflow.addmnfmpa_postingaccountid.value = predefmoneyflow["mpa_postingaccountid"];
            document.addmoneyflow.addmnfmcs_capitalsourceid.value = predefmoneyflow["mcs_capitalsourceid"];

            favoriteOn = onFavorite;
            favoriteOff = offFavorite;

            clearErrorDiv("addMoneyflowErrors");
            $(function() {
              $('#favorite').prop('checked', false).change();
            })
          }

          $('#addmnfform').validator('reset');
          $('#addmnfform').validator('update');

          $(function() {
            $('#favorite').bootstrapToggle({
              on: favoriteOn,
              off: favoriteOff
            });
          })
        }

	function toggleAddMoneyflowSplitEntries () {
	  var element = document.getElementById('addmnfToggleSplitEntries');
	  element.innerHTML = '<span class="glyphicon glyphicon-minus"></span>';
	}

        /************************************************************
         *
         * SPLIT ENTRY STUFF
         *
         ************************************************************/
         
        function checkIfRequired(row) {
          var elementAmount = $('#addmnfsubamount' + row);
          var elementComment = $('#addmnfsubcomment' + row);
          var elementPostingAccount = $('#addmnfsubmpa_postingaccountid' + row);
          
          var required = true;
          if(elementAmount.val().length == 0 && elementComment.val().length == 0 && elementPostingAccount.val().length == 0) {
            required = false;
          }

          var currentRequired = elementAmount.prop('required');
          if(currentRequired != required) {
            if(required) {
              filledSplitEntryRows.push(row);
            } else {
              var index = filledSplitEntryRows.indexOf(row);
              if(index > -1) {
                filledSplitEntryRows.splice(index,1);
              }
            }
            hideNonRelevantFieldsIfSplitEntries();
            elementAmount.prop('required',required).change();
            elementComment.prop('required',required).change();
            elementPostingAccount.prop('required',required).change();
 
            $('#addmnfform').validator('reset');
            $('#addmnfform').validator('update');
          }
        }

        function hideNonRelevantFieldsIfSplitEntries() {
          if(filledSplitEntryRows.length > 0) {
            $('#addmnfcommentDiv').hide();
            $('#addmnfmpa_postingaccountidDiv').hide();
          } else {
            $('#addmnfcommentDiv').show();
            $('#addmnfmpa_postingaccountidDiv').show();
          }
        }
        
        function calculateRemainingAmount(row) {
          var amount = $('#addmnfamount').val();
          var length = shownSplitEntryRows.length;

          // have we edited the last row? If yes add a new one automatically
          if( row == shownSplitEntryRows[shownSplitEntryRows.length - 1] ) {
            addSplitEntryLine();
            length = shownSplitEntryRows.length;
          }

          for(i=0 ; i < length ; i++ ) {
            var subamount = $('#addmnfsubamount' + shownSplitEntryRows[i]).val();
            if(subamount != null) {
              amount -= subamount;
            }

          }
          if(amount == 0) {
            $('#addmnfremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
          } else {
            $('#addmnfremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).show();
          }
                      
          $('#addmnfremaining' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).val(amount.toFixed(2));
        }
        
        function copyRemainer() {
          var lastSubElement = shownSplitEntryRows[shownSplitEntryRows.length - 1];
          var addmnfsubamount = $('#addmnfsubamount' + lastSubElement);
          var addmnfremaining = $('#addmnfremaining' + lastSubElement);

          addmnfsubamount.val(addmnfremaining.val());
          checkIfRequired(lastSubElement);
          calculateRemainingAmount();
        }
         
         
        function initSplitEntries() {
          $('#addmnfsplitentries').empty();
          shownSplitEntryRows = [];
          filledSplitEntryRows = [];
          splitEntriesTemplateData.splitEntryIndex = -1;
          
          addSplitEntryLine();
          addSplitEntryLine();
          hideNonRelevantFieldsIfSplitEntries();
        }

        /*
         * Adds a split entry line when a "+" was clicked.
         * Also moves the "+" button to the newly displayed row as well as the "remaining" box
         *
         */
        function addSplitEntryLine() {

          splitEntriesTemplateData.splitEntryIndex++;

          var template = $('#template').html();
          var rendered = Mustache.render(template,splitEntriesTemplateData);
          $('#addmnfsplitentries').append( rendered );

	  if( shownSplitEntryRows.length > 0 ) {
            $('#addmnfmoresplitentries' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
            $('#addmnfremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
            
          }
          
          shownSplitEntryRows.push(splitEntriesTemplateData.splitEntryIndex);

          $('#addmnfform').validator('reset');
          $('#addmnfform').validator('update');

          calculateRemainingAmount();
        }
        
        /*
         * Removes the split entry line where the "-" was clicked.
         * If the number of displayed rows would then be lower than two, a new empty row is added afterwards
         *
         */
        function removeSplitEntryLine(splitEntryIndex) {

          $('#addmnfsub' + splitEntryIndex).remove();

          // maintain state-arrays
          var index = shownSplitEntryRows.indexOf(splitEntryIndex);
          if(index > -1) {
            shownSplitEntryRows.splice(index,1);
          }
          index = filledSplitEntryRows.indexOf(splitEntryIndex);
          if(index > -1) {
            filledSplitEntryRows.splice(index,1);
          }
          

          // make sure more than two entry cannot be deleted
          // we always show at least two rows!
          if( shownSplitEntryRows.length < 2) {
            addSplitEntryLine();
          }
          
          // show the "+" button and the "remaining" information on the last displayed row
          $('#addmnfmoresplitentries' + shownSplitEntryRows[shownSplitEntryRows.length - 1]).show();
          $('#addmnfremainingDiv' + shownSplitEntryRows[shownSplitEntryRows.length - 1]).show();

          // recalculate the "remaining" amount
          calculateRemainingAmount();

          // show comment and postingAccount of the main booking again if all rows where deleted
          hideNonRelevantFieldsIfSplitEntries(); 
        }
        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function copyIfSplitEntries() {
          if(filledSplitEntryRows.length > 0) {
            filledSplitEntryRows.sort(function(a, b){return a-b});
            var elementSplitEntryComment = $('#addmnfsubcomment' + filledSplitEntryRows[0]);
            var elementSplitEntryPostingAccount = $('#addmnfsubmpa_postingaccountid' + filledSplitEntryRows[0]);
            $('#addmnfcomment').val(elementSplitEntryComment.val());
            $('#addmnfmpa_postingaccountid').val(elementSplitEntryPostingAccount.val());
          }
          return true;
        }
        function ajaxAddMoneyflowSuccess(data) {
          resetFormAddMoneyflow();
        }

        function ajaxAddMoneyflowError(data) {
          clearErrorDiv('addMoneyflowErrors');
          populateErrorDiv(data.responseText,'addMoneyflowErrorsGoHere','addMoneyflowErrors');
        }

        fillSelectMoneyflow(currency, addMoneyflowJsonPreDefMoneyflows);
        preFillFormAddMoneyflow(FORM_MODE_DEFAULT);
        $('#addmnfform').validator();
        $('#addmnfform').ajaxForm({
            beforeSubmit: function() {
              return copyIfSplitEntries();
            },
            dataType: 'json',
            success: ajaxAddMoneyflowSuccess,
            error: ajaxAddMoneyflowError
        });
        
        initSplitEntries();

{/literal}
      </script>
{$FOOTER}

