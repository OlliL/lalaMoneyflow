<html>
{if $CLOSE != 1}
	<head><title>moneyflow: delete contract partner</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		Do you realy want delete this contract partner?<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_contractpartner">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0 width=600>
				<tr>
					<th width="150">name</th>
					<th width="200">street</th>
					<th width="50">postcode</th>
					<th width="100">town</th>
					<th width="100">country</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.name}</td>
					<td class="contrastbgcolor" wrap>{$ALL_DATA.street}</td>
					<td class="contrastbgcolor">{$ALL_DATA.postcode}</td>
					<td class="contrastbgcolor">{$ALL_DATA.town}</td>
					<td class="contrastbgcolor">{$ALL_DATA.country}</td>
				</tr>
			</table>
			<input type="submit" name="realaction" value="yes">
			<input type="button" value="no" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
