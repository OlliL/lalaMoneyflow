<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {$TEXT_39}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<u>{$TEXT_40}</u><br /><br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_capitalsource">
			<input type="hidden" name="realaction"       value="yes">
			<input type="hidden" name="capitalsourceid"  value="{$ALL_DATA.capitalsourceid}">
			<input type="hidden" name="REFERER"          value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th width="200">{$TEXT_21}</th>
					<th width="80" >{$TEXT_30}</th>
					<th width="80" >{$TEXT_31}</th>
					<th width="100">{$TEXT_32}</th>
					<th width="100">{$TEXT_33}</th>
					<th width="60" >{$TEXT_34}</th>
					<th width="60" >{$TEXT_35}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.comment}</td>
					<td class="contrastbgcolor">{$ALL_DATA.type}</td>
					<td class="contrastbgcolor">{$ALL_DATA.state}</td>
					<td class="contrastbgcolor">{$ALL_DATA.accountnumber}</td>
					<td class="contrastbgcolor">{$ALL_DATA.bankcode}</td>
					<td class="contrastbgcolor">{$ALL_DATA.validfrom}</td>
					<td class="contrastbgcolor">{$ALL_DATA.validtil}</td>
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
