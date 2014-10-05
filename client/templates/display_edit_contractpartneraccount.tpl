<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $CONTRACTPARTNERACCOUNTID > 0}{#TEXT_264#}{else}{#TEXT_365#}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"                   value="edit_contractpartneraccount">
			<input type="hidden" name="realaction"               value="save">
			<input type="hidden" name="contractpartneraccountid" value="{$CONTRACTPARTNERACCOUNTID}">
			<input type="hidden" name="REFERER"                  value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
			{/section}
			<table border=0>
				<tr>
					<th>{#TEXT_32#}</th>
					<th>{#TEXT_33#}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[accountnumber]" value="{$ALL_DATA.accountnumber}"    {if $ALL_DATA.accountnumber_error == 1}style="color:red"{/if}></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bankcode]"      value="{$ALL_DATA.bankcode}"         {if $ALL_DATA.bankcode_error      == 1}style="color:red"{/if}></td>
				</tr>
			</table>
			<input type="submit" value="{#TEXT_22#}">
			<input type="button" value="{#TEXT_23#}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
