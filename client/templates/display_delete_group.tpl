<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_215}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<u>{$TEXT_216}</u><br /><br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"     value="delete_group">
			<input type="hidden" name="realaction" value="yes">
			<input type="hidden" name="groupid"    value="{$ALL_DATA.groupid}">
			<input type="hidden" name="REFERER"    value="{$ENV_REFERER}">
			{if $ASK == 1}
			<input type="hidden" name="force"      value="1">
			{/if}
			<table border=0>
				<tr>
					<th width="200">{$TEXT_210}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.name}</td>
				</tr>
			</table>
			<input type="submit" value="{$TEXT_25}">
			<input type="button" value="{$TEXT_26}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
