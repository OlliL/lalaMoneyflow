<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_15#}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"      value="edit_moneyflow">
			<input type="hidden" name="realaction"  value="save">
			<input type="hidden" name="moneyflowid" value="{$MONEYFLOWID}">
			<input type="hidden" name="REFERER"     value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
			{/section}
			<table border=0>
				<tr>
					<th>{#TEXT_209#}</th>
					<th>{#TEXT_16#}</th>
					<th>{#TEXT_17#}</th>
					<th>{#TEXT_18#}</th>
					<th>{#TEXT_2#}</th>
					<th>{#TEXT_21#}</th>
					<th>{#TEXT_232#}</th>
					<th>{#TEXT_19#}</th>
				</tr>
				<tr>
				    <td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[private]" value=1 {if $ALL_DATA.private == 1}checked{/if} ></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bookingdate]" value="{$ALL_DATA.bookingdate}"                 size=10 {if $ALL_DATA.bookingdate_error == 1}style="color:red"{/if}></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[invoicedate]" value="{$ALL_DATA.invoicedate}"                 size=10 {if $ALL_DATA.invoicedate_error == 1}style="color:red"{/if}></td>
					<td class="contrastbgcolor"><input class="contrastbgcolornobr" type="text" name="all_data[amount]"      value="{$ALL_DATA.amount}"    size=8  style="{if $ALL_DATA.amount_error == 1}color:red;{/if}text-align:right"> {#CURRENCY#}</td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcp_contractpartnerid]" size=1 style="width:130px">
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA.mcp_contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]" value="{$ALL_DATA.comment}" size="40"></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mpa_postingaccountid]" size=1 style="width:150px">
					{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
						<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}" {if $POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid == $ALL_DATA.mpa_postingaccountid}selected{/if}> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[mcs_capitalsourceid]" size=1 style="width:150px">
					{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
						<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
					{/section}
					</select></td>
				</tr>
				{section name=SPLIT_ENTRIES loop=$MONEYFLOW_SPLIT_ENTRIES}
					<tr>
						<td colspan=2></td>
						<th align="right">{#TEXT_37#} <input class="contrastbgcolor" type="checkbox" name="moneyflow_split_entries[{$smarty.section.SPLIT_ENTRIES.index}][delete]" value=1 {if $MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].delete == 1}checked{/if} ></th>
						<td class="contrastbgcolor">
						 	<input type="hidden" name="moneyflow_split_entries[{$smarty.section.SPLIT_ENTRIES.index}][moneyflowsplitentryid]" value="{$MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].moneyflowsplitentryid}">
						 	<input class="contrastbgcolornobr" type="text" name="moneyflow_split_entries[{$smarty.section.SPLIT_ENTRIES.index}][amount]" value="{$MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].amount}"    size=8  style="{if $MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].amount_error == 1}color:red;{/if}text-align:right"> {#CURRENCY#}
						</td>
						<td></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="moneyflow_split_entries[{$smarty.section.SPLIT_ENTRIES.index}][comment]" value="{$MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].comment}" size="40"></td>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="moneyflow_split_entries[{$smarty.section.SPLIT_ENTRIES.index}][mpa_postingaccountid]" size=1 style="width:150px">
							<option value=""> </option>
						{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
							<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}" {if $POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid == $MONEYFLOW_SPLIT_ENTRIES[SPLIT_ENTRIES].mpa_postingaccountid}selected{/if}> {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}
						{/section}
						</select></td>
						<td></td>
					</tr>
				{/section}
			</table>
			<input type="submit" value="{#TEXT_22#}">
			<input type="button" value="{#TEXT_23#}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	{if $ENV_REFERER == ''}
		<body onLoad='parent.close()'>
	{else}
		<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	{/if}
	</html>
{/if}
