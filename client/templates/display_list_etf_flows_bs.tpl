{$HEADER}

{literal}
<script language="JavaScript">
	function mySubmit() {
		if(document.validForm.currently_valid_checkbox.checked) {
			document.validForm.currently_valid.value = "1";
		} else {
			document.validForm.currently_valid.value = "0";
		}
		document.validForm.submit();
	}
</script>
{/literal}


      <div class="container">
        <div class="text-center">
          <h4>{#TEXT_340#}</h4>
        </div>
        <div class="container">
{if $COUNT_ALL_DATA > 0}
          <br><br>
          <table class="table table-striped table-bordered table-hover">
                      <col style="width:40%">
                      <col style="width:10%">
                      <col style="width:10%">
                      <col style="width:10%">
                      <col style="width:10%">
                      <col style="width:10%">
                      <col style="width:10%">
            <thead>
              <tr>
                <th class="text-center">{#TEXT_41#}</th>
                <th class="text-center">{#TEXT_16#}</th>
                <th class="text-center">{#TEXT_332#}</th>
                <th class="text-center">{#TEXT_333#}</th>
                <th class="text-center">{#TEXT_335#}</th>
                <th class="text-center" colspan="2"></th>
              </tr>
            </thead>
            <tbody>
{assign "PIECE" "0"}
{assign "OVERALL_PRICE" "0"}
{section name=DATA loop=$ALL_DATA}
              {math equation="x * y" x=$ALL_DATA[DATA].amount y=$ALL_DATA[DATA].price assign=SUM_PRICE}
              {math equation="x + y" x=$SUM_PRICE y=$OVERALL_PRICE assign=OVERALL_PRICE}
              {math equation="x + y" x=$ALL_DATA[DATA].amount y=$PIECE assign=PIECE}

              <tr>
                <td><a href="{$ALL_DATA[DATA].chartUrl}">{$ALL_DATA[DATA].name}</a></td>
                <td>{$ALL_DATA[DATA].date}</td>
                <td class="text-right">{$ALL_DATA[DATA].amount|number_format_variable:3}</td>
                <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].price|number_format_variable:3} {#CURRENCY#}</td>
                <td class="text-right of_number_to_be_evaluated">{$SUM_PRICE|number_format} {#CURRENCY#}</td>
                <td><i href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_et_flowf&amp;etfflowid={$ALL_DATA[DATA].etfflowid}&amp;sr=1','_blank','width=1000,height=150')">{#TEXT_37#}</i></td>
                <td><i href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_etf_flow&amp;etfflowid={$ALL_DATA[DATA].etfflowid}&amp;sr=1','_blank','width=600,height=460')">{#TEXT_36#}</i></td>
              </tr>
{/section}
              {math equation="x / y" x=$OVERALL_PRICE y=$PIECE assign=MEAN_PRICE}

              <tr>
                <td colspan="2"></td>
                <td class="text-right"><b>{$PIECE|number_format_variable:3}</b></td>
                <td class="text-right of_number_to_be_evaluated"><b>{$MEAN_PRICE|number_format_variable:3} {#CURRENCY#}</b></td>
                <td class="text-right of_number_to_be_evaluated"><b>{$OVERALL_PRICE|number_format_variable:2} {#CURRENCY#}</b></td>
                <td colspan="2"></td>
              </tr>
            </tbody>
          </table>
{/if}
        <div>

        <div class="text-center">
          <h4>{#TEXT_341#}</h4>
        </div>

        <div>
        <form action="{$ENV_INDEX_PHP}" method="POST" name="calcetfsale" id="calcetfsalefrm">
          <input type="hidden" name="action" value="calc_etf_sale">

          <div class="span2 well">

            <div id="calcEtfSaleErrorsGoHere">
            </div>

    <div class="row">
            
            <div class="form-group col-md-4 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <select class="form-control" name="all_data[isin]" id="calcetfisin" required data-error="{#TEXT_344#}">
{section name=ETF loop=$ETF_VALUES}
                    <option value="{$ETF_VALUES[ETF].isin}" {if $ETF_VALUES[ETF].isin == $CALC_ETF_SALE_ISIN}selected{/if}>{$ETF_VALUES[ETF].name}</option>
{/section}
                  </select>
                </div>
                <label for="calcetfisin">{#TEXT_331#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="1" class="form-control" lang="en" id="calcetfpcs" name="all_data[pieces]" required data-error="{#TEXT_343#}" autofocus value="{$CALC_ETF_SALE_PIECES}">
                </div>
                <label for="calcetfpcs">{#TEXT_333#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.001" class="form-control" lang="en" id="calcetfbid" name="all_data[bidPrice]" required data-error="{#TEXT_306#}" value="{$CALC_ETF_BID_PRICE|number_format_variable:3}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="calcetfbid">{#TEXT_336#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.001" class="form-control" lang="en" id="calcetfask" name="all_data[askPrice]" required data-error="{#TEXT_306#}" value="{$CALC_ETF_ASK_PRICE|number_format_variable:3}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="calcetfask">{#TEXT_337#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>

            <div class="form-group col-md-2 col-xs-12">
              <span class="has-float-label">
                <div class="input-group col-xs-12">
                  <input type="number" step="0.01" class="form-control" lang="en" id="calcetftrans" name="all_data[transactionCosts]" required data-error="{#TEXT_306#}" value="{$CALC_ETF_TRANSACTIOM_COSTS|number_format_variable:2}">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-euro"></span>
                  </span>
                </div>
                <label for="calcetftrans">{#TEXT_342#}</label>
              </span>
              <div class="help-block with-errors"></div>
            </div>
    </div>
          </div>
            <div class="form-group">
              <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary"                          >{#TEXT_341#}</button>
              </div>  
            </div>  
        </form>
        </div>

      </div>
<br><br><br>
      <div class="col-lg-4 col-lg-push-4 col-sm-6 col-sm-push-3" id="calcetfsale_results" style="display: none;">
          <table class="table table-striped table-bordered table-hover">
                      <col style="width:70%">
                      <col style="width:30%">
            <tbody>
              <tr>
                <th>{#TEXT_345#}</th>
                <td class="text-right"><div id="calcetfsale_result_pieces"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_346#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_originalBuyPrice"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_347#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_sellPrice"></div></td>
              <tr>
            </tbody>
          </table>
          <table class="table table-striped table-bordered table-hover">
                      <col style="width:70%">
                      <col style="width:30%">
            <tbody>
              <tr>
                <th>{#TEXT_348#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_profit"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_349#}</th>
                <td class="text-right of_number_to_be_evaluated"><b><div id="calcetfsale_result_chargeable"></div></b></td>
              <tr>
         
            </tbody>
          </table>
          <table class="table table-striped table-bordered table-hover">
                      <col style="width:70%">
                      <col style="width:30%">
            <tbody>
              <tr>
                <th>{#TEXT_350#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_newBuyPrice"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_351#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_rebuyLosses"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_342#}</th>
                <td class="text-right of_number_to_be_evaluated"><div id="calcetfsale_result_transactionCosts"></div></td>
              <tr>
              <tr>
                <th>{#TEXT_352#}</th>
                <td class="text-right of_number_to_be_evaluated"><b><div id="calcetfsale_result_overallCosts"></div></b></td>
              <tr>
            </tbody>
          </table>
      </div>
<script>
        var currency = "{#CURRENCY#}"
{literal}
        var formatNumber = new Intl.NumberFormat('en', {minimumFractionDigits: 2, maximumFractionDigits: 2})

        function formatCurrency(price) {
          return formatNumber.format(price) + " " + currency;
        }

        function preFillFormCalcEtfSale(formMode) {
          $('#calcetfsalefrm').validator('reset');
          $('#calcetfsalefrm').validator('update');
        }

        
        function fillCalculationCalcEtfSale(calcResult) {
          $('#calcetfsale_result_originalBuyPrice').html(formatCurrency(calcResult.originalBuyPrice));
          $('#calcetfsale_result_sellPrice').html(formatCurrency(calcResult.sellPrice));
          $('#calcetfsale_result_newBuyPrice').html(formatCurrency(calcResult.newBuyPrice));
          $('#calcetfsale_result_profit').html(formatCurrency(calcResult.profit));
          $('#calcetfsale_result_chargeable').html(formatCurrency(calcResult.chargeable));
          $('#calcetfsale_result_transactionCosts').html(formatCurrency(calcResult.transactionCosts));
          $('#calcetfsale_result_rebuyLosses').html(formatCurrency(calcResult.rebuyLosses));
          $('#calcetfsale_result_overallCosts').html(formatCurrency(calcResult.overallCosts));
          $('#calcetfsale_result_pieces').html(calcResult.pieces);

          $("td.of_number_to_be_evaluated:contains('-')").addClass('red');
          $("td.of_number_to_be_evaluated:not(:contains('-'))").removeClass('red');

          $('#calcetfsale_results').show();
        }

        function ajaxCalcEtfSaleSuccess(data) {
          clearErrorDiv('calcEtfSaleErrors');
          fillCalculationCalcEtfSale(data)
        }

        function ajaxCalcEtfSaleError(data) {
          clearErrorDiv('calcEtfSaleErrors');
          populateErrorDiv(data.responseText,'calcEtfSaleErrorsGoHere','calcEtfSaleErrors');
        }

        preFillFormCalcEtfSale(FORM_MODE_DEFAULT);

        $('#calcetfsalefrm').validator();
        $('#calcetfsalefrm').ajaxForm({
            dataType: 'json',
            success: ajaxCalcEtfSaleSuccess,
            error: ajaxCalcEtfSaleError
        });
        

{/literal}
      </script>

{$FOOTER}
