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
{section name=DATA loop=$ALL_DATA}
              {math equation="x * y" x=$ALL_DATA[DATA].amount y=$ALL_DATA[DATA].price assign=SUM_PRICE}

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
            </tbody>
          </table>
{/if}
        </div>
      </div>
{$FOOTER}
