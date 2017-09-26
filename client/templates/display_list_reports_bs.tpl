{$HEADER}

{literal}
<script type="text/javascript">
<!--
function Go(x)
{
 if(x == "nothing")
 {
   document.reports_year.reset();
   document.reports_year.elements[0].blur();
   return;
 }
 else
  {
   location.href = x;
   document.reports_year.reset();
   document.reports_year.elements[0].blur();
  }
}
//-->
</script>
{/literal}

      <div>
        <div class="text-center">
          <h4>{#TEXT_5#}</h4>
        </div>


        <div class="text-center">
          <form action="#" method="get" class="form-inline">
            <table style="margin: 0 auto;">
              <tr>
{if {$ALL_YEARS|@count} gt 0}
                <td class="text-right">
                  <select class="form-control" name="reports_year" onchange="Go(this.form.reports_year.options[this.form.reports_year.options.selectedIndex].value)">
{section name=YEAR loop=$ALL_YEARS}
                    <option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
{/section}
                  </select>
                  &nbsp;
                </td>
                <td>
                  <ul class="pagination">
{section name=MONTH loop=$ALL_MONTHS}
{if $ALL_MONTHS[MONTH].nummeric ne $SELECTED_MONTH}
                    <li><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$ALL_MONTHS[MONTH].nummeric}&amp;reports_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a></li> 
{else}
                    <li class="active"><a href="#">{$ALL_MONTHS[MONTH].name}</a></li>
{/if}
{/section}
                  </ul>
                </td>
{else}
                <td class="red">{#TEXT_261#}</td>
{/if}
              </tr>
            </table>  
          </form>
        </div>

{if $REPORT eq 1}
        <div class="row">
    	  <div class="col-xs-6">
						{if $PREV_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$PREV_MONTH}&amp;reports_year={$PREV_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">&lt;&lt; {#TEXT_202#}</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	  <div class="col-xs-6 text-right">
						{if $NEXT_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$NEXT_MONTH}&amp;reports_year={$NEXT_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">{#TEXT_201#} &gt;&gt;</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	</div>
    	
        <div class="row">
    	  <div class="col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading text-center">
		    <h4>{#TEXT_61#} {$MONTH.name} {$SELECTED_YEAR}</h4>
		  </div>
		  <div class="panel-body">
			<table class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="text-center"></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_bookingdate&amp;reports_order={$NEWORDER}" >{#TEXT_16#}</a></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_invoicedate&amp;reports_order={$NEWORDER}" >{#TEXT_17#}</a></th>
					<th class="text-center" colspan="2"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_amount&amp;reports_order={$NEWORDER}"     >{#TEXT_18#}</a></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=contractpartners_name&amp;reports_order={$NEWORDER}" >{#TEXT_2#}</a></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_comment&amp;reports_order={$NEWORDER}"    >{#TEXT_21#}</a></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=postingaccount_name&amp;reports_order={$NEWORDER}">{#TEXT_232#}</a></th>
					<th class="text-center"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=capitalsources_comment&amp;reports_order={$NEWORDER}">{#TEXT_19#}</a></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
				</tr>
       		                </thead>
       		                
				<tbody>
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
					{if $ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries eq 0}
						<tr>
							<td class="text-center">{if $ALL_MONEYFLOW_DATA[DATA].has_receipt eq 1}
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=show_moneyflow_receipt&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}','_blank','width=800,height=1000')">+</a>
							{/if}</td>
							<td class="text-center">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</td>
							<td class="text-center">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</td>
							<td class="text-right of_number_to_be_evaluated" colspan="2">{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
							<td>{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</td>
							<td>{$ALL_MONEYFLOW_DATA[DATA].comment}</td>
							<td>{$ALL_MONEYFLOW_DATA[DATA].postingaccountname}</td>
							<td>{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</td>
							{if $ALL_MONEYFLOW_DATA[DATA].owner == true }
								<td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=800')">{#TEXT_36#}</a></td>
								<td class="text-center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=120')">{#TEXT_37#}</a></td>
							{else}
								<td colspan="2"></td>
							{/if}
						</tr>
					{else}
						{section name=DATA2 loop=$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries}
							<tr>
							{if $smarty.section.DATA2.first eq true}
								<td class="text-center" rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{if $ALL_MONEYFLOW_DATA[DATA].has_receipt eq 1}
									<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=show_moneyflow_receipt&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}','_blank','width=800,height=1000')">+</a>
								{/if}</td>
								<td class="text-center" rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{$DATA2}  {$ALL_MONEYFLOW_DATA[DATA].bookingdate}</td>
								<td class="text-center" rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</td>
								<td class="text-right of_number_to_be_evaluated" rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} {#CURRENCY#}</td>
							{/if}
							<td class="text-right of_number_to_be_evaluated">{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].amount|number_format} {#CURRENCY#}</td>
							{if $smarty.section.DATA2.first eq true}
								<td rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</td>
							{/if}
							<td>{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].comment}</td>
							<td>{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].postingaccountname}</td>
							{if $smarty.section.DATA2.first eq true}
								<td rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}>{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</td>
								{if $ALL_MONEYFLOW_DATA[DATA].owner == true }
									<td rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=800')">{#TEXT_36#}</a></td>
									<td rowspan={$ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries}><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=120')">{#TEXT_37#}</a></td>
								{else}
									<td colspan="2"></td>
								{/if}
							{/if}
							</tr>							
						{/section}
					{/if}
				{/section}
				<tr>
					<td class="text-right" colspan="3">&sum;</td>
					<td class="text-right of_number_to_be_evaluated" colspan="2"><u>{$MOVEMENT|number_format} {#CURRENCY#}</u></td>
					<td class="text-right" colspan="6"></td>
				</tr>
				</tbody>
			</table>
			</div>
			</div>
          </div>
        </div>

        <div class="row">
    	  <div class="col-xs-6">
						{if $PREV_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$PREV_MONTH}&amp;reports_year={$PREV_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">&lt;&lt; {#TEXT_202#}</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	  <div class="col-xs-6 text-right">
						{if $NEXT_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$NEXT_MONTH}&amp;reports_year={$NEXT_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">{#TEXT_201#} &gt;&gt;</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	</div>

	<br>

{if ( $SUMMARY_DATA != '' || $LIABILITIES_SUMMARY_DATA != '' ) }
        <div class="row">
          <div class="col-xs-12 text-center"><h1>{#TEXT_68#}</h1></div>
        </div>
	<br>
{if $SUMMARY_DATA != ''}
        <div class="row">
          <div class="col-xs-8 col-xs-push-2">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_280#}</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
						<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
							<col style="width:15%">
							<col style="width:10%">
							<col style="width:20%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:15%">
						        <thead>
							<tr>
								<th class="text-center">{#TEXT_30#}</th>
								<th class="text-center">{#TEXT_31#}</th>
								<th class="text-center">{#TEXT_21#}</th>
								<th class="text-center">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_63#}</th>
								{/if}
								<th class="text-center">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_65#}</th>
								{else}
								<th class="text-center">{#TEXT_288#}</th>
								<th class="text-center">{#TEXT_289#}</th>
								{/if}
							</tr>
							</thead>
							<tbody>
							{section name=DATA loop=$SUMMARY_DATA}
								<tr>
									<td class="text-left">{$SUMMARY_DATA[DATA].typecomment}</td>
									<td class="text-left">{$SUMMARY_DATA[DATA].statecomment}</td>
									<td class="text-left">{$SUMMARY_DATA[DATA].comment}</td>
									<td class="text-right of_number_to_be_evaluated">{$SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right  of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$SUMMARY_DATA[DATA])}
											{$SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}
										{/if}
										</td>
									{/if}
									<td class="text-right of_number_to_be_evaluated">{$SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$SUMMARY_DATA[DATA].fixamount y=$SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}
										{/if}
										</td>
									{else}
										<td class="text-right of_number_to_be_evaluated">
										{$SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}
										</td>
										<td class="text-right">
										{if array_key_exists('amount_current_state',$SUMMARY_DATA[DATA])}
											{$SUMMARY_DATA[DATA].amount_current_state}
										{else}
											{#TEXT_290#}
										{/if}
										</td>
									{/if}
								</tr>
							{/section}
								<tr>
									<td class="text-right" colspan="3">&sum;</td>
									<td class="text-right of_number_to_be_evaluated"><u>{$LASTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td class="text-right of_number_to_be_evaluated"><u>{$FIXAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
									<td class="text-right of_number_to_be_evaluated"><u>{$MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$FIXAMOUNT y=$MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td class="text-right of_number_to_be_evaluated"><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></td>
									{else}
									<td class="text-right of_number_to_be_evaluated"><u>{$CURRENTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
								</tr>
							</tbody>
						</table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-6 col-xs-offset-3">
						<table class="table table-striped table-bordered table-hover">
						        <thead>
							<tr>
								<th class="text-center"></th>
								<th class="text-center">{#TEXT_56#}</th>
								<th class="text-center">{#TEXT_57#}</th>
							</tr>
							</thead>
							<tbody>
							{if !$FIRSTAMOUNT}{assign var="FIRSTAMOUNT" value="0"}{/if}
							{if $MONTHLYSETTLEMENT_EXISTS == true}
							<tr>
								<th class="text-right">{#TEXT_66#}</th>
								{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=MON_FIXEDTURNOVER}
								<td class="text-right of_number_to_be_evaluated">{$MON_FIXEDTURNOVER|number_format} {#CURRENCY#}</td>
								{math equation="y-x" x=$FIRSTAMOUNT y=$FIXAMOUNT assign=YEA_FIXEDTURNOVER}
								<td class="text-right of_number_to_be_evaluated">{$YEA_FIXEDTURNOVER|number_format} {#CURRENCY#}</td>
							</tr>
							{/if}
							<tr>
								<th class="text-right">{#TEXT_67#}</th>
								<td class="text-right of_number_to_be_evaluated">{$MON_CALCULATEDTURNOVER|number_format} {#CURRENCY#}</td>
								<td class="text-right of_number_to_be_evaluated">{$YEA_CALCULATEDTURNOVER|number_format} {#CURRENCY#}</td>
							</tr>
							{if $MONTHLYSETTLEMENT_EXISTS == true}
							<tr>
								<th class="text-right">{#TEXT_65#}</th>
								<td class="text-right of_number_to_be_evaluated">{$MON_DIFFERENCE|number_format} {#CURRENCY#}</td>
								{math equation="x - y" x=$YEA_FIXEDTURNOVER y=$YEA_CALCULATEDTURNOVER assign=YEA_DIFFERENCE}
								<td class="text-right of_number_to_be_evaluated">{$YEA_DIFFERENCE|number_format} {#CURRENCY#}</td>
							</tr>
							{/if}
							</tbody>
						</table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
{/if}
{if $LIABILITIES_SUMMARY_DATA != ''}
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <div class="row">
          <div class="col-xs-8 col-xs-push-2">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_281#}</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
						<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
							<col style="width:15%">
							<col style="width:10%">
							<col style="width:20%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:15%">
						        <thead>
							<tr>
								<th class="text-center">{#TEXT_30#}</th>
								<th class="text-center">{#TEXT_31#}</th>
								<th class="text-center">{#TEXT_21#}</th>
								<th class="text-center">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_63#}</th>
								{/if}
								<th class="text-center">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_65#}</th>
								{else}
								<th class="text-center">{#TEXT_288#}</th>
								<th class="text-center">{#TEXT_289#}</th>
								{/if}
							</tr>
							</thead>
							<tbody>
							{section name=DATA loop=$LIABILITIES_SUMMARY_DATA}
								<tr>
									<td class="text-left">{$LIABILITIES_SUMMARY_DATA[DATA].typecomment}</td>
									<td class="text-left">{$LIABILITIES_SUMMARY_DATA[DATA].statecomment}</td>
									<td class="text-left">{$LIABILITIES_SUMMARY_DATA[DATA].comment}</td>
									<td class="text-right of_number_to_be_evaluated">{$LIABILITIES_SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$LIABILITIES_SUMMARY_DATA[DATA])}
											{$LIABILITIES_SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}
										{/if}
										</td>
									{/if}
									<td class="text-right of_number_to_be_evaluated">{$LIABILITIES_SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$LIABILITIES_SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$LIABILITIES_SUMMARY_DATA[DATA].fixamount y=$LIABILITIES_SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}
										{/if}
										</td>
									{else}
										<td class="text-right of_number_to_be_evaluated">
										{$LIABILITIES_SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}
										</td>
										<td class="text-right">
										{if array_key_exists('amount_current_state',$LIABILITIES_SUMMARY_DATA[DATA])}
											{$LIABILITIES_SUMMARY_DATA[DATA].amount_current_state}
										{else}
											{#TEXT_290#}
										{/if}
										</td>
									{/if}
								</tr>
							{/section}
								<tr>
									<td class="text-right" colspan="3">&sum;</td>
									<td class="text-right of_number_to_be_evaluated"><u>{$LIABILITIES_LASTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td class="text-right of_number_to_be_evaluated"><u>{$LIABILITIES_FIXAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
									<td class="text-right of_number_to_be_evaluated"><u>{$LIABILITIES_MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$LIABILITIES_FIXAMOUNT y=$LIABILITIES_MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td class="text-right of_number_to_be_evaluated"><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></td>
									{else}
									<td class="text-right of_number_to_be_evaluated"><u>{$LIABILITIES_CURRENTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
								</tr>
								</tbody>
						</table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
{/if}
{if $CREDITS_SUMMARY_DATA != ''}
        <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
        <div class="row">
          <div class="col-xs-8 col-xs-push-2">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                <h4>{#TEXT_294#}</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
						<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
							<col style="width:15%">
							<col style="width:10%">
							<col style="width:20%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:15%">
						        <thead>
							<tr>
								<th class="text-center">{#TEXT_30#}</th>
								<th class="text-center">{#TEXT_31#}</th>
								<th class="text-center">{#TEXT_21#}</th>
								<th class="text-center">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_63#}</th>
								{/if}
								<th class="text-center">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th class="text-center">{#TEXT_297#}</th>
								{else}
								<th class="text-center">{#TEXT_288#}</th>
								<th class="text-center">{#TEXT_289#}</th>
								{/if}
							</tr>
							</thead>
							<tbody>
							{section name=DATA loop=$CREDITS_SUMMARY_DATA}
								<tr>
									<td class="text-left">{$CREDITS_SUMMARY_DATA[DATA].typecomment}</td>
									<td class="text-left">{$CREDITS_SUMMARY_DATA[DATA].statecomment}</td>
									<td class="text-left">{$CREDITS_SUMMARY_DATA[DATA].comment}</td>
									<td class="text-right of_number_to_be_evaluated">{$CREDITS_SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$CREDITS_SUMMARY_DATA[DATA])}
											{$CREDITS_SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}
										{/if}
										</td>
									{/if}
									<td class="text-right of_number_to_be_evaluated">{$CREDITS_SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td class="text-right of_number_to_be_evaluated">
										{if array_key_exists('fixamount',$CREDITS_SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$CREDITS_SUMMARY_DATA[DATA].fixamount y=$CREDITS_SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}
										{/if}
										</td>
									{else}
										<td class="text-right of_number_to_be_evaluated">
										{$CREDITS_SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}
										</td>
										<td class="text-right">
										{if array_key_exists('amount_current_state',$CREDITS_SUMMARY_DATA[DATA])}
											{$CREDITS_SUMMARY_DATA[DATA].amount_current_state}
										{else}
											{#TEXT_290#}
										{/if}
										</td>
									{/if}
								</tr>
							{/section}
								<tr>
									<td class="text-right" colspan="3">&sum;</td>
									<td class="text-right of_number_to_be_evaluated"><u>{$CREDITS_LASTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td class="text-right of_number_to_be_evaluated"><u>{$CREDITS_FIXAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
									<td class="text-right of_number_to_be_evaluated"><u>{$CREDITS_MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$CREDITS_FIXAMOUNT y=$CREDITS_MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td class="text-right of_number_to_be_evaluated"><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></td>
									{else}
									<td class="text-right of_number_to_be_evaluated"><u>{$CREDITS_CURRENTAMOUNT|number_format} {#CURRENCY#}</u></td>
									{/if}
								</tr>
								</tbody>
						</table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
{/if}
{/if}
{/if}
      </div>
      
<script>
  $("td.of_number_to_be_evaluated:contains('-')").addClass('red');
</script>
{$FOOTER}
