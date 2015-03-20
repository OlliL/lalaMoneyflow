<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $PREDEFMONEYFLOWID > 0}{#TEXT_49#}{else}{#TEXT_12#}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"     value="edit_predefmoneyflow">
			<input type="hidden" name="realaction" value="save">
			<input type="hidden" name="predefmoneyflowid"     value="{$PREDEFMONEYFLOWID}">
			<input type="hidden" name="REFERER"    value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
			{/section}
			<table border=0>
				<tr>
					<th>{#TEXT_18#}</th>
					<th>{#TEXT_2#}</th>
					<th>{#TEXT_21#}</th>
					<th>{#TEXT_232#}</th>
					<th>{#TEXT_19#}</th>
					<th>{#TEXT_206#}</th>
				</tr>
				<tr>
					<td class="contrastbgcolornobr"><input class="contrastbgcolor" type="text" name="all_data[amount]" value="{$ALL_DATA.amount}" align="right" size="8" style="{if $ALL_DATA.amount_error == 1}color:red;{/if}text-align:right"> {#CURRENCY#}</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcp_contractpartnerid]" size="1" {if $ALL_DATA.contractpartner_error == 1}style="color:red"{/if}>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA.mcp_contractpartnerid}selected{/if} value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name|escape:htmlall}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment|escape:htmlall}" size="30"></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mpa_postingaccountid]" size=1 style="width:150px">
					{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
						<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}" {if $POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid == $ALL_DATA.mpa_postingaccountid}selected{/if}> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name|escape:htmlall}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcs_capitalsourceid]" size="1" {if $ALL_DATA.capitalsource_error == 1}style="color:red"{/if}>
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if} value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment|escape:htmlall}
					{/section}
					</select></td>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[once_a_month]" size=1>
							<option value=0 {if $ALL_DATA.once_a_month == 0}selected{/if} > {#TEXT_26#}
							<option value=1 {if $ALL_DATA.once_a_month == 1}selected{/if} > {#TEXT_25#}
						</select>
					</td>
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
