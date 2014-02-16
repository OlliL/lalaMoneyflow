<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_51#}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<u>{#TEXT_52#}</u><br><br>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"            value="delete_predefmoneyflow">
			<input type="hidden" name="realaction"        value="yes">
			<input type="hidden" name="predefmoneyflowid" value="{$ALL_DATA.predefmoneyflowid}">
			<input type="hidden" name="REFERER"           value="{$ENV_REFERER}">
			<table border=0>
				<tr>
				 	 <th width="80" >{#TEXT_18#}</th>
				 	 <th width="200">{#TEXT_2#}</th>
				 	 <th width="200">{#TEXT_21#}</th>
				 	 <th width="100">{#TEXT_19#}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor" align="right">{$ALL_DATA.amount|string_format:"%.2f"} {#CURRENCY#}</td>
					<td class="contrastbgcolor">{$ALL_DATA.contractpartnername|escape:htmlall}</td>
					<td class="contrastbgcolor">{$ALL_DATA.comment|escape:htmlall}</td>
					<td class="contrastbgcolor">{$ALL_DATA.capitalsourcecomment|escape:htmlall}</td>
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
