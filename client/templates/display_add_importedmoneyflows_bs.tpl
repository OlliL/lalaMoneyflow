{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_268#}</h4>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
{if $NUM_MONEYFLOWS eq 0}
<br>
<p align="center">{#TEXT_285#}</p>
{/if}

{for $NUM=1 to $NUM_MONEYFLOWS}
        <div class="well" id="{$NUM}addmimentry">
        <div class="row">
        <form action="{$ENV_INDEX_PHP}" method="POST" name="addimportedmoneyflow" id="{$NUM}addmimform">
          <input type="hidden" name="action"                          value="add_importedmoneyflow_submit">
          <input type="hidden" name="num"                             value="{$NUM}">
          <input type="hidden" name="all_data[delete]"                value="0"  id="{$NUM}addmimdelete">
          <input type="hidden" name="all_data[importedmoneyflowid]"   value="-1" id="{$NUM}addmimimportedmoneyflowid">
          <input type="hidden" name="all_data[accountnumber]"         id="{$NUM}addmimaccountnumber">
          <input type="hidden" name="all_data[bankcode]"              id="{$NUM}addmimbankcode">
          <input type="hidden" name="all_data[name]"                  id="{$NUM}addmimname">
          <input type="hidden" name="all_data[usage]"                 id="{$NUM}addmimusage">

          <div class="span2 well">
           <div class="row">
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_32#}</div>
             <div class="col-md-3" id="{$NUM}addmimaccountnumber_div"></div>
             <div class="col-md-1 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_33#}</div>
             <div class="col-md-2" id="{$NUM}addmimbankcode_div"></div>
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_269#}</div>
             <div class="col-md-2" id="{$NUM}addmimname_div"></div>
           </div>
           <div class="row">
             <div class="col-md-2 col-xs-3" style="font-weight:700;font-size:10.5px">{#TEXT_270#}</div>
             <div class="col-md-10" id="{$NUM}addmimusage_div"></div>
          </div>
          </div>
          <div class="span2 well">

            <div id="{$NUM}addImportedMoneyflowErrorsGoHere">
            </div>

     <div class="row">
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="{$NUM}addmimbookingdateDiv">
                  <input type="text" class="form-control" name="all_data[bookingdate]" id="{$NUM}addmimbookingdate" required data-error="{#TEXT_305#}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="{$NUM}addmimbookingdate">{#TEXT_16#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#{$NUM}addmimbookingdateDiv').datetimepicker({
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
                <div class='input-group date col-xs-12' id="{$NUM}addmiminvoicedateDiv">
                  <input type="text" class="form-control" name="all_data[invoicedate]" id="{$NUM}addmiminvoicedate">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="{$NUM}addmiminvoicedate">{#TEXT_17#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#{$NUM}addmiminvoicedateDiv').datetimepicker({
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
                  <select class="form-control" name="all_data[mcp_contractpartnerid]" id="{$NUM}addmimmcp_contractpartnerid" onchange="setContractpartnerDefaults({$NUM})" required data-error="{#TEXT_307#}">
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
                <label for="{$NUM}addmimmcp_contractpartnerid">{#TEXT_2#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mcs_capitalsourceid]" id="{$NUM}addmimmcs_capitalsourceid" required data-error="{#TEXT_310#}">
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
                <label for="{$NUM}addmimmcs_capitalsourceid">{#TEXT_19#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

    </div>
    <div class="row">

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.01" class="form-control" id="{$NUM}addmimamount" name="all_data[amount]" required data-error="{#TEXT_306#}" autofocus onChange="calculateRemainingAmount({$NUM})">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="{$NUM}addmimamount">{#TEXT_18#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <div id="{$NUM}addmimcommentDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="{$NUM}addmimcomment" name="all_data[comment]" required data-error="{#TEXT_308#}">
                </div>
                <label for="{$NUM}addmimcomment">{#TEXT_21#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <div id="{$NUM}addmimmpa_postingaccountidDiv">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[mpa_postingaccountid]" id="{$NUM}addmimmpa_postingaccountid" required data-error="{#TEXT_309#}">
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
                <label for="{$NUM}addmimmpa_postingaccountid">{#TEXT_232#}</label>
              </span>
              <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="col-md-3 col-xs-12">
                  <input id="{$NUM}private"  data-toggle="toggle" value="1" data-on="{#TEXT_209#}" data-off="{#TEXT_301#}" data-onstyle="danger" data-offstyle="success" type="checkbox" name="all_data[private]">
                  <input id="{$NUM}favorite" value="1" type="checkbox" name="all_data[save_as_predefmoneyflow]">
            </div>
    </div>

            <div class="panel-group hide-on-small">
              <div class="panel panel-default">
              
                <div class="panel-heading">
                  <a data-toggle="collapse" href="#{$NUM}addmimsplitentries">Unterbuchungen</a>
                </div>
                
                <div id="{$NUM}addmimsplitentries" class="panel-collapse collapse panel-footer">
                </div>

              </div>
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="submit" class="btn btn-primary"                          >{#TEXT_271#}</button>
              <button type="button" class="btn btn-danger" onclick="deleteImportedMoneyflow({$NUM})">{#TEXT_37#}</button>
            </div>  
          </div>  

        </form>
      </div>
    </div>
{/for}

    </div>

{literal}    
<!-- TODO hier noch $NUM beruecksichtigen! Als Templatevariable -->
<script id="template" type="x-tmpl-mustache">
          <div class="row" id="{{num}}addmimsub{{splitEntryIndex}}">
            <div class="form-group has-float-label col-md-1 col-xs-12">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="removeSplitEntryLine({{num}}, {{splitEntryIndex}})">
                    <span class="glyphicon glyphicon-minus"></span>
                  </button>
                  <button type="button" class="btn" onclick="addSplitEntryLine({{num}})" id="{{num}}addmimmoresplitentries{{splitEntryIndex}}">
                    <span class="glyphicon glyphicon-plus"></span>
                  </button>
                </span>
              </div>
            </div>
            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="hidden" id="{{num}}addmimsubmoneyflowsplitentryid{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][moneyflowsplitentryid]" value="-1">
                  <input type="number" step="0.01" class="form-control" id="{{num}}addmimsubamount{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][amount]"  data-error="{{{amountError}}}" onChange="calculateRemainingAmount({{num}}, {{splitEntryIndex}});checkIfRequired({{num}}, {{splitEntryIndex}})">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="{{num}}addmimsubamount{{splitEntryIndex}}">{{amountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control" id="{{num}}addmimsubcomment{{splitEntryIndex}}" name="all_subdata[{{splitEntryIndex}}][comment]"  data-error="{{{commentError}}}" onChange="checkIfRequired({{num}}, {{splitEntryIndex}})">
                </div>
                <label for="{{num}}addmimsubcomment{{splitEntryIndex}}">{{commentLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_subdata[{{splitEntryIndex}}][mpa_postingaccountid]" id="{{num}}addmimsubmpa_postingaccountid{{splitEntryIndex}}"  data-error="{{{postingAccountError}}}" onChange="checkIfRequired({{num}}, {{splitEntryIndex}})">
                    <option value="">&nbsp;</option>
{{#postingAccounts}}
                    <option value="{{postingaccountid}}">{{name}}</option>
{{/postingAccounts}}
                  </select>
                </div>
                <label for="{{num}}addmimsubmpa_postingaccountid{{splitEntryIndex}}">{{postingAccountLabel}}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
            
            <div class="form-group col-md-2 col-xs-12" id="{{num}}addmimremainingDiv{{splitEntryIndex}}">
              <div class="input-group col-xs-12">
                <span class="input-group-btn">
                  <button type="button" class="btn" onclick="copyRemainer({{num}})">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                  </button>
                </span>
                <span class="has-float-label">
                  <input type="text" class="form-control" id="{{num}}addmimremaining{{splitEntryIndex}}" readonly pattern="0">
                  <label for="{{num}}addmimremaining{{splitEntryIndex}}">Rest</label>
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

        var previousCommentSetByContractpartnerDefaults = [];
        var previousPostingAccountSetByContractpartnerDefaults = [];

        var shownSplitEntryRows = [];
        var filledSplitEntryRows = [];
        var splitEntryIndex = [];
        var splitEntriesTemplateData = {
          num: -1,
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
        function deleteImportedMoneyflow(num) {
          $('#' + num + 'addmimdelete').val(1);
          $('#' + num + 'addmimform').submit();
        }

        /**
         * When changing a contractpartner in the drop down box, fill out his defaults for
         * comment and postingaccount - but only if the user didn't modified the fields
         * on his own in the meantime
         */
        function setContractpartnerDefaults(num) {
          var length = addImportedMoneyflowJsonContractpartner.length;

          var addmimcomment = $('#' + num + 'addmimcomment');
          var addmimmpa_postingaccountid = $('#' + num + 'addmimmpa_postingaccountid');
          var addmimmcp_contractpartnerid_val = $('#' + num + 'addmimmcp_contractpartnerid').val();
          
          var updateComment = false;
          var updatePostingAccount = false;

          if( addmimcomment.val() == previousCommentSetByContractpartnerDefaults[num]  || ( ! addmimcomment.val() && ! previousCommentSetByContractpartnerDefaults[num] ))
            updateComment = true;

          if( addmimmpa_postingaccountid.val() == previousPostingAccountSetByContractpartnerDefaults[num] || ( ! addmimmpa_postingaccountid.val() && ! previousPostingAccountSetByContractpartnerDefaults[num] ))
            updatePostingAccount = true;
            
          if( updateComment || updatePostingAccount ) {
            for (i=0 ; i<length ; i++) {
              if (addImportedMoneyflowJsonContractpartner[i]["contractpartnerid"] == addmimmcp_contractpartnerid_val) {
                if ( updateComment ) {
                  addmimcomment.val(addImportedMoneyflowJsonContractpartner[i]["moneyflow_comment"]);
                  previousCommentSetByContractpartnerDefaults[num] = addImportedMoneyflowJsonContractpartner[i]["moneyflow_comment"];
                }
                if ( updatePostingAccount ) {
                  addmimmpa_postingaccountid.val(addImportedMoneyflowJsonContractpartner[i]["mpa_postingaccountid"]);
                  previousPostingAccountSetByContractpartnerDefaults[num] = addImportedMoneyflowJsonContractpartner[i]["mpa_postingaccountid"];
                }
                break;
              }
            }
          }
          $('#' + num + 'addmimform').validator('reset');
          $('#' + num + 'addmimform').validator('update');
        }

        function setElement(element, value) {
            if ( element !== null ) {
              if ( element.tagName == 'DIV' ) {
                element.innerHTML = value;
              } else {
                element.value = value;
              }
            }
        }
                
        function preFillFormAddImportedMoneyflow(num) {

          $(function() {
            $('#' + num + 'favorite').bootstrapToggle('destroy');
          })

          for ( var key in addImportedMoneyflowJsonFormDefaults[num-1] ) {
            var element = document.getElementById( num+'addmim'+key );
            setElement(element, addImportedMoneyflowJsonFormDefaults[num-1][key]);

            element = document.getElementById( num+'addmim'+key+'_div' );
            setElement(element, addImportedMoneyflowJsonFormDefaults[num-1][key]);
          }

          $('#' + num + 'addmimform').validator('reset');
          $('#' + num + 'addmimform').validator('update');

          $(function() {
            $('#' + num + 'favorite').bootstrapToggle({
              on: onEmpty,
              off: offEmpty
            });
          })
        }

	function toggleAddImportedMoneyflowSplitEntries (num) {
	  var element = document.getElementById(num+'addmimToggleSplitEntries');
	  element.innerHTML = '<span class="glyphicon glyphicon-minus"></span>';
	}

        /************************************************************
         *
         * SPLIT ENTRY STUFF
         *
         ************************************************************/
         
        function checkIfRequired(num, row) {
          var elementAmount = $('#' + num + 'addmimsubamount' + row);
          var elementComment = $('#' + num + 'addmimsubcomment' + row);
          var elementPostingAccount = $('#' + num + 'addmimsubmpa_postingaccountid' + row);
          
          var required = true;
          if(elementAmount.val().length == 0 && elementComment.val().length == 0 && elementPostingAccount.val().length == 0) {
            required = false;
          }

          var currentRequired = elementAmount.prop('required');
          if(currentRequired != required) {
            if(required) {
              filledSplitEntryRows[num].push(row);
            } else {
              var index = filledSplitEntryRows[num].indexOf(row);
              if(index > -1) {
                filledSplitEntryRows[num].splice(index,1);
              }
            }
            hideNonRelevantFieldsIfSplitEntries(num);
            elementAmount.prop('required',required).change();
            elementComment.prop('required',required).change();
            elementPostingAccount.prop('required',required).change();
 
            $('#' + num + 'addmimform').validator('reset');
            $('#' + num + 'addmimform').validator('update');
          }
        }

        function hideNonRelevantFieldsIfSplitEntries(num) {
          if(filledSplitEntryRows[num].length > 0) {
            if($('#' + num + 'addmimcommentDiv').is(':visible')) {
              $('#' + num + 'addmimcommentDiv').hide();
              $('#' + num + 'addmimcomment').prop('required',false).change();
              $('#' + num + 'addmimmpa_postingaccountidDiv').hide();
              $('#' + num + 'addmimmpa_postingaccountid').prop('required',false).change();
            }
          } else {
            if($('#' + num + 'addmimcommentDiv').is(':hidden')) {
              $('#' + num + 'addmimcommentDiv').show();
              $('#' + num + 'addmimcomment').prop('required',true).change();
              $('#' + num + 'addmimmpa_postingaccountidDiv').show();
              $('#' + num + 'addmimmpa_postingaccountid').prop('required',true).change();
            }
          }
        }
        
        function calculateRemainingAmount(num, row) {
          var amount = $('#' + num + 'addmimamount').val();
          if(!amount)
            amount = 0;

          var length = shownSplitEntryRows[num].length;

          // have we edited the last row? If yes add a new one automatically
          if( row == shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1] ) {
            addSplitEntryLine(num);
            length = shownSplitEntryRows[num].length;
          }

          for(i=0 ; i < length ; i++ ) {
            var subamount = $('#' + num + 'addmimsubamount' + shownSplitEntryRows[num][i]).val();
            if(subamount != null) {
              amount -= subamount;
            }

          }
          if(amount == 0 || !amount ) {
            $('#' + num + 'addmimremainingDiv' + (shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1])).hide();
          } else {
            $('#' + num + 'addmimremainingDiv' + (shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1])).show();
          }
                      
          $('#' + num + 'addmimremaining' + (shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1])).val(amount.toFixed(2));
        }
        
        function copyRemainer(num) {
          var lastSubElement = shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1];
          var addmimsubamount = $('#' + num + 'addmimsubamount' + lastSubElement);
          var addmimremaining = $('#' + num + 'addmimremaining' + lastSubElement);

          var addmimcomment = $('#' + num + 'addmimcomment');
          var addmimmpa_postingaccountid = $('#' + num + 'addmimmpa_postingaccountid');
          var addmimsubcomment = $('#' + num + 'addmimsubcomment' + lastSubElement);
          var addmimsubmpa_postingaccountid = $('#' + num + 'addmimsubmpa_postingaccountid' + lastSubElement);

          addmimsubcomment.val(addmimcomment.val());
          addmimsubmpa_postingaccountid.val(addmimmpa_postingaccountid.val());
          addmimsubamount.val(addmimremaining.val());
          checkIfRequired(lastSubElement);
          calculateRemainingAmount(num);
        }
         
         
        function initSplitEntries(num) {
          $('#' + num + 'addmimsplitentries').empty();
          shownSplitEntryRows[num] = [];
          filledSplitEntryRows[num] = [];
          splitEntryIndex[num] = -1
          splitEntriesTemplateData.splitEntryIndex = splitEntryIndex[num];
          
          addSplitEntryLine(num);
          addSplitEntryLine(num);
          hideNonRelevantFieldsIfSplitEntries(num);
        }

        /*
         * Adds a split entry line when a "+" was clicked.
         * Also moves the "+" button to the newly displayed row as well as the "remaining" box
         *
         */
        function addSplitEntryLine(num) {

          splitEntryIndex[num]++;
          splitEntriesTemplateData.splitEntryIndex = splitEntryIndex[num];
          splitEntriesTemplateData.num = num;

          var template = $('#template').html();
          var rendered = Mustache.render(template,splitEntriesTemplateData);
          $('#' + num + 'addmimsplitentries').append( rendered );

	  if( shownSplitEntryRows[num].length > 0 ) {
            $('#' + num + 'addmimmoresplitentries' + (shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1])).hide();
            $('#' + num + 'addmimremainingDiv' + (shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1])).hide();
            
          }
          
          shownSplitEntryRows[num].push(splitEntryIndex[num]);

          $('#' + num + 'addmimform').validator('reset');
          $('#' + num + 'addmimform').validator('update');

          calculateRemainingAmount(num);
        }
        
        /*
         * Removes the split entry line where the "-" was clicked.
         * If the number of displayed rows would then be lower than two, a new empty row is added afterwards
         *
         */
        function removeSplitEntryLine(num, splitEntryIndex) {

          $('#' + num + 'addmimsub' + splitEntryIndex).remove();

          // maintain state-arrays
          var index = shownSplitEntryRows[num].indexOf(splitEntryIndex);
          if(index > -1) {
            shownSplitEntryRows[num].splice(index,1);
          }
          index = filledSplitEntryRows[num].indexOf(splitEntryIndex);
          if(index > -1) {
            filledSplitEntryRows[num].splice(index,1);
          }
          

          // make sure more than two entry cannot be deleted
          // we always show at least two rows!
          if( shownSplitEntryRows[num].length < 2) {
            addSplitEntryLine(num);
          }
          
          // show the "+" button and the "remaining" information on the last displayed row
          $('#' + num + 'addmimmoresplitentries' + shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1]).show();
          $('#' + num + 'addmimremainingDiv' + shownSplitEntryRows[num][shownSplitEntryRows[num].length - 1]).show();

          // recalculate the "remaining" amount
          calculateRemainingAmount(num);

          // show comment and postingAccount of the main booking again if all rows where deleted
          hideNonRelevantFieldsIfSplitEntries(num); 
        }
        
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function copyIfSplitEntries(num) {
          if(filledSplitEntryRows[num].length > 0) {
            filledSplitEntryRows[num].sort(function(a, b){return a-b});
            var elementSplitEntryComment = $('#' + num + 'addmimsubcomment' + filledSplitEntryRows[num][0]);
            var elementSplitEntryPostingAccount = $('#' + num + 'addmimsubmpa_postingaccountid' + filledSplitEntryRows[num][0]);
            $('#' + num + 'addmimcomment').val(elementSplitEntryComment.val());
            $('#' + num + 'addmimmpa_postingaccountid').val(elementSplitEntryPostingAccount.val());
          }
          return true;
        }

        function ajaxAddImportedMoneyflowSuccess(num) {
          $('#' + num + 'addmimentry').remove();
        }

        function ajaxAddImportedMoneyflowError(num, data) {
          clearErrorDiv(num + 'addImportedMoneyflowErrors');
          console.log(data.responseText);
          populateErrorDiv(data.responseText,num + 'addImportedMoneyflowErrorsGoHere',num + 'addImportedMoneyflowErrors');
        }

{/literal}

{for $NUM=1 to $NUM_MONEYFLOWS}
        initSplitEntries({$NUM});
        preFillFormAddImportedMoneyflow({$NUM});
        calculateRemainingAmount({$NUM});
        setContractpartnerDefaults({$NUM});

        $('#{$NUM}addmimform').validator();
        $('#{$NUM}addmimform').ajaxForm({
            beforeSubmit: function() {
              return copyIfSplitEntries({$NUM});
            },
            dataType: 'json',
            success: function() {
                       ajaxAddImportedMoneyflowSuccess({$NUM});
                     },
            error: function(data) {
                       ajaxAddImportedMoneyflowError({$NUM}, data);
                     }
        });
{/for}        

      </script>
{$FOOTER}

