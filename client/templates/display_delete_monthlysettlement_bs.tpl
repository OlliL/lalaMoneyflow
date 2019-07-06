{$HEADER}
{if $COUNT_ALL_DATA > 0}
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>

        <div class="text-center">
          <u>{#TEXT_59#}</u>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>

        <div id="deleteMonthlysettlementErrorsGoHere">
        </div>

        <div class="row">
          <div class="col-lg-4 col-lg-push-4 col-sm-6 col-sm-push-3">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_53#} {$MONTH.name} {$YEAR}</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
                      <col style="width:60%">
                      <col style="width:40%">

                      <thead>
                        <tr>
                          <th class="text-center">{#TEXT_19#}</th>
                          <th class="text-center">{#TEXT_18#}</th>
                        </tr>
                      </thead>

                      <tbody>

	{section name=DATA loop=$ALL_DATA}
		{if $ALL_DATA[DATA].capitalsourcetype != 5}
			<tr>
			  <td class="text-left">{$ALL_DATA[DATA].capitalsourcecomment}</td>
			  <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
			</tr>
		{else}
			{assign var=CREDIT_EXISTS value=1}
		{/if}
	{/section}
			<tr>
                          <td class="text-right">&sum;</td>
                          <td class="text-right of_number_to_be_evaluated"><u>{$SUMAMOUNT|number_format} {#CURRENCY#}</u></td>
			</tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
	{if $CREDIT_EXISTS == 1 }
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
                      <col style="width:60%">
                      <col style="width:40%">

                      <tbody>

	{section name=DATA loop=$ALL_DATA}
		{if $ALL_DATA[DATA].capitalsourcetype == 5}
			<tr>
			  <td class="text-left">{$ALL_DATA[DATA].capitalsourcecomment}</td>
			  <td class="text-right of_number_to_be_evaluated">{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
			</tr>
		{/if}
	{/section}
			<tr>
                          <td class="text-right">&sum;</td>
                          <td class="text-right of_number_to_be_evaluated"><u>{$CREDIT_SUMAMOUNT|number_format} {#CURRENCY#}</u></td>
			</tr>
                      </tbody>
                    </table>
                  </div>
                </div>
 	{/if}
              </div>
            </div>
          </div>
        </div>

        <div>
          <form action="{$ENV_INDEX_PHP}" method="POST" name="deletemonthlysettlement" id="delmmsform">
           <input type="hidden" name="action"  value="delete_monthlysettlement_submit">
            <input type="hidden" name="monthlysettlements_month" value="{$MONTH.nummeric}">
            <input type="hidden" name="monthlysettlements_year" value="{$YEAR}">

            <div class="form-group">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn"             onclick="btnDeleteMonthlysettlementCancel()"    >{#TEXT_315#}</button>
                <button type="submit" class="btn btn-danger"                                                  >{#TEXT_37#}</button>
              </div>  
            </div>  
          </form>
        </div>

      <script>
{literal}
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnDeleteMonthlysettlementCancel() {
          window.close();
        }

        function ajaxDeleteMonthlysettlementSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxDeleteMonthlysettlementError(data) {
          clearErrorDiv('deleteMonthlysettlementErrors');
          populateErrorDiv(data.responseText,'deleteMonthlysettlementErrorsGoHere','deleteMonthlysettlementErrors');
        }

        $("td.of_number_to_be_evaluated:contains('-')").addClass('red');

        $('#delmmsform').validator();
        $('#delmmsform').ajaxForm({
            dataType: 'json',
            success: ajaxDeleteMonthlysettlementSuccess,
            error: ajaxDeleteMonthlysettlementError
        });
{/literal}
      </script>
{/if}
{$FOOTER}
