<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}edit{else}add{/if} contract partnere</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="edit_contractpartner">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>name</th>
					<th>street</th>
					<th>postcode</th>
					<th>town</th>
					<th>country</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[name]"     value="{$ALL_DATA.name}"    /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[street]"   value="{$ALL_DATA.street}"  /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[postcode]" value="{$ALL_DATA.postcode}" size="5" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[town]"     value="{$ALL_DATA.town}"    /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[country]"  value="{$ALL_DATA.country}" size="10" /></td>
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
