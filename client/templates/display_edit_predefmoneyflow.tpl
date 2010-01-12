<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_49}{else}{$TEXT_12}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"     value="edit_predefmoneyflow">
			<input type="hidden" name="realaction" value="save">
			<input type="hidden" name="predefmoneyflowid"     value="{$PREDEFMONEYFLOWID}">
			<input type="hidden" name="REFERER"    value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th>{$TEXT_18}</th>
					<th>{$TEXT_2}</th>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_19}</th>
					<th>{$TEXT_206}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><nobr><input class="contrastbgcolor" type="text" name="all_data[amount]" value="{$ALL_DATA.amount}" align="right" size="8"/> {$CURRENCY}</nobr></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcp_contractpartnerid]" size=1>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA.mcp_contractpartnerid}selected{/if} value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment}" size="30"/></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcs_capitalsourceid]" size=1>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if} value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
					{/section}
					</select></td>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[once_a_month]" size=1>
							<option value=0 {if $ALL_DATA.once_a_month == 0}selected{/if} > {$TEXT_26}
							<option value=1 {if $ALL_DATA.once_a_month == 1}selected{/if} > {$TEXT_25}
						</select>
					</td>
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
