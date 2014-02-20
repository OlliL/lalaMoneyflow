<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_48#}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<u>{#TEXT_47#}</u><br><br>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"            value="delete_contractpartner">
			<input type="hidden" name="realaction"        value="yes">
			<input type="hidden" name="contractpartnerid" value="{$ALL_DATA.contractpartnerid}">
			<input type="hidden" name="REFERER"           value="{$ENV_REFERER}">
			<table border=0 width=600>
				<tr>
					<th width="150">{#TEXT_41#}</th>
					<th width="200">{#TEXT_42#}</th>
					<th width="50" >{#TEXT_43#}</th>
					<th width="100">{#TEXT_44#}</th>
					<th width="100">{#TEXT_45#}</th>
					<th width="60" >{#TEXT_34#}</th>
					<th width="60" >{#TEXT_35#}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor">{$ALL_DATA.name|escape:htmlall}</td>
					<td class="contrastbgcolor" wrap>{$ALL_DATA.street|escape:htmlall}</td>
					<td class="contrastbgcolor">{$ALL_DATA.postcode}</td>
					<td class="contrastbgcolor">{$ALL_DATA.town|escape:htmlall}</td>
					<td class="contrastbgcolor">{$ALL_DATA.country|escape:htmlall}</td>
					<td class="contrastbgcolor">{$ALL_DATA.validfrom}</td>
					<td class="contrastbgcolor">{$ALL_DATA.validtil}</td>
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
