<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_213}{else}{$TEXT_214}{/if}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"     value="edit_group">
			<input type="hidden" name="realaction" value="save">
			<input type="hidden" name="groupid"    value="{$ALL_DATA.groupid}">
			<input type="hidden" name="REFERER"    value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>{$TEXT_210}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[name]"          size=10 value="{$ALL_DATA.name}" /></td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
