<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_266#}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<u>{#TEXT_267#}</u><br><br>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"            value="delete_contractpartneraccount">
			<input type="hidden" name="realaction"        value="yes">
			<input type="hidden" name="contractpartneraccountid" value="{$ALL_DATA.contractpartneraccountid}">
			<input type="hidden" name="REFERER"           value="{$ENV_REFERER}">
			<table border=0>
				<tr>
						<th width="215">{#TEXT_32#}</th>
						<th width="85">{#TEXT_33#}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.accountnumber}</td>
					<td class="contrastbgcolor">{$ALL_DATA.bankcode}</td>
				</tr>
			</table>
			<input type="submit" value="{#TEXT_25#}">
			<input type="button" value="{#TEXT_26#}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
