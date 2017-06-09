<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_8#}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{#TEXT_8#}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<br>
		<form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST" name="addmoney">
			<input type="hidden" name="action" value="add_moneyflow">
			<table border=0>
				<tr>
					<th>&nbsp;</th>
					<th>{#TEXT_209#}</th>
					<th>{#TEXT_16#}</th>
					<th>{#TEXT_17#}</th>
					<th>{#TEXT_18#}</th>
					<th>{#TEXT_2#}</th>
					<th>{#TEXT_21#}</th>
					<th>{#TEXT_232#}</th>
					<th>{#TEXT_19#}</th>
					<th>{#TEXT_207#}</th>
				</tr>
				{assign var=elements value="1"}
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][checked]" value=1 {if $ALL_DATA[DATA].checked == 1}checked{/if}><input type="hidden" name="all_data[{$smarty.section.DATA.index}][predefmoneyflowid]" value="{$ALL_DATA[DATA].predefmoneyflowid}"></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][private]" value=1 {if $ALL_DATA[DATA].private == 1}checked{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][bookingdate]" value="{$ALL_DATA[DATA].bookingdate}" size=9 {if $ALL_DATA[DATA].bookingdate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][invoicedate]" value="{$ALL_DATA[DATA].invoicedate}" size=9 {if $ALL_DATA[DATA].invoicedate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount|number_format}" size=6 onchange="this.form.elements[{$elements}].checked=true" style="text-align:right{if $ALL_DATA[DATA].amount_error == 1};color:red{/if}"> {#CURRENCY#}</td>

						{if $ALL_DATA[DATA].predefmoneyflowid lt 0 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcp_contractpartnerid]" size=1 style="width:130px{if $ALL_DATA[DATA].contractpartner_error == 1};color:red{/if}" onchange="initContractpartner({$elements}+6)">
							<option value=""> </option>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}" {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA[DATA].mcp_contractpartnerid}selected{/if} > {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
						{/section}
						</select></td>
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcp_contractpartnerid]" value="{$ALL_DATA[DATA].mcp_contractpartnerid}"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][contractpartnername]" value="{$ALL_DATA[DATA].contractpartnername}" {if $ALL_DATA[DATA].contractpartner_error == 1}style="color:red"{/if}>{$ALL_DATA[DATA].contractpartnername}</td>
						{/if}

						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][comment]" value="{$ALL_DATA[DATA].comment}" size="30" {if $ALL_DATA[DATA].comment_error == 1}style="color:red"{/if}></td>

						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mpa_postingaccountid]" size=1 style="width:150px{if $ALL_DATA[DATA].postingaccount_error == 1};color:red{/if}">
							<option value=""> </option>
						{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
							<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}" {if $POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid == $ALL_DATA[DATA].mpa_postingaccountid}selected{/if} > {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name}</option>
						{/section}
						</select></td>

						{if $ALL_DATA[DATA].predefmoneyflowid lt 0 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" size=1 style="width:150px{if $ALL_DATA[DATA].capitalsource_error == 1};color:red{/if}">
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA[DATA].mcs_capitalsourceid}selected{/if} > {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
						{/section}
						</select></td>
						{assign var="elements" value="`$elements+10`"}
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].mcs_capitalsourceid}"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment}"><font {if $ALL_DATA[DATA].capitalsource_error == 1}style="color:red"{/if}>{$ALL_DATA[DATA].capitalsourcecomment}</font></td>
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][last_used]" value="{$ALL_DATA[DATA].last_used}">{$ALL_DATA[DATA].last_used}</td>
						{assign var="elements" value="`$elements+13`"}
						{/if}
					</tr>
				{/section}
{literal}
<script language="JavaScript">
  document.addmoney.elements[6].focus();
 
  var comment = new Array();
  var postingAccount = new Array();
{/literal}
{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
   comment['{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}'] = '{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].moneyflow_comment}';
   postingAccount['{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}'] = '{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].mpa_postingaccountid}';
{/section}
  var numflows = {$NUMFLOWS}
{literal}

  var elementId = 1;
  for(var i=1 ; i <= numflows ; i ++) {
    initContractpartner(elementId + 6);
    elementId+=10;
  }
  
  function selectItemByValue(element, value) {
    if (value !== undefined && value !== null) {
      var length = element.options.length;
      for (var i = 0; i < length; i++) {
        if (element.options[i].value === value) {
          element.selectedIndex = i;
          return;
        }
      }
    }
  }
  
  function initContractpartner(elementId) {
    var e = document.addmoney.elements[elementId];
    var contractpartnerId = e.options[e.selectedIndex].value;

    if(contractpartnerId > 0 && document.addmoney.elements[elementId+1].value == "") {
      document.addmoney.elements[elementId+1].value=comment[contractpartnerId];
    }
    selectItemByValue( document.addmoney.elements[elementId+2], postingAccount[contractpartnerId] );
  }
</script>
{/literal}
			</table>
			<br>
			<input type="hidden" name="realaction" value="save">
			<input type="submit" value="{#TEXT_22#}">
		</form>
		</td>
{$FOOTER}
