			<br />
			<h1>money flow for {$MONTH.name} {$YEAR}</h1>
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%">bookingdate</th>
					<th width="9%">invoicedate</th>
					<th width="10%">amount</th>
					<th width="16%">contract partner</th>
					<th >comment</th>
					<th width="22%">capital source</th>
					<th width="2%">&nbsp</th>
					<th width="3%">&nbsp</th>
				</tr>
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
					<tr>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $ALL_MONEYFLOW_DATA[DATA].amount < 0}color="red"{/if}>{$ALL_MONEYFLOW_DATA[DATA].amount|number_format} EUR</font></td>
						<td class="contrastbgcolor">
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							{if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $ALL_MONEYFLOW_DATA[DATA].contractpartnerid}{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}{/if}
						{/section}
						</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].comment}</td>
						<td class="contrastbgcolor">
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							{if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_MONEYFLOW_DATA[DATA].capitalsourceid}{$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}{/if} 
						{/section}
						</td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_moneyflow&id={$ALL_MONEYFLOW_DATA[DATA].id}&sr=1','_blank','width=1024,height=80')">edit</a></td>
						<td class="contrastbgcolor" align="center"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_moneyflow&id={$ALL_MONEYFLOW_DATA[DATA].id}&sr=1','_blank','width=1024,height=80')">delete</a></td>
					</tr>
				{/section}
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
						<td></td>
						<td></td>
						<td></td>
						<td align="right" class="contrastbgcolor"><font {if $LASTAMOUNT < 0}color="red"{/if}>{$LASTAMOUNT|number_format} EUR</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						<td align="right" class="contrastbgcolor"><b><font {if $FIXAMOUNT < 0}color="red"{/if}>{$FIXAMOUNT|number_format} EUR</b></font></td>
						{/if}
						<td align="right" class="contrastbgcolor"><font {if $CALCAMOUNT < 0}color="red"{/if}>{$CALCAMOUNT|number_format} EUR</font></td>
						{if $MONTHLYSETTLEMENT_EXISTS == true}
						{math equation="x - y" x=$FIXAMOUNT y=$CALCAMOUNT assign=DIFFERENCE}
						<td align="right" class="contrastbgcolor"><font {if $DIFFERENCE < 0}color="red"{/if}>{$DIFFERENCE|number_format} EUR</font></td>
						{/if}
			</table>
			<br />
			<table border=0 cellpadding=2>
				<tr>
					<th></th>
					<th>month</th>
					<th>year</th>
				</tr>
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">fixed turnover</th>
					{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $FIXEDTURNOVER < 0}color="red"{/if}>{$FIXEDTURNOVER|number_format} EUR</font></td>
					{math equation="y-x" x=$FIRSTAMOUNT y=$FIXAMOUNT assign=FIRSTTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $FIRSTTURNOVER < 0}color="red"{/if}>{$FIRSTTURNOVER|number_format} EUR</font></td>
				</tr>
				{/if}
				<tr>
					<th align="right">calculated turnover</th>
					{math equation="y-x" x=$LASTAMOUNT y=$CALCAMOUNT assign=CALCULATEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $CALCULATEDTURNOVER < 0}color="red"{/if}>{$CALCULATEDTURNOVER|number_format} EUR</font></td>
					{math equation="y-x" x=$FIRSTAMOUNT y=$CALCAMOUNT assign=FIRSTTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $FIRSTTURNOVER < 0}color="red"{/if}>{$FIRSTTURNOVER|number_format} EUR</font></td>
				</tr>
				{if $MONTHLYSETTLEMENT_EXISTS == true}
				<tr>
					<th align="right">difference</th>
					<td align="right" class="contrastbgcolor" colspan=2><font {if $DIFFERENCE < 0}color="red"{/if}>{$DIFFERENCE|number_format} EUR</font></td>
				</tr>
				{/if}
			</table>
