<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_15}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"      value="edit_moneyflow">
			<input type="hidden" name="realaction"  value="save">
			<input type="hidden" name="moneyflowid" value="{$MONEYFLOWID}">
			<input type="hidden" name="REFERER"     value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th>{$TEXT_16}</th>
					<th>{$TEXT_17}</th>
					<th>{$TEXT_18}</th>
					<th>{$TEXT_2}</th>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_19}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bookingdate]" value="{$ALL_DATA.bookingdate}"                 size=10 {if $ALL_DATA.bookingdate_error == 1}style="color:red"{/if}/></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[invoicedate]" value="{$ALL_DATA.invoicedate}"                 size=10 {if $ALL_DATA.invoicedate_error == 1}style="color:red"{/if}/></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[amount]"      value="{$ALL_DATA.amount}" size=8 align="right" /> {$CURRENCY}</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcp_contractpartnerid]" size=1>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA.mcp_contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment}" size="50"/></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcs_capitalsourceid]" size=1>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
					{/section}
					</select></td>
				</tr>
			</table>
			<input type="submit" value="{$TEXT_22}">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
