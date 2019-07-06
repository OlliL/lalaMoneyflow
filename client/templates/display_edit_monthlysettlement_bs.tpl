{$HEADER}
{literal}
<script>
<!--
function Go(month,year)
{
{/literal}
   referer = "{$ENV_REFERER}"
{literal}
   location.href = "index.php?action=edit_monthlysettlement&monthlysettlements_month=" + month + "&monthlysettlements_year=" + year + "&REFERER=" + referer;
   document.monthlysettlements.reset();
   document.monthlysettlements.elements[0].blur();
}
//-->
</script>
{/literal}
{if $COUNT_ALL_DATA gt 0}
      <form action="{$ENV_INDEX_PHP}" method="POST" name="editmonthlysettlement" id="edtmmsform">
        <input type="hidden" name="action"  value="edit_monthlysettlement_submit">
        <div class="text-center">
          <h4>{if $NEW != 1}{#TEXT_54#}{else}{#TEXT_55#}{/if}</h4>
        </div>

        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>

        <div id="editMonthlysettlementErrorsGoHere">
        </div>

        <div class="row">
          <div class="col-lg-4 col-lg-push-4 col-sm-6 col-sm-push-3 col-xs-10 col-xs-push-1">
                <div class="row">
                  <div class="col-xs-12 text-center">
			{#TEXT_56#}
			<select class="contrastbgcolor" name="monthlysettlements_month" id="edtmmsMonthSelectbox" onchange="Go(document.getElementById('edtmmsMonthSelectbox').options[document.getElementById('edtmmsMonthSelectbox').options.selectedIndex].value,document.getElementById('edtmmsYearTextbox').value)">
				<option {if $MONTH.nummeric == "01"}selected{/if} value="01"> 01
				<option {if $MONTH.nummeric == "02"}selected{/if} value="02"> 02
				<option {if $MONTH.nummeric == "03"}selected{/if} value="03"> 03
				<option {if $MONTH.nummeric == "04"}selected{/if} value="04"> 04
				<option {if $MONTH.nummeric == "05"}selected{/if} value="05"> 05
				<option {if $MONTH.nummeric == "06"}selected{/if} value="06"> 06
				<option {if $MONTH.nummeric == "07"}selected{/if} value="07"> 07
				<option {if $MONTH.nummeric == "08"}selected{/if} value="08"> 08
				<option {if $MONTH.nummeric == "09"}selected{/if} value="09"> 09
				<option {if $MONTH.nummeric == "10"}selected{/if} value="10"> 10
				<option {if $MONTH.nummeric == "11"}selected{/if} value="11"> 11
				<option {if $MONTH.nummeric == "12"}selected{/if} value="12"> 12
			</select>
			{#TEXT_57#}
			<input class="contrastbgcolor" type="text" name="monthlysettlements_year" id="edtmmsYearTextbox" value="{$YEAR}" size="4" onchange="Go(this.form.monthlysettlements_month.options[this.form.monthlysettlements_month.options.selectedIndex].value,this.form.monthlysettlements_year.value)">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-xs-12">&nbsp;</div>
                </div>
                
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
			  <td class="text-left" style="vertical-align: middle;">
			    {if $NEW == 1 }<input type="hidden" name="all_data[{$smarty.section.DATA.index}][new]" value="1">
                            {/if}<input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].mcs_capitalsourceid}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][month]" value="{$MONTH.nummeric}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][year]" value="{$YEAR}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment}">
			    {$ALL_DATA[DATA].capitalsourcecomment}
			  </td>

			  <td class="text-right of_number_to_be_evaluated">
{if $ALL_DATA[DATA].imported == 1}
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}">
                            <div class="input-group col-xs-12">
                              <input disabled type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[{$smarty.section.DATA.index}][amount]" required data-error="{#TEXT_306#}" value="{$ALL_DATA[DATA].amount}" style="text-align: right;">
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-euro"></span>
                              </span>
                            </div>
{else}
                            <div class="form-group" style="margin-bottom:0px;">
                              <div class="input-group col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[{$smarty.section.DATA.index}][amount]" required data-error="{#TEXT_306#}" value="{$ALL_DATA[DATA].amount}" style="text-align: right;">
                                <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-euro"></span>
                                </span>
                              </div>
                              <div class="help-block with-errors" style="margin-bottom:0px;"margin-top:0px;"></div>
                            </div>
{/if}
                          </td>
			</tr>
		{else}
			{assign var=CREDIT_EXISTS value=1}
		{/if}
	{/section}
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
			  <td class="text-left" style="vertical-align: middle;">
			    {if $NEW == 1 }<input type="hidden" name="all_data[{$smarty.section.DATA.index}][new]" value="1">
                            {/if}<input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].mcs_capitalsourceid}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][month]" value="{$MONTH.nummeric}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][year]" value="{$YEAR}">
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment}">
			    {$ALL_DATA[DATA].capitalsourcecomment}
			  </td>

			  <td class="text-right of_number_to_be_evaluated">
{if $ALL_DATA[DATA].imported == 1}
                            <input type="hidden" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}">
                            <div class="input-group col-xs-12">
                              <input disabled type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[{$smarty.section.DATA.index}][amount]" required data-error="{#TEXT_306#}" value="{$ALL_DATA[DATA].amount}" style="text-align: right;">
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-euro"></span>
                              </span>
                            </div>
{else}
                            <div class="form-group" style="margin-bottom:0px;">
                              <div class="input-group col-xs-12">
                                <input type="number" step="0.01" class="form-control" id="addmnfamount" name="all_data[{$smarty.section.DATA.index}][amount]" required data-error="{#TEXT_306#}" value="{$ALL_DATA[DATA].amount}" style="text-align: right;">
                                <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-euro"></span>
                                </span>
                              </div>
                              <div class="help-block with-errors" style="margin-bottom:0px;"margin-top:0px;"></div>
                            </div>
{/if}
                          </td>
			</tr>
		{/if}
	{/section}
                      </tbody>
                    </table>
                  </div>
                </div>
 	{/if}
              </div>
            </div>

        <div>
            <div class="form-group">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn"             onclick="btnEditMonthlysettlementCancel()"    >{#TEXT_315#}</button>
                <button type="submit" class="btn btn-success"                                               >{#TEXT_22#}</button>
              </div>  
            </div>  
        </div>
      </form>

      <script>
{literal}
        /************************************************************
         *
         * AJAX AND INIT
         *
         ************************************************************/
        function btnEditMonthlysettlementCancel() {
          window.close();
        }

        function ajaxEditMonthlysettlementSuccess(data) {
          opener.location = ENV_REFERER;
          window.close();
        }

        function ajaxEditMonthlysettlementError(data) {
          clearErrorDiv('editMonthlysettlementErrors');
          populateErrorDiv(data.responseText,'editMonthlysettlementErrorsGoHere','editMonthlysettlementErrors');
        }

<!--
        $("input.of_number_to_be_evaluated:contains('-')").addClass('red');
-->

        $('#edtmmsform').validator();
        $('#edtmmsform').ajaxForm({
            dataType: 'json',
            success: ajaxEditMonthlysettlementSuccess,
            error: ajaxEditMonthlysettlementError
        });
{/literal}
      </script>
{/if}
{$FOOTER}
