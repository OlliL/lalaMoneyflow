<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_15}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_moneyflow">
			<input type="hidden" name="realaction" value="save">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th>{$TEXT_16}</th>
					<th>{$TEXT_17}</th>
					<th>{$TEXT_18}</th>
					<th>{$TEXT_20}</th>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_19}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bookingdate]" value="{$ALL_DATA.bookingdate}"                 size=10 /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[invoicedate]" value="{$ALL_DATA.invoicedate}"                 size=10 /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[amount]"      value="{$ALL_DATA.amount|string_format:"%.2f"}" size=8 align="right" /> {$CURRENCY}</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[contractpartnerid]" size=1>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $ALL_DATA.contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment}" size="50"/></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[capitalsourceid]" size=1>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_DATA.capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
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
