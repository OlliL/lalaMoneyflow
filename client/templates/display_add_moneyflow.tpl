<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_8}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{$TEXT_8}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<br>
		<form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
			<input type="hidden" name="action" value="add_moneyflow">
			<table border=0>
				<tr>
					<th>&nbsp;</th>
					<th>{$TEXT_209}</th>
					<th>{$TEXT_16}</th>
					<th>{$TEXT_17}</th>
					<th>{$TEXT_18}</th>
					<th>{$TEXT_2}</th>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_19}</th>
					<th>{$TEXT_207}</th>
				</tr>
				{assign var=elements value="1"}
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][checked]" value=1 {if $ALL_DATA[DATA].checked == 1}checked{/if}><input type="hidden" name="all_data[{$smarty.section.DATA.index}][predefmoneyflowid]" value="{$ALL_DATA[DATA].predefmoneyflowid}"></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][private]" value=1 {if $ALL_DATA[DATA].private == 1}checked{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][bookingdate]" value="{$ALL_DATA[DATA].bookingdate}" size=10 {if $ALL_DATA[DATA].bookingdate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][invoicedate]" value="{$ALL_DATA[DATA].invoicedate}" size=10 {if $ALL_DATA[DATA].invoicedate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}" size=8 onchange="this.form.elements[{$elements}].checked=true" style="text-align:right{if $ALL_DATA[DATA].amount_error == 1};color:red{/if}"> {$CURRENCY}</td>

						{if $ALL_DATA[DATA].predefmoneyflowid lt 0 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcp_contractpartnerid]" size=1 {if $ALL_DATA[DATA].contractpartner_error == 1}style="color:red"{/if}>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}" {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA[DATA].mcp_contractpartnerid}selected{/if} > {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name|escape:htmlall}</option>
						{/section}
						</select></td>
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcp_contractpartnerid]" value="{$ALL_DATA[DATA].mcp_contractpartnerid}"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][contractpartnername]" value="{$ALL_DATA[DATA].contractpartnername|escape:htmlall}" {if $ALL_DATA[DATA].contractpartner_error == 1}style="color:red"{/if}>{$ALL_DATA[DATA].contractpartnername|escape:htmlall}</td>
						{/if}
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][comment]" value="{$ALL_DATA[DATA].comment|escape:htmlall}" size="40" {if $ALL_DATA[DATA].comment_error == 1}style="color:red"{/if}></td>

						{if $ALL_DATA[DATA].predefmoneyflowid lt 0 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" size=1 {if $ALL_DATA[DATA].capitalsource_error == 1}style="color:red"{/if}>
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA[DATA].mcs_capitalsourceid}selected{/if} > {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment|escape:htmlall}</option>
						{/section}
						</select></td>
						{assign var="elements" value="`$elements+9`"}
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].mcs_capitalsourceid}"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment|escape:htmlall}"><font {if $ALL_DATA[DATA].capitalsource_error == 1}style="color:red"{/if}>{$ALL_DATA[DATA].capitalsourcecomment|escape:htmlall}</font></td>
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][last_used]" value="{$ALL_DATA[DATA].last_used}">{$ALL_DATA[DATA].last_used}</td>
						{assign var="elements" value="`$elements+12`"}
						{/if}
					</tr>
				{/section}
{literal}
<script language="JavaScript">
  document.addmoney.elements[6].focus();
</script>
{/literal}
			</table>
			<br>
			<input type="hidden" name="realaction" value="save">
			<input type="submit" value="{$TEXT_22}">
		</form>
		</td>
{$FOOTER}
