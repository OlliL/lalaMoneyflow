<html>
{if $CLOSE != 1}
	<head><title>moneyflow: {if $ALL_DATA.id > 0}edit{else}add{/if} predefined moneyflow</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="edit_predefmoneyflow">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>amount</th>
					<th>capital source</th>
					<th>contract partner</th>
					<th>comment</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[amount]" value="{$ALL_DATA.amount|string_format:"%.2f"}" align="right" size="8"/> EUR</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[capitalsourceid]" size=1>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_DATA.capitalsourceid}selected{/if} value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[contractpartnerid]" size=1>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $ALL_DATA.contractpartnerid}selected{/if} value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
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
