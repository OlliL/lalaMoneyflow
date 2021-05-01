{$HEADER}
      <div class="container container-wide">
        <div class="text-center">
          <h4>{#TEXT_364#}</h4>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
{if $NUM_IMPORTEDMONEYFLOWRECEIPTS eq 0}
<br>
<p align="center">{#TEXT_365#}</p>
{/if}

{for $NUM=1 to $NUM_IMPORTEDMONEYFLOWRECEIPTS}
        <div class="well" id="{$NUM}impimrentry">
        <div class="row">
        <form action="{$ENV_INDEX_PHP}" method="POST" name="addimportedmoneyflow" id="{$NUM}impimrform">
          <input type="hidden" name="action"                          value="import_imported_moneyflow_receipt_submit">
          <input type="hidden" name="all_data[delete]"                value="0"  id="{$NUM}impimrdelete">
          <input type="hidden" name="all_data[id]"                    value="-1" id="{$NUM}impimrid">

          <div class="span2 well">
            <div id="{$NUM}importImportedMoneyflowReceiptErrorsGoHere">
            </div>
          
            <div class="row">
              <div style="overflow-x:scroll; white-space: nowrap; height:300px;" class="col-md-3 col-xs-12">
                <img src="" id="{$NUM}impimrreceipt" style="max-width:100%;">
              </div>

              <div class="form-group col-md-9 col-xs-12">

                <div class="row">
                  <div class="form-group col-md-1 col-xs-12">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-primary" onclick="searchForAmount({$NUM})">
                            <span class="glyphicon glyphicon-search"></span>
                          </button>
                        </span>
                  </div>
                  <div class="form-group col-md-4 col-xs-12">
                    <span class="has-float-label">
                      <div class="input-group col-xs-12">
                        <input type="number" step="0.01" class="form-control" id="{$NUM}impimramount" name="all_data[amount]" autofocus style="text-align: right;">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-euro"></span>
                        </span>
                      </div>
                      <label for="{$NUM}impimramount">{#TEXT_18#}</label>
                    </span>
                    <div class="help-block with-errors"></div>
                  </div>

            <div class="form-group col-md-3 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="{$NUM}impimrdatefromDiv">
                  <input type="text" class="form-control" name="all_data[dateFrom]" id="{$NUM}impimrdatefrom">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="{$NUM}impimrdatefrom">{#TEXT_69#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#{$NUM}impimrdatefromDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true, 
                      });
                  });
              </script>
            </div>

            <div class="form-group col-md-3 col-xs-12">
              <span class="has-float-label">
                <div class='input-group date col-xs-12' id="{$NUM}impimrdatetilDiv">
                  <input type="text" class="form-control" name="all_data[dateTil]" id="{$NUM}impimrdatetil">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
                <label for="{$NUM}impimrdatetil">{#TEXT_70#}</label>
              </span>
              <div class="help-block with-errors"></div>
              <script>
                  $(function () {
                      $('#{$NUM}impimrdatetilDiv').datetimepicker({
                        format: 'YYYY-MM-DD',
                        focusOnShow: false,
                        showClear: true,
                        showTodayButton: true,
                        showClose: true, 
                      });
                  });
              </script>
            </div>

                </div>

                <div class="row">
                  <div class="panel-group hide-on-small col-md-12 col-xs-12">
                    <table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
                      <col style="width:5%">
                      <col style="width:25%">
                      <col style="width:20%">
                      <col style="width:30%">
                      <col style="width:40%">
                      <thead>
                        <tr>
                          <th class="text-center">&nbsp;</th>
                          <th class="text-center">{#TEXT_17#}</th>
                          <th class="text-center">{#TEXT_18#}</th>
                          <th class="text-center">{#TEXT_2#}</th>
                          <th class="text-center">{#TEXT_21#}</th>
                        </tr>
                      </thead>

                      <tbody id="{$NUM}impimrresults">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12 text-center">
              <button type="submit" class="btn btn-primary"                          >{#TEXT_271#}</button>
              <button type="button" class="btn btn-danger cancel" onclick="deleteImportedMoneyflowReceipt({$NUM})">{#TEXT_37#}</button>
            </div>
          </div>

        </form>
      </div>
    </div>
{/for}

    </div>

{literal}    
<script id="template" type="x-tmpl-mustache">
          <tr>
            <td><input type="radio" name="all_data[moneyflowid]" value="{{moneyflowid}}" {{#first}}checked{{/first}}></input></td>
            <td class="text-left">{{invoicedate}}</td>
            <td class="text-right">
            {{#amount_negative}}
              <font color="red">
            {{/amount_negative}}
            {{{amount_formatted}}}
            {{#amount_negative}}
              </font>
            {{/amount_negative}}
            </td>
            <td class="text-left">{{{contractpartnername}}}</td>
            <td class="text-left">{{{comment}}}</td>
          </tr>
</script>
{/literal}


<script>
        var importImportedMoneyflowReceiptJsonDefaults = {$JSON_FORM_DEFAULTS};
        var index = [];
        var currency = "{#CURRENCY#}"

{literal}
        var formatNumber = new Intl.NumberFormat('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})

        function formatCurrency(price) {
          return formatNumber.format(price);
        }

        function setElement(element, value) {
            if ( element !== null ) {
              if ( element.tagName == 'DIV' ) {
                element.innerHTML = value;
              } else if ( element.tagName == 'IMG' ) {
                element.src = value;
             } else if ( element.tagName == 'OBJECT' ) {
                element.setAttribute('data', value);
              } else {
                element.value = value;
              }
            }
        }

        function deleteImportedMoneyflowReceipt(num) {
          $('#' + num + 'impimrdelete').val(1);
          $('#' + num + 'impimrentry').hide();
          $('#' + num + 'impimrform').submit();
        }

        function preFillFormImportImportedMoneyflowReceipt(num) {
        
        
          var now = new Date();
          var element = document.getElementById( num+'impimrdatetil' );
          element.value = now.toISOString();
          element = document.getElementById( num+'impimrdatefrom' );
          now.setMonth(now.getMonth() -1 );
          element.value = now.toISOString();
          
          for ( var key in importImportedMoneyflowReceiptJsonDefaults[num-1] ) {
            element = document.getElementById( num+'impimr'+key );
            
            if( key == 'amount' ) {
              amount = formatCurrency(importImportedMoneyflowReceiptJsonDefaults[num-1][key]);
              setElement(element, amount);
            } else if (key == "receipt") {
              mediaType = importImportedMoneyflowReceiptJsonDefaults[num-1]["mediaType"];
              if( mediaType == "application/pdf" ) {
                newElement = document.createElement('object');
                newElement.setAttribute('id', num+'impimr'+key);
                newElement.setAttribute('style','max-width:100%;height:300px;');
                element.replaceWith(newElement);
                element = document.getElementById( num+'impimr'+key );
                element.parentElement.setAttribute('style','');
              }
                
              src = "data:"
                    + mediaType
                    + ";base64,"
                    + importImportedMoneyflowReceiptJsonDefaults[num-1][key];
              setElement(element, src);
            } else {
              setElement(element, importImportedMoneyflowReceiptJsonDefaults[num-1][key]);
              element = document.getElementById( num+'impimr'+key+'_div' );
              setElement(element, importImportedMoneyflowReceiptJsonDefaults[num-1][key]);
            }
          }

          searchForAmount(num);
          
          $('#' + num + 'impimrform').validator('reset');
          $('#' + num + 'impimrform').validator('update');

        }
        
        /*
         * SEARCH STUFF
         */

        function initResults(num) {
          $('#' + num + 'impimrresults').empty();
          index[num] = -1;
        }

        function addResultLine(num, data) {

          index[num]++;
          data['first'] = index[num] == 0 ? true : false;
          data['index'] = index[num];
          data['amount_negative'] = data['amount']+0 < 0 ? true : false;
          data['amount_formatted'] = formatCurrency(data['amount']) + currency;

          var template = $('#template').html();
          var rendered = Mustache.render(template,data);
          $('#' + num + 'impimrresults').append( rendered );
        }

         
        function searchForAmount(num) {
          initResults(num);
          amount = document.getElementById( num+'impimramount').value
          dateFrom = document.getElementById( num+'impimrdatefrom').value
          dateTil = document.getElementById( num+'impimrdatetil').value
          
          
          $.get( '?action=search_moneyflow_by_amount&amount=' + amount + '&datefrom=' + dateFrom + '&datetil='+dateTil, function( data ) {
            if(data) {
              json = JSON.parse(data);
              for (const item of Object.keys(json['moneyflows'])) {
                addResultLine(num, json['moneyflows'][item]);
              }
            }
          });
        }

        function ajaxImportImportedMoneyflowReceiptSuccess(num) {
          $('#' + num + 'impimrentry').remove();
        }

        function ajaxImportImportedMoneyflowReceiptError(num, data) {
          clearErrorDiv(num + 'importImportedMoneyflowReceiptErrors');
          populateErrorDiv(data.responseText,num + 'importImportedMoneyflowReceiptErrorsGoHere',num + 'importImportedMoneyflowReceiptErrors');
        }

{/literal}

{for $NUM=1 to $NUM_IMPORTEDMONEYFLOWRECEIPTS}
      preFillFormImportImportedMoneyflowReceipt({$NUM});

      $('#{$NUM}impimrform').validator();
      $('#{$NUM}impimrform').ajaxForm({
          dataType: 'json',
          success: function() {
                     ajaxImportImportedMoneyflowReceiptSuccess({$NUM});
                   },
          error: function(data) {
                     ajaxImportImportedMoneyflowReceiptError({$NUM}, data);
                   }
      });
{/for}

</script>

{$FOOTER}

