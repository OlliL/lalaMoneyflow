<html>
{if $CLOSE != 1}
	<head><title>moneyflow: delete predefined moneyflowe</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		Do you realy want delete this predefined moneyflow?<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_predefmoneyflow">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
				 	 <th width="80">amount</th>
				 	 <th width="100">capital source</th>
				 	 <th width="200">contract partner</th>
				 	 <th width="200">comment</th>
				</tr>
				<tr>
					<td class="contrastbgcolor" align="right">{$ALL_DATA.amount|string_format:"%.2f"} EUR</td>
					<td class="contrastbgcolor">{$ALL_DATA.capitalsource_comment}</td>
					<td class="contrastbgcolor">{$ALL_DATA.contractpartner_name}</td>
					<td class="contrastbgcolor">{$ALL_DATA.comment}</td>
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
