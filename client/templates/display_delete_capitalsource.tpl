<html>
{if $CLOSE != 1}
	<head><title>moneyflow: delete capital source</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		Do you realy want delete this capitalsource?<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_capitalsource">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th width="200">comment</th>
					<th width="80">type</th>
					<th width="80">state</th>
					<th width="100">accountnumber</th>
					<th width="100">bankcode</th>
					<th width="60">valid from</th>
					<th width="60">valid til</th>
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
			<input type="submit" name="realaction" value="yes">
			<input type="button" value="no" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
