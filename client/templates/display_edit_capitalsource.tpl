<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $ALL_DATA.id > 0}{$TEXT_38}{else}{$TEXT_10}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"           value="edit_capitalsource">
			<input type="hidden" name="realaction"       value="save">
			<input type="hidden" name="capitalsourceid"  value="{$CAPITALSOURCEID}">
			<input type="hidden" name="REFERER"          value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_30}</th>
					<th>{$TEXT_31}</th>
					<th>{$TEXT_32}</th>
					<th>{$TEXT_33}</th>
					<th>{$TEXT_34}</th>
					<th>{$TEXT_35}</th>
					<th>{$TEXT_210}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]"       value="{$ALL_DATA.comment}" /></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[type]"  size=1>
					{section name=TYPE loop=$TYPE_VALUES}
						<option {if $TYPE_VALUES[TYPE].value   == $ALL_DATA.type}selected{/if} value="{$TYPE_VALUES[TYPE].value}" > {$TYPE_VALUES[TYPE].text}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[state]" size=1>
					{section name=STATE loop=$STATE_VALUES}
						<option {if $STATE_VALUES[STATE].value == $ALL_DATA.state}selected{/if} value="{$STATE_VALUES[STATE].value}"> {$STATE_VALUES[STATE].text}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[accountnumber]" value="{$ALL_DATA.accountnumber}" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bankcode]"      value="{$ALL_DATA.bankcode}" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[validfrom]"     value="{$ALL_DATA.validfrom}" size=8 {if $ALL_DATA.validfrom_error == 1}style="color:red"{/if} /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[validtil]"      value="{$ALL_DATA.validtil}"  size=8 {if $ALL_DATA.validtil_error  == 1}style="color:red"{/if} /></td>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[att_group_use]" size=1>
							<option value=0 {if $ALL_DATA.att_group_use == 0}selected{/if} > {$TEXT_26}
							<option value=1 {if $ALL_DATA.att_group_use == 1}selected{/if} > {$TEXT_25}
						</select>
					</td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
