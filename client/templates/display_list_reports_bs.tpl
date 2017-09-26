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

        <div class="row">
    	  <div class="col-xs-12">


			<form action="#" method="get">
			{if {$ALL_YEARS|@count} gt 0}
			<table border="0" cellpadding=5>
				<tr>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="reports_year" size=1 onchange="Go(this.form.reports_year.options[this.form.reports_year.options.selectedIndex].value)">
					{section name=YEAR loop=$ALL_YEARS}
						<option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
					{/section}
					</select></td>
					{section name=MONTH loop=$ALL_MONTHS}
						<td class="contrastbgcolor">
							{if $ALL_MONTHS[MONTH].nummeric ne $SELECTED_MONTH}
								<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$ALL_MONTHS[MONTH].nummeric}&amp;reports_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a> 
							{else}
								{$ALL_MONTHS[MONTH].name}
							{/if}
						</td>
					{/section}
				</tr>
			</table>
			{else}
				<font color="#FF0000">{#TEXT_261#}</font>
			{/if}
			</form>



          </div>
        </div>

{if $REPORT eq 1}
        <div class="row">
    	  <div class="col-xs-2">
						{if $PREV_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$PREV_MONTH}&amp;reports_year={$PREV_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">&lt;&lt; {#TEXT_202#}</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	  <div class="col-xs-8 text-center">
    	 	<h4>{#TEXT_61#} {$MONTH.name} {$SELECTED_YEAR}</h4>
    	  </div>
    	  <div class="col-xs-2 text-right">
						{if $NEXT_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$NEXT_MONTH}&amp;reports_year={$NEXT_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">{#TEXT_201#} &gt;&gt;</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	</div>

			<div>
				<div class="row">
					<div class="col-xs-1"></div>
					<div class="col-xs-1"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_bookingdate&amp;reports_order={$NEWORDER}" >{#TEXT_16#}</a></div>
					<div class="col-xs-1"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_invoicedate&amp;reports_order={$NEWORDER}" >{#TEXT_17#}</a></div>
					<div class="col-xs-1"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_amount&amp;reports_order={$NEWORDER}"     >{#TEXT_18#}</a></div>
					<div class="col-xs-2"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=contractpartners_name&amp;reports_order={$NEWORDER}" >{#TEXT_2#}</a></div>
					<div class="col-xs-2"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=moneyflows_comment&amp;reports_order={$NEWORDER}"    >{#TEXT_21#}</a></div>
					<div class="col-xs-1"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=postingaccount_name&amp;reports_order={$NEWORDER}">{#TEXT_232#}</a></div>
					<div class="col-xs-2"><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$MONTH.nummeric}&amp;reports_year={$SELECTED_YEAR}&amp;reports_sortby=capitalsources_comment&amp;reports_order={$NEWORDER}">{#TEXT_19#}</a></div>
					<div class="col-xs-1">&nbsp</div>
					
				</div>
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
						<div class="row {if $smarty.section.DATA.index % 2  == 1} striped{/if}">
					{if $ALL_MONEYFLOW_DATA[DATA].has_moneyflow_split_entries eq 0}
							<div class="col-xs-1 text-right">
{if $ALL_MONEYFLOW_DATA[DATA].has_receipt eq 1}
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=show_moneyflow_receipt&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}','_blank','width=800,height=1000')">+</a>
{/if}
							</div>
							<div class="col-xs-1">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</div>
							<div class="col-xs-1">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</div>
							<div class="col-xs-1"><font {if $ALL_MONEYFLOW_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} {#CURRENCY#}</font></div>
							<div class="col-xs-2">{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</div>
							<div class="col-xs-2">{$ALL_MONEYFLOW_DATA[DATA].comment}</div>
							<div class="col-xs-1">{$ALL_MONEYFLOW_DATA[DATA].postingaccountname}</div>
							<div class="col-xs-2">{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</div>
{if $ALL_MONEYFLOW_DATA[DATA].owner == true }
							<div class="col-xs-1">
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=800')">{#TEXT_36#}</a>
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=120')">{#TEXT_37#}</a>
							</div>
{/if}
					{else}
							<div class="col-xs-1 text-right">
{if $ALL_MONEYFLOW_DATA[DATA].has_receipt eq 1}
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=show_moneyflow_receipt&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}','_blank','width=800,height=1000')">+</a>
{/if}
							</div>
							<div class="col-xs-1">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</div>
							<div class="col-xs-1">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</div>
							<div class="col-xs-1">
								<div class="col-xs-6"><font {if $ALL_MONEYFLOW_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} {#CURRENCY#}</font></div>
								<div class="col-xs-6">
{section name=DATA2 loop=$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries}
									<div class="row">
										<div class="col-xs-12">
											<font {if $ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].amount < 0}color="red"{else}color="black"{/if}>{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].amount|number_format} {#CURRENCY#}</font>
										</div>
									</div>
{/section}
								</div>
							</div>
							
							<div class="col-xs-2">{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</div>
							
							<div class="col-xs-2">
{section name=DATA2 loop=$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries}
								<div class="row">
									<div class="col-xs-12">{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].comment}</div>
								</div>
{/section}
							</div>

							<div class="col-xs-1">
{section name=DATA2 loop=$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries}
								<div class="row">
									<div class="col-xs-12">{$ALL_MONEYFLOW_DATA[DATA].moneyflow_split_entries[DATA2].postingaccountname}</div>
								</div>
{/section}
							</div>
								
							<div class="col-xs-2">{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</div>
{if $ALL_MONEYFLOW_DATA[DATA].owner == true }
							<div class="col-xs-1">
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=800')">{#TEXT_36#}</a>
								<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&amp;moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&amp;sr=1','_blank','width=1024,height=120')">{#TEXT_37#}</a>
							</div>
							{/if}
					{/if}
						</div>	
				{/section}
				<div class="row">
					<div class="col-xs-1"></div>
					<div class="col-xs-1"></div>
					<div class="col-xs-1">&sum;</div>
					<div class="col-xs-1"><font {if $MOVEMENT < 0}color="red"{else}color="black"{/if}><u>{$MOVEMENT|number_format} {#CURRENCY#}</u></font></div>
				</div>
			</div>




        <div class="row">
    	  <div class="col-xs-2">
						{if $PREV_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$PREV_MONTH}&amp;reports_year={$PREV_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">&lt;&lt; {#TEXT_202#}</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	  <div class="col-xs-8">
    	 	&nbsp;
    	  </div>
    	  <div class="col-xs-2">
						{if $NEXT_LINK == true }
							<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$NEXT_MONTH}&amp;reports_year={$NEXT_YEAR}{if $SORTBY != ''}&amp;reports_sortby={$SORTBY}&amp;reports_order={$ORDER}{/if}">{#TEXT_201#} &gt;&gt;</a>
						{else}
							&nbsp;
						{/if}
    	  </div>
    	</div>





			<br>
			{if ( $SUMMARY_DATA != '' || $LIABILITIES_SUMMARY_DATA != '' ) }
			<hr align="center" width="830">
			<h1>{#TEXT_68#}</h1>
			<table border=0 cellpadding=2>
				<tr>
				{if $SUMMARY_DATA != ''}
					<th><h2>{#TEXT_280#}</h2></th>
				{/if}
				</tr>
				<tr>
				{if $SUMMARY_DATA != ''}
				<td valign="top" align="center">
						<table border=0 cellpadding=2>
							<tr>
								<th width="110">{#TEXT_30#}</th>
								<th width="60">{#TEXT_31#}</th>
								<th width="150">{#TEXT_21#}</th>
								<th width="80">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_63#}</th>
								{/if}
								<th width="80">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_65#}</th>
								{else}
								<th width="80">{#TEXT_288#}</th>
								<th width="110">{#TEXT_289#}</th>
								{/if}
							</tr>
							{section name=DATA loop=$SUMMARY_DATA}
								<tr>
									<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].typecomment}</td>
									<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].statecomment}</td>
									<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].comment}</td>
									<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].lastamount < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$SUMMARY_DATA[DATA])}
											<font {if $SUMMARY_DATA[DATA].fixamount  < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].calcamount < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$SUMMARY_DATA[DATA].fixamount y=$SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											<font {if $CAPITALSOURCE_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{else}
										<td align="right" class="contrastbgcolor">
										<font {if $SUMMARY_DATA[DATA].amount_current  < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}</font>
										</td>
										<td align="right" class="contrastbgcolor">
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
									<td></td>
									<td></td>
									<td align="right">&sum;</td>
									<td align="right" class="contrastbgcolor"><font {if $LASTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LASTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td align="right" class="contrastbgcolor"><font {if $FIXAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$FIXAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $MON_CALCAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$FIXAMOUNT y=$MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></font></td>
									{else}
									<td align="right" class="contrastbgcolor"><font {if $CURRENTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$CURRENTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
								</tr>
						</table>
						<br>
						<table border=0 cellpadding=2>
							<tr>
								<th></th>
								<th>{#TEXT_56#}</th>
								<th>{#TEXT_57#}</th>
							</tr>
							{if !$FIRSTAMOUNT}{assign var="FIRSTAMOUNT" value="0"}{/if}
							{if $MONTHLYSETTLEMENT_EXISTS == true}
							<tr>
								<th align="right">{#TEXT_66#}</th>
								{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=MON_FIXEDTURNOVER}
								<td align="right" class="contrastbgcolor"><font {if $MON_FIXEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$MON_FIXEDTURNOVER|number_format} {#CURRENCY#}</font></td>
								{math equation="y-x" x=$FIRSTAMOUNT y=$FIXAMOUNT assign=YEA_FIXEDTURNOVER}
								<td align="right" class="contrastbgcolor"><font {if $YEA_FIXEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$YEA_FIXEDTURNOVER|number_format} {#CURRENCY#}</font></td>
							</tr>
							{/if}
							<tr>
								<th align="right">{#TEXT_67#}</th>
								<td align="right" class="contrastbgcolor"><font {if $MON_CALCULATEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$MON_CALCULATEDTURNOVER|number_format} {#CURRENCY#}</font></td>
								<td align="right" class="contrastbgcolor"><font {if $YEA_CALCULATEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$YEA_CALCULATEDTURNOVER|number_format} {#CURRENCY#}</font></td>
							</tr>
							{if $MONTHLYSETTLEMENT_EXISTS == true}
							<tr>
								<th align="right">{#TEXT_65#}</th>
								<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</font></td>
								{math equation="x - y" x=$YEA_FIXEDTURNOVER y=$YEA_CALCULATEDTURNOVER assign=YEA_DIFFERENCE}
								<td align="right" class="contrastbgcolor"><font {if $YEA_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$YEA_DIFFERENCE|number_format} {#CURRENCY#}</font></td>
							</tr>
							{/if}
						</table>
					</td>
				{/if}
				{if $LIABILITIES_SUMMARY_DATA != ''}
				</tr>
				<tr>
					<th><br><h2>{#TEXT_281#}</h2></th>
				</tr>
				<tr>
					<td valign="top" align="center">
						<table border=0 cellpadding=2>
							<tr>
								<th width="110">{#TEXT_30#}</th>
								<th width="60">{#TEXT_31#}</th>
								<th width="150">{#TEXT_21#}</th>
								<th width="80">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_63#}</th>
								{/if}
								<th width="80">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_65#}</th>
								{else}
								<th width="80">{#TEXT_288#}</th>
								<th width="110">{#TEXT_289#}</th>
								{/if}
							</tr>
							{section name=DATA loop=$LIABILITIES_SUMMARY_DATA}
								<tr>
									<td class="contrastbgcolor">{$LIABILITIES_SUMMARY_DATA[DATA].typecomment}</td>
									<td class="contrastbgcolor">{$LIABILITIES_SUMMARY_DATA[DATA].statecomment}</td>
									<td class="contrastbgcolor">{$LIABILITIES_SUMMARY_DATA[DATA].comment}</td>
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_SUMMARY_DATA[DATA].lastamount < 0}color="red"{else}color="black"{/if}>{$LIABILITIES_SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$LIABILITIES_SUMMARY_DATA[DATA])}
											<font {if $LIABILITIES_SUMMARY_DATA[DATA].fixamount  < 0}color="red"{else}color="black"{/if}>{$LIABILITIES_SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_SUMMARY_DATA[DATA].calcamount < 0}color="red"{else}color="black"{/if}>{$LIABILITIES_SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$LIABILITIES_SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$LIABILITIES_SUMMARY_DATA[DATA].fixamount y=$LIABILITIES_SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											<font {if $CAPITALSOURCE_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{else}
										<td align="right" class="contrastbgcolor">
										<font {if $LIABILITIES_SUMMARY_DATA[DATA].amount_current  < 0}color="red"{else}color="black"{/if}>{$LIABILITIES_SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}</font>
										</td>
										<td align="right" class="contrastbgcolor">
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
									<td></td>
									<td></td>
									<td align="right">&sum;</td>
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_LASTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LIABILITIES_LASTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_FIXAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LIABILITIES_FIXAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_MON_CALCAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LIABILITIES_MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$LIABILITIES_FIXAMOUNT y=$LIABILITIES_MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></font></td>
									{else}
									<td align="right" class="contrastbgcolor"><font {if $LIABILITIES_CURRENTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LIABILITIES_CURRENTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
								</tr>
						</table>
					</td>
				{/if}
				{if $CREDITS_SUMMARY_DATA != ''}
				</tr>
				<tr>
					<th><br><h2>{#TEXT_294#}</h2></th>
				</tr>
				<tr>
					<td valign="top" align="center">
						<table border=0 cellpadding=2>
							<tr>
								<th width="110">{#TEXT_30#}</th>
								<th width="60">{#TEXT_31#}</th>
								<th width="150">{#TEXT_21#}</th>
								<th width="80">{#TEXT_62#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_63#}</th>
								{/if}
								<th width="80">{#TEXT_64#}</th>
								{if $MONTHLYSETTLEMENT_EXISTS == true}
								<th width="80">{#TEXT_297#}</th>
								{else}
								<th width="80">{#TEXT_288#}</th>
								<th width="110">{#TEXT_289#}</th>
								{/if}
							</tr>
							{section name=DATA loop=$CREDITS_SUMMARY_DATA}
								<tr>
									<td class="contrastbgcolor">{$CREDITS_SUMMARY_DATA[DATA].typecomment}</td>
									<td class="contrastbgcolor">{$CREDITS_SUMMARY_DATA[DATA].statecomment}</td>
									<td class="contrastbgcolor">{$CREDITS_SUMMARY_DATA[DATA].comment}</td>
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_SUMMARY_DATA[DATA].lastamount < 0}color="red"{else}color="black"{/if}>{$CREDITS_SUMMARY_DATA[DATA].lastamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$CREDITS_SUMMARY_DATA[DATA])}
											<font {if $CREDITS_SUMMARY_DATA[DATA].fixamount  < 0}color="red"{else}color="black"{/if}>{$CREDITS_SUMMARY_DATA[DATA].fixamount|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_SUMMARY_DATA[DATA].calcamount < 0}color="red"{else}color="black"{/if}>{$CREDITS_SUMMARY_DATA[DATA].calcamount|number_format} {#CURRENCY#}</font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
										<td align="right" class="contrastbgcolor">
										{if array_key_exists('fixamount',$CREDITS_SUMMARY_DATA[DATA])}
											{math equation="x - y" x=$CREDITS_SUMMARY_DATA[DATA].fixamount y=$CREDITS_SUMMARY_DATA[DATA].calcamount assign=CAPITALSOURCE_DIFFERENCE}
									
											<font {if $CAPITALSOURCE_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$CAPITALSOURCE_DIFFERENCE|number_format} {#CURRENCY#}</font>
										{/if}
										</td>
									{else}
										<td align="right" class="contrastbgcolor">
										<font {if $CREDITS_SUMMARY_DATA[DATA].amount_current  < 0}color="red"{else}color="black"{/if}>{$CREDITS_SUMMARY_DATA[DATA].amount_current|number_format} {#CURRENCY#}</font>
										</td>
										<td align="right" class="contrastbgcolor">
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
									<td></td>
									<td></td>
									<td align="right">&sum;</td>
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_LASTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$CREDITS_LASTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_FIXAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$CREDITS_FIXAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_MON_CALCAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$CREDITS_MON_CALCAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{if $MONTHLYSETTLEMENT_EXISTS == true}
									{math equation="x - y" x=$CREDITS_FIXAMOUNT y=$CREDITS_MON_CALCAMOUNT assign=MON_DIFFERENCE}
									<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}><u>{$MON_DIFFERENCE|number_format} {#CURRENCY#}</u></font></td>
									{else}
									<td align="right" class="contrastbgcolor"><font {if $CREDITS_CURRENTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$CREDITS_CURRENTAMOUNT|number_format} {#CURRENCY#}</u></font></td>
									{/if}
								</tr>
						</table>
					</td>
				{/if}
				</tr>
			</table>
			{/if}

{/if}
</div>
{$FOOTER}
