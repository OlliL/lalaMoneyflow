<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_46}{else}{$TEXT_11}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"            value="edit_contractpartner">
			<input type="hidden" name="realaction"        value="save">
			<input type="hidden" name="contractpartnerid" value="{$ALL_DATA.contractpartnerid}">
			<input type="hidden" name="REFERER"           value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>{$TEXT_41}</th>
					<th>{$TEXT_42}</th>
					<th>{$TEXT_43}</th>
					<th>{$TEXT_44}</th>
					<th>{$TEXT_45}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[name]"     value="{$ALL_DATA.name}"    /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[street]"   value="{$ALL_DATA.street}"  /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[postcode]" value="{$ALL_DATA.postcode}" size="5" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[town]"     value="{$ALL_DATA.town}"    /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[country]"  value="{$ALL_DATA.country}" size="10" /></td>
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
