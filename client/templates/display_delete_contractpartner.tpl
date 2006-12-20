<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_48}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<u>{$TEXT_47}</u><br /><br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_contractpartner">
			<input type="hidden" name="realaction" value="yes">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0 width=600>
				<tr>
					<th width="150">{$TEXT_41}</th>
					<th width="200">{$TEXT_42}</th>
					<th width="50" >{$TEXT_43}</th>
					<th width="100">{$TEXT_44}</th>
					<th width="100">{$TEXT_45}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.name}</td>
					<td class="contrastbgcolor" wrap>{$ALL_DATA.street}</td>
					<td class="contrastbgcolor">{$ALL_DATA.postcode}</td>
					<td class="contrastbgcolor">{$ALL_DATA.town}</td>
					<td class="contrastbgcolor">{$ALL_DATA.country}</td>
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
