<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_111}{else}{$TEXT_112}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"         value="edit_currencyrate">
			<input type="hidden" name="realaction"     value="save">
			<input type="hidden" name="mcu_currencyid" value="{$CURRENCYID}">
			<input type="hidden" name="validfrom"      value="{$VALIDFROM}">
			<input type="hidden" name="REFERER"        value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th width="100">{$TEXT_107}</th>
					<th width="80" >{$TEXT_108}</th>
					<th width="60" >{$TEXT_34}</th>
					{if $NEW != 1}
					<th width="60" >{$TEXT_35}</th>
					{/if}
				</tr>
				<tr>
					{if $NEW == 1}
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcu_currencyid]" size=1>
					{section name=CURRENCY loop=$CURRENCY_VALUES}
						<option value="{$CURRENCY_VALUES[CURRENCY].currencyid}"  {if $CURRENCY_VALUES[CURRENCY].currencyid == $ALL_DATA.mcu_currencyid}selected{/if}> {$CURRENCY_VALUES[CURRENCY].currency}
					{/section}
					</select></td>
					{else}
					<td class="contrastbgcolor">{$ALL_DATA.currency}</td>
					{/if}
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[rate]"      value="{$ALL_DATA.rate}"      size=10 /></td>
					{if $NEW == 1}
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[validfrom]" value="{$ALL_DATA.validfrom}" size=10 /></td>
					{else}
					<td class="contrastbgcolor">{$ALL_DATA.validfrom}</td>
					<td class="contrastbgcolor">{$ALL_DATA.validtil}</td>
					{/if}
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
