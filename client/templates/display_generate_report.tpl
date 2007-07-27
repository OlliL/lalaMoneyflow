			<br />
			<h1>{$TEXT_61} {$MONTH.name} {$YEAR}</h1>
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_bookingdate&reports_order={$ORDER}" >{$TEXT_16}</a></th>
					<th width="9%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_invoicedate&reports_order={$ORDER}" >{$TEXT_17}</a></th>
					<th width="10%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_amount&reports_order={$ORDER}"     >{$TEXT_18}</a></th>
					<th width="16%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=contractpartners_name&reports_order={$ORDER}" >{$TEXT_2}</a></th>
					<th ><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=moneyflows_comment&reports_order={$ORDER}"               >{$TEXT_21}</a></th>
					<th width="22%"><a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$MONTH.nummeric}&reports_year={$YEAR}&reports_sortby=capitalsources_comment&reports_order={$ORDER}">{$TEXT_19}</a></th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{assign var=MON_CALCULATEDTURNOVER value=0}
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
					<tr>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $ALL_MONEYFLOW_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].contractpartnername}</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].comment}</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].capitalsourcecomment}</td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&sr=1','_blank','width=1024,height=80')">{$TEXT_36}</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&moneyflowid={$ALL_MONEYFLOW_DATA[DATA].moneyflowid}&sr=1','_blank','width=1024,height=80')">{$TEXT_37}</a></td>
					</tr>
					{math equation="x + y" x=$ALL_MONEYFLOW_DATA[DATA].amount y=$MON_CALCULATEDTURNOVER assign=MON_CALCULATEDTURNOVER}
				{/section}
				<tr>
					<td></td>
					<td align="right">&sum;</td>
					<td align="right" class="contrastbgcolor"><font {if $MON_CALCULATEDTURNOVER < 0}color="red"{else}color="black"{/if}><u>{$MON_CALCULATEDTURNOVER|number_format} {$CURRENCY}</u></font></td>
			</table>
			<br />
			<hr align="center" width="830"/>
			<h1>{$TEXT_68}</h1>
			<table border=0 cellpadding=2>
				<tr>
					<th>{$TEXT_30}</th>
					<th>{$TEXT_31}</th>
					<th>{$TEXT_21}</th>
					<th width="80">{$TEXT_62}</th>
					{if $MONTHLYSETTLEMENT_EXISTS == true}
					<th width="80">{$TEXT_63}</th>
					{/if}
					<th width="80">{$TEXT_64}</th>
					{if $MONTHLYSETTLEMENT_EXISTS == true}
					<th width="80">{$TEXT_65}</th>
					{/if}
				</tr>
				{section name=DATA loop=$SUMMARY_DATA}
					<tr>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].typecomment}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].statecomment}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].comment}</td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].lastamount < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].lastamount|number_format} {$CURRENCY}</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].fixamount  < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].fixamount|number_format} {$CURRENCY}</font></td>
						{/if}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].calcamount < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].calcamount|number_format} {$CURRENCY}</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].difference < 0}color="red"{else}color="black"{/if}>{$SUMMARY_DATA[DATA].difference|number_format} {$CURRENCY}</font></td>
						{/if}
					</tr>
				{/section}
					<tr>
						<td></td>
						<td></td>
						<td align="right">&sum;</td>
						<td align="right" class="contrastbgcolor"><font {if $LASTAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$LASTAMOUNT|number_format} {$CURRENCY}</u></font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><font {if $FIXAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$FIXAMOUNT|number_format} {$CURRENCY}</u></font></td>
						{/if}
						<td align="right" class="contrastbgcolor"><font {if $MON_CALCAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$MON_CALCAMOUNT|number_format} {$CURRENCY}</u></font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						{math equation="x - y" x=$FIXAMOUNT y=$MON_CALCAMOUNT assign=MON_DIFFERENCE}
						<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}><u>{$MON_DIFFERENCE|number_format} {$CURRENCY}</u></font></td>
						{/if}
					</tr>
			</table>
			<br />
			<table border=0 cellpadding=2>
				<tr>
					<th></th>
					<th>{$TEXT_56}</th>
					<th>{$TEXT_57}</th>
				</tr>
				{if !$FIRSTAMOUNT}{assign var="FIRSTAMOUNT" value="0"}{/if}
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">{$TEXT_66}</th>
					{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=MON_FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $MON_FIXEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$MON_FIXEDTURNOVER|number_format} {$CURRENCY}</font></td>
					{math equation="y-x" x=$FIRSTAMOUNT y=$FIXAMOUNT assign=YEA_FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $YEA_FIXEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$YEA_FIXEDTURNOVER|number_format} {$CURRENCY}</font></td>
				</tr>
				{/if}
				<tr>
					<th align="right">{$TEXT_67}</th>
					<td align="right" class="contrastbgcolor"><font {if $MON_CALCULATEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$MON_CALCULATEDTURNOVER|number_format} {$CURRENCY}</font></td>
					<td align="right" class="contrastbgcolor"><font {if $YEA_CALCULATEDTURNOVER < 0}color="red"{else}color="black"{/if}>{$YEA_CALCULATEDTURNOVER|number_format} {$CURRENCY}</font></td>
				</tr>
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">{$TEXT_65}</th>
					<td align="right" class="contrastbgcolor"><font {if $MON_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$MON_DIFFERENCE|number_format} {$CURRENCY}</font></td>
					{math equation="x - y" x=$YEA_FIXEDTURNOVER y=$YEA_CALCULATEDTURNOVER assign=YEA_DIFFERENCE}
					<td align="right" class="contrastbgcolor"><font {if $YEA_DIFFERENCE < 0}color="red"{else}color="black"{/if}>{$YEA_DIFFERENCE|number_format} {$CURRENCY}</font></td>
				</tr>
				{/if}
			</table>
