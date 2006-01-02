			<br />
			<h1>money flow for {$MONTH.name} {$YEAR}</h1>
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_bookingdate&reports_order={$ORDER}">bookingdate</a></th>
					<th width="9%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_invoicedate&reports_order={$ORDER}">invoicedate</a></th>
					<th width="10%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_amount&reports_order={$ORDER}">amount</a></th>
					<th width="16%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=contractpartners_name&reports_order={$ORDER}">contract partner</a></th>
					<th ><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_comment&reports_order={$ORDER}">comment</a></th>
					<th width="22%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=capitalsources_comment&reports_order={$ORDER}">capital source</a></th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
					<tr>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $ALL_MONEYFLOW_DATA[DATA].amount < 0}color="red"{/if}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} EUR</font></td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].comment}</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&id={$ALL_MONEYFLOW_DATA[DATA].id}&sr=1','_blank','width=1024,height=80')">edit</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&id={$ALL_MONEYFLOW_DATA[DATA].id}&sr=1','_blank','width=1024,height=80')">delete</a></td>
					</tr>
				{/section}
				{math equation="y-x" x=$LASTAMOUNT y=$MON_CALCAMOUNT assign=MON_CALCULATEDTURNOVER}
				<tr>
					<td></td>
					<td align="right">&sum;</td>
					<td align="right" class="contrastbgcolor"><font {if $MON_CALCULATEDTURNOVER < 0}color="red"{/if}><u>{$MON_CALCULATEDTURNOVER|number_format} EUR</u></font></td>
			</table>
			<br />
			<hr>
			<h1>Summary</h1>
			<table border=0 cellpadding=2>
				<tr>
					<th>type</th>
					<th>state</th>
					<th>comment</th>
					<th>amount begin</th>
					{if $MONTHLYSETTLEMENT_EXISTS == true}
					<th>fixed end</th>
					{/if}
					<th>calculated end</th>
					{if $MONTHLYSETTLEMENT_EXISTS == true}
					<th>difference</th>
					{/if}
				</tr>
				{section name=DATA loop=$SUMMARY_DATA}
					<tr>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].type}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].state}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].comment}</td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].lastamount < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].lastamount|number_format} EUR</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].fixamount  < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].fixamount|number_format} EUR</font></td>
						{/if}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].calcamount < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].calcamount|number_format} EUR</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].difference < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].difference|number_format} EUR</font></td>
						{/if}
					</tr>
				{/section}
					<tr>
						<td></td>
						<td></td>
						<td align="right">&sum;</td>
						<td align="right" class="contrastbgcolor"><font {if $LASTAMOUNT < 0}color="red"{/if}><u>{$LASTAMOUNT|number_format} EUR</u></font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $FIXAMOUNT < 0}color="red"{/if}><u>{$FIXAMOUNT|number_format} EUR</u></font></td>
						{/if}
						<td align="right" class="contrastbgcolor"><font {if $MON_CALCAMOUNT < 0}color="red"{/if}><u>{$MON_CALCAMOUNT|number_format} EUR</u></font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						{math equation="x - y" x=$FIXAMOUNT y=$MON_CALCAMOUNT assign=MON_DIFFERENCE}
						<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{/if}><u>{$MON_DIFFERENCE|number_format} EUR</u></font></td>
						{/if}
					</tr>
			</table>
			<br />
			<table border=0 cellpadding=2>
				<tr>
					<th></th>
					<th>month</th>
					<th>year</th>
				</tr>
				{if !$FIRSTAMOUNT}{assign var="FIRSTAMOUNT" value="0"}{/if}
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">fixed turnover</th>
					{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=MON_FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $MON_FIXEDTURNOVER < 0}color="red"{/if}>{$MON_FIXEDTURNOVER|number_format} EUR</font></td>
					{math equation="y-x" x=$FIRSTAMOUNT y=$FIXAMOUNT assign=YEA_FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $YEA_FIXEDTURNOVER < 0}color="red"{/if}>{$YEA_FIXEDTURNOVER|number_format} EUR</font></td>
				</tr>
				{/if}
				<tr>
					<th align="right">calculated turnover</th>
					<td align="right" class="contrastbgcolor"><font {if $MON_CALCULATEDTURNOVER < 0}color="red"{/if}>{$MON_CALCULATEDTURNOVER|number_format} EUR</font></td>
					<td align="right" class="contrastbgcolor"><font {if $YEA_CALCULATEDTURNOVER < 0}color="red"{/if}>{$YEA_CALCULATEDTURNOVER|number_format} EUR</font></td>
				</tr>
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">difference</th>
					<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{/if}>{$MON_DIFFERENCE|number_format} EUR</font></td>
					{math equation="x - y" x=$YEA_FIXEDTURNOVER y=$YEA_CALCULATEDTURNOVER assign=YEA_DIFFERENCE}
					<td align="right" class="contrastbgcolor"><font {if $YEA_DIFFERENCE < 0}color="red"{/if}>{$YEA_DIFFERENCE|number_format} EUR</font></td>
				</tr>
				{/if}
			</table>
