<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_46}{else}{$TEXT_11}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"            value="edit_contractpartner">
			<input type="hidden" name="realaction"        value="save">
			<input type="hidden" name="contractpartnerid" value="{$ALL_DATA.contractpartnerid}">
			<input type="hidden" name="REFERER"           value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
			{/section}
			<table border=0>
				<tr>
					<th>{$TEXT_41}</th>
					<th>{$TEXT_42}</th>
					<th>{$TEXT_43}</th>
					<th>{$TEXT_44}</th>
					<th>{$TEXT_45}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[name]"     value="{$ALL_DATA.name|escape:htmlall}"   ></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[street]"   value="{$ALL_DATA.street|escape:htmlall}" ></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[postcode]" value="{$ALL_DATA.postcode|escape:htmlall}" size="5"></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[town]"     value="{$ALL_DATA.town|escape:htmlall}"   ></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[country]"  value="{$ALL_DATA.country|escape:htmlall}" size="10"></td>
				</tr>
			</table>
			<input type="submit" value="{$TEXT_22}">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
