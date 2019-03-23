{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_268#}</h4>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>

        <div class="well">
        <div class="row">
        <form action="{$ENV_INDEX_PHP}" method="POST" name="addimportedmoneyflow" id="addmimform">
          <input type="hidden" name="action"                          value="edit_moneyflow_submit">
          <input type="hidden" name="moneyflowid"                     value="{$MONEYFLOWID}">
          <input type="hidden" name="all_data[predefmoneyflowid]"     value="-1"               id="addmimpredefmoneyflowid" >
          <input type="hidden" name="all_data[existing_split_entry_ids]"  value=""               id="addmimexisting_split_entry_ids" >

          <div class="span2 well">
           <div class="row">
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_32#}</div>
             <div class="col-md-3" id="addmimaccountnumber"></div>
             <div class="col-md-1 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_33#}</div>
             <div class="col-md-2" id="addmimbankcode"></div>
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_269#}</div>
             <div class="col-md-2" id="addmimname"></div>
           </div>
           <div class="row">
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_270#}</div>
             <div class="col-md-10" id="addmimusage"></div>
          </div>
          </div>
          <div class="span2 well">

            <div id="addImportedMoneyflowErrorsGoHere">
            </div>

     <div class="row">
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="addmimbookingdateDiv">
                  <input type="text" class="form-control" name="all_data[bookingdate]" id="addmimbookingdate" required data-error="{#TEXT_305#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="addmimbookingdate">{#TEXT_16#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#addmimbookingdateDiv').datetimepicker({
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
                <div class='input-group date col-xs-12' id="addmiminvoicedateDiv">
                  <input type="text" class="form-control" name="all_data[invoicedate]" id="addmiminvoicedate">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="addmiminvoicedate">{#TEXT_17#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#addmiminvoicedateDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true, 
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcp_contractpartnerid]" id="addmimmcp_contractpartnerid" onchange="setContractpartnerDefaults()" required data-error="{#TEXT_307#}">
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
                <label for="addmimmcp_contractpartnerid">{#TEXT_2#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcs_capitalsourceid]" id="addmimmcs_capitalsourceid" required data-error="{#TEXT_310#}">
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
                <label for="addmimmcs_capitalsourceid">{#TEXT_19#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

    </div>
    <div class="row">

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.01" class="form-control" id="addmimamount" name="all_data[amount]" required data-error="{#TEXT_306#}" autofocus onChange="calculateRemainingAmount()">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="addmimamount">{#TEXT_18#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <div id="addmimcommentDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="addmimcomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
                </div>
                <label for="addmimcomment">{#TEXT_21#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <div id="addmimmpa_postingaccountidDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mpa_postingaccountid]" id="addmimmpa_postingaccountid" required data-error="{#TEXT_309#}">
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
                <label for="addmimmpa_postingaccountid">{#TEXT_232#}</label>
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
                  <a data-toggle="collapse" href="#addmimsplitentries">Unterbuchungen</a>
                </div>
                
                <div id="addmimsplitentries" class="panel-collapse collapse panel-footer">
                </div>

              </div>
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="button" class="btn btn-default" onclick="resetFormAddImportedMoneyflow()">{#TEXT_304#}</button>
              <button type="submit" class="btn btn-primary"                          >{#TEXT_22#}</button>
            </div>  
          </div>  

        </form>
      </div>
    </div>
    </div>

{literal}    
<script id="template" type="x-tmpl-mustache">
          <div class="row" id="addmimsub{{splitEntryIndex}}">
            <div class="form-group has-float-label col-md-1 col-xs-12">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="removeSplitEntryLine({{splitEntryIndex}})">
                    <span class="glyphicon glyphicon-minus"></span>
                  </button>
                  <button type="button" class="btn" onclick="addSplitEntryLine()" id="addmimmoresplitentries{{splitEntryIndex}}">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
            </div>
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="hidden" id="addmimsubmoneyflowsplitentryid{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][moneyflowsplitentryid]" value="-1">
                  <input type="number" step="0.01" class="form-control" id="addmimsubamount{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][amount]"  data-error="{{{amountError}}}" onChange="calculateRemainingAmount({{splitEntryIndex}});checkIfRequired({{splitEntryIndex}})">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="addmimsubamount{{splitEntryIndex}}">{{amountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="addmimsubcomment{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][comment]"  data-error="{{{commentError}}}" onChange="checkIfRequired({{splitEntryIndex}})">
                </div>
                <label for="addmimsubcomment{{splitEntryIndex}}">{{commentLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_subdata[{{splitEntryIndex}}][mpa_postingaccountid]" id="addmimsubmpa_postingaccountid{{splitEntryIndex}}"  data-error="{{{postingAccountError}}}" onChange="checkIfRequired({{splitEntryIndex}})">
                    <option value="">&nbsp;</option>
{{#postingAccounts}}
                    <option value="{{postingaccountid}}">{{name}}</option>
{{/postingAccounts}}
                  </select>
                </div>
                <label for="addmimsubmpa_postingaccountid{{splitEntryIndex}}">{{postingAccountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            
            <div class="form-group col-md-2 col-xs-12" id="addmimremainingDiv{{splitEntryIndex}}">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="copyRemainer()">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                  </button>
                </span>
                <span class="has-float-label">
                  <input type="text" class="form-control" id="addmimremaining{{splitEntryIndex}}" readonly pattern="0">
                  <label for="addmimremaining{{splitEntryIndex}}">Rest</label>
                </span>                
              </div>
            </div>
          </div>
</script>
{/literal}

      <script>

        var addImportedMoneyflowJsonContractpartner = {$JSON_CONTRACTPARTNER};
        var addImportedMoneyflowJsonPostingAccounts = {$JSON_POSTINGACCOUNTS};
        var addImportedMoneyflowJsonFormDefaults = {$JSON_FORM_DEFAULTS};
        var onEmpty = "{#TEXT_302#}";
        var offEmpty = "{#TEXT_303#}";

        var previousCommentSetByContractpartnerDefaults = "";
        var previousPostingAccountSetByContractpartnerDefaults = "";

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
          postingAccounts: addImportedMoneyflowJsonPostingAccounts
        };
        

{literal}
        function resetFormAddImportedMoneyflow() {
          preFillFormAddImportedMoneyflow();
          initSplitEntries();

          previousCommentSetByContractpartnerDefaults = "";
          previousPostingAccountSetByContractpartnerDefaults = "";
        }

        /**
         * When changing a contractpartner in the drop down box, fill out his defaults for
         * comment and postingaccount - but only if the user didn't modified the fields
         * on his own in the meantime
         */
        function setContractpartnerDefaults() {
          var length = addImportedMoneyflowJsonContractpartner.length;
          var selectedValue = document.addimportedmoneyflow.addmimmcp_contractpartnerid;

          var addmimcomment = $('#addmimcomment');
          var addmimmpa_postingaccountid = $('#addmimmpa_postingaccountid');
          var addmimmcp_contractpartnerid_val = $('#addmimmcp_contractpartnerid').val();
          
          var updateComment = false;
          var updatePostingAccount = false;

          if( addmimcomment.val() == previousCommentSetByContractpartnerDefaults )
            updateComment = true;

          if( addmimmpa_postingaccountid.val() == previousPostingAccountSetByContractpartnerDefaults || ( ! addmimmpa_postingaccountid.val() && ! previousPostingAccountSetByContractpartnerDefaults ))
            updatePostingAccount = true;
            
          if( updateComment || updatePostingAccount ) {
            for (i=0 ; i<length ; i++) {
              if (addImportedMoneyflowJsonContractpartner[i]["contractpartnerid"] == addmimmcp_contractpartnerid_val) {
                if ( updateComment ) {
                  addmimcomment.val(addImportedMoneyflowJsonContractpartner[i]["moneyflow_comment"]);
                  previousCommentSetByContractpartnerDefaults = addImportedMoneyflowJsonContractpartner[i]["moneyflow_comment"];
                }
                if ( updatePostingAccount ) {
                  addmimmpa_postingaccountid.val(addImportedMoneyflowJsonContractpartner[i]["mpa_postingaccountid"]);
                  previousPostingAccountSetByContractpartnerDefaults = addImportedMoneyflowJsonContractpartner[i]["mpa_postingaccountid"];
                }
                break;
              }
            }
          }
          $('#addmimform').validator('reset');
          $('#addmimform').validator('update');
        }

        function preFillFormAddImportedMoneyflow() {

          $(function() {
            $('#favorite').bootstrapToggle('destroy');
          })

          document.addimportedmoneyflow.addmimpredefmoneyflowid.value = -1;
          document.addimportedmoneyflow.addmimbookingdate.value = today;
          document.addimportedmoneyflow.addmiminvoicedate.value = "";      
          document.addimportedmoneyflow.addmimamount.value = "";
          document.addimportedmoneyflow.addmimmcp_contractpartnerid.value = "";
          document.addimportedmoneyflow.addmimcomment.value = "";
          document.addimportedmoneyflow.addmimmpa_postingaccountid.value = "";
          document.addimportedmoneyflow.addmimmcs_capitalsourceid.selectedIndex = 0;

          for ( var key in addImportedMoneyflowJsonFormDefaults ) {
            var element = document.getElementById( 'addmim'+key );

            if ( element !== null ) {
              if ( key == "save_as_predefmoneyflow") {
                if ( addImportedMoneyflowJsonFormDefaults["save_as_predefmoneyflow"] == "1" ) {
                  $(function() {
                    $('#favorite').prop('checked', true).change();
                  })
                }
              } else if ( key == "private") {
                if ( addImportedMoneyflowJsonFormDefaults["private"] == "1" ) {
                  $(function() {
                    $('#private').prop('checked', true).change();
                  })
                }
              } else {
              console.log(element.tagName);
                if ( element.tagName == 'DIV' ) {
                  element.innerHTML = addImportedMoneyflowJsonFormDefaults[key];
                  console.log(addImportedMoneyflowJsonFormDefaults[key]);
                } else {
                  element.value = addImportedMoneyflowJsonFormDefaults[key];
                }
              }
            }
          }

          $('#addmimform').validator('reset');
          $('#addmimform').validator('update');

          $(function() {
            $('#favorite').bootstrapToggle({
              on: onEmpty,
              off: offEmpty
            });
          })
        }

	function toggleAddImportedMoneyflowSplitEntries () {
	  var element = document.getElementById('addmimToggleSplitEntries');
	  element.innerHTML = '<span class="glyphicon glyphicon-minus"></span>';
	}

        /************************************************************
         *
         * SPLIT ENTRY STUFF
         *
         ************************************************************/
         
        function checkIfRequired(row) {
          var elementAmount = $('#addmimsubamount' + row);
          var elementComment = $('#addmimsubcomment' + row);
          var elementPostingAccount = $('#addmimsubmpa_postingaccountid' + row);
          
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
 
            $('#addmimform').validator('reset');
            $('#addmimform').validator('update');
          }
        }

        function hideNonRelevantFieldsIfSplitEntries() {
          if(filledSplitEntryRows.length > 0) {
            if($('#addmimcommentDiv').is(':visible')) {
              $('#addmimcommentDiv').hide();
              $('#addmimcomment').prop('required',false).change();
              $('#addmimmpa_postingaccountidDiv').hide();
              $('#addmimmpa_postingaccountid').prop('required',false).change();
            }
          } else {
            if($('#addmimcommentDiv').is(':hidden')) {
              $('#addmimcommentDiv').show();
              $('#addmimcomment').prop('required',true).change();
              $('#addmimmpa_postingaccountidDiv').show();
              $('#addmimmpa_postingaccountid').prop('required',true).change();
            }
          }
        }
        
        function calculateRemainingAmount(row) {
          var amount = $('#addmimamount').val();
          var length = shownSplitEntryRows.length;

          // have we edited the last row? If yes add a new one automatically
          if( row == shownSplitEntryRows[shownSplitEntryRows.length - 1] ) {
            addSplitEntryLine();
            length = shownSplitEntryRows.length;
          }

          for(i=0 ; i < length ; i++ ) {
            var subamount = $('#addmimsubamount' + shownSplitEntryRows[i]).val();
            if(subamount != null) {
              amount -= subamount;
            }

          }
          if(amount == 0) {
            $('#addmimremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
          } else {
            $('#addmimremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).show();
          }
                      
          $('#addmimremaining' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).val(amount.toFixed(2));
        }
        
        function copyRemainer() {
          var lastSubElement = shownSplitEntryRows[shownSplitEntryRows.length - 1];
          var addmimsubamount = $('#addmimsubamount' + lastSubElement);
          var addmimremaining = $('#addmimremaining' + lastSubElement);

          var addmimcomment = $('#addmimcomment');
          var addmimmpa_postingaccountid = $('#addmimmpa_postingaccountid');
          var addmimsubcomment = $('#addmimsubcomment' + lastSubElement);
          var addmimsubmpa_postingaccountid = $('#addmimsubmpa_postingaccountid' + lastSubElement);

          addmimsubcomment.val(addmimcomment.val());
          addmimsubmpa_postingaccountid.val(addmimmpa_postingaccountid.val());
          addmimsubamount.val(addmimremaining.val());
          checkIfRequired(lastSubElement);
          calculateRemainingAmount();
        }
         
         
        function initSplitEntries() {
          $('#addmimsplitentries').empty();
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
          $('#addmimsplitentries').append( rendered );

	  if( shownSplitEntryRows.length > 0 ) {
            $('#addmimmoresplitentries' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
            $('#addmimremainingDiv' + (shownSplitEntryRows[shownSplitEntryRows.length - 1])).hide();
            
          }
          
          shownSplitEntryRows.push(splitEntriesTemplateData.splitEntryIndex);

          $('#addmimform').validator('reset');
          $('#addmimform').validator('update');

          calculateRemainingAmount();
        }
        
        /*
         * Removes the split entry line where the "-" was clicked.
         * If the number of displayed rows would then be lower than two, a new empty row is added afterwards
         *
         */
        function removeSplitEntryLine(splitEntryIndex) {

          $('#addmimsub' + splitEntryIndex).remove();

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
          $('#addmimmoresplitentries' + shownSplitEntryRows[shownSplitEntryRows.length - 1]).show();
          $('#addmimremainingDiv' + shownSplitEntryRows[shownSplitEntryRows.length - 1]).show();

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
            var elementSplitEntryComment = $('#addmimsubcomment' + filledSplitEntryRows[0]);
            var elementSplitEntryPostingAccount = $('#addmimsubmpa_postingaccountid' + filledSplitEntryRows[0]);
            $('#addmimcomment').val(elementSplitEntryComment.val());
            $('#addmimmpa_postingaccountid').val(elementSplitEntryPostingAccount.val());
          }
          return true;
        }

        function ajaxAddImportedMoneyflowSuccess(data) {
          // TODO: remove from HTML
          resetFormAddImportedMoneyflow();
        }

        function ajaxAddImportedMoneyflowError(data) {
          clearErrorDiv('addImportedMoneyflowErrors');
          populateErrorDiv(data.responseText,'addImportedMoneyflowErrorsGoHere','addImportedMoneyflowErrors');
        }

        initSplitEntries();
        preFillFormAddImportedMoneyflow();
        setContractpartnerDefaults();

        $('#addmimform').validator();
        $('#addmimform').ajaxForm({
            beforeSubmit: function() {
              return copyIfSplitEntries();
            },
            dataType: 'json',
            success: ajaxAddImportedMoneyflowSuccess,
            error: ajaxAddImportedMoneyflowError
        });
        

{/literal}
      </script>
{$FOOTER}

