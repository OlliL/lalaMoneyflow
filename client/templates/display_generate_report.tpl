<html>
	<head><title>report</title>
{$HEADER}

		<td align="center">
			<h1>money flow {$YEAR}-{$MONTH}</h1>
			<table border=0>
				<tr>
					<th>bookingdate</th>
					<th>invoicedate</th>
					<th>amount</th>
					<th>contractpartner</th>
					<th>comment</th>
					<th>capitalsource</th>
				</tr>
				{section name=DATA loop=$ALL_MONEYFLOW_DATA}
					<tr>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].bookingdate}</td>
						<td class="contrastbgcolor">{$ALL_MONEYFLOW_DATA[DATA].invoicedate}</td>
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
						<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=show_moneyflow&id={$ALL_MONEYFLOW_DATA[DATA].id}&sr=1','_blank','width=1024,height=80')">edit</a></td>
					</tr>
				{/section}
			</table>
			<br />
			<hr>
			<h1>Summary</h1>
			<table border=0>
				<tr>
					<th>type</th>
					<th>state</th>
					<th>comment</th>
					<th>amount begin</th>
					<th>fixed end</th>
					<th>calculated end</th>
					<th>difference</th>
				</tr>
				{section name=DATA loop=$SUMMARY_DATA}
					<tr>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].type}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].state}</td>
						<td class="contrastbgcolor">{$SUMMARY_DATA[DATA].comment}</td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].lastamount < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].lastamount|number_format} EUR</font></td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].fixamount  < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].fixamount|number_format} EUR</font></td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].calcamount < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].calcamount|number_format} EUR</font></td>
						<td align="right" class="contrastbgcolor"><font {if $SUMMARY_DATA[DATA].difference < 0}color="red"{/if}>{$SUMMARY_DATA[DATA].difference|number_format} EUR</font></td>
					</tr>
				{/section}
			</table>
			<br />
			<table border=0>
				<tr>
					<th align="right">fixed turnover</th>
					{math equation="y-x" x=$LASTAMOUNT y=$FIXAMOUNT assign=FIXEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $FIXEDTURNOVER < 0}color="red"{/if}>{$FIXEDTURNOVER|number_format} EUR</font></td>
				</tr>
				<tr>
					<th align="right">calculated turnover</th>
					{math equation="y-x" x=$LASTAMOUNT y=$CALCAMOUNT assign=CALCULATEDTURNOVER}
					<td align="right" class="contrastbgcolor"><font {if $CALCULATEDTURNOVER < 0}color="red"{/if}>{$CALCULATEDTURNOVER|number_format} EUR</font></td>
				</tr>
				<tr>
					<th align="right">difference</th>
					{math equation="x - y" x=$FIXAMOUNT y=$CALCAMOUNT assign=DIFFERENCE}
					<td align="right" class="contrastbgcolor"><font {if $DIFFERENCE < 0}color="red"{/if}>{$DIFFERENCE|number_format} EUR</font></td>
				</tr>
			</table>
			<br />
			<form action="{$ENV_INDEX_PHP}" method="GET">
				<select name="header_month">
					<option {if $HEADER_MONTH == 01}selected{/if}> 01
					<option {if $HEADER_MONTH == 02}selected{/if}> 02
					<option {if $HEADER_MONTH == 03}selected{/if}> 03
					<option {if $HEADER_MONTH == 04}selected{/if}> 04
					<option {if $HEADER_MONTH == 05}selected{/if}> 05
					<option {if $HEADER_MONTH == 06}selected{/if}> 06
					<option {if $HEADER_MONTH == 07}selected{/if}> 07
					<option {if $HEADER_MONTH == 08}selected{/if}> 08
					<option {if $HEADER_MONTH == 09}selected{/if}> 09
					<option {if $HEADER_MONTH == 10}selected{/if}> 10
					<option {if $HEADER_MONTH == 11}selected{/if}> 11
					<option {if $HEADER_MONTH == 12}selected{/if}> 12
				</select>
				<select name="header_year">
					{section name=YEAR loop=$YEARS}
						<option {if $YEARS[YEAR] == $HEADER_YEAR}selected{/if}> {$YEARS[YEAR]}
					{/section}
				</select>
				<input type="submit" name="action" value="generate report">
			</form>
		</td>
{$FOOTER}
