<html>
{if $CLOSE != 1}
	<head><title>moneyflow: edit moneyflow</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_moneyflow">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>bookingdate</th>
					<th>invoicedate</th>
					<th>amount</th>
					<th>capitalsource</th>
					<th>contractpartner</th>
					<th>comment</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bookingdate]" value="{$ALL_DATA.bookingdate}"                 size=10 /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[invoicedate]" value="{$ALL_DATA.invoicedate}"                 size=10 /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[amount]"      value="{$ALL_DATA.amount|string_format:"%.2f"}" size=8 align="right" /> EUR</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[capitalsourceid]" size=1>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_DATA.capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[contractpartnerid]" size=1>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $ALL_DATA.contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment}" size="50"/></td>
				</tr>
			</table>
			<input type="submit" name="realaction" value="save">
			<input type="button" value="cancel" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
