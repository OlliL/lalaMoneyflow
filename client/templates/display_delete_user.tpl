<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_101}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<u>{$TEXT_102}</u><br /><br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_user">
			<input type="hidden" name="realaction" value="yes">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th width="200">{$TEXT_85}</th>
					<th width="40" >{$TEXT_96}</th>
					<th width="40" >{$TEXT_97}</th>
					<th width="40" >{$TEXT_98}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.name}</td>
					<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA.perm_login == 1}<font color="green">{$TEXT_25}{else}<font color="red">{$TEXT_26}{/if}</font></b></td>
					<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA.perm_admin == 1}<font color="green">{$TEXT_25}{else}<font color="red">{$TEXT_26}{/if}</font></b></td>
					<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA.att_new == 1}<font color="green">{$TEXT_25}{else}<font color="red">{$TEXT_26}{/if}</font></b></td>
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
