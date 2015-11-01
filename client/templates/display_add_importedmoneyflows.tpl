<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_268#}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{#TEXT_268#}</h1>
{if $NUMFLOWS > 0}
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<br>
		<form action="{$ENV_INDEX_PHP}?action=add_importedmoneyflows" method="POST" name="addmoney">
			<input type="hidden" name="action" value="add_importedmoneyflows">
			<input type="hidden" name="realaction" value="save">
			<table border=0>
				{assign var=elements value="1"}
				{section name=DATA loop=$ALL_DATA}
				<tr>
					<th>{#TEXT_271#}</th>
					<th>{#TEXT_37#}</th>
					<!--<th>&nbsp;</th>-->
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
						<td class="contrastbgcolor">
						<input type="hidden" name="all_data[{$smarty.section.DATA.index}][importedmoneyflowid]" value="{$ALL_DATA[DATA].importedmoneyflowid}">
						<input class="contrastbgcolor" type="radio" name="all_data[{$smarty.section.DATA.index}][action]" value=1 {if $ALL_DATA[DATA].action == 1}checked{/if} onchange="changeColor({$elements})"></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="radio" name="all_data[{$smarty.section.DATA.index}][action]" value=2 {if $ALL_DATA[DATA].action == 2}checked{/if} onchange="changeColor({$elements})"></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][private]" value=1 {if $ALL_DATA[DATA].private == 1}checked{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][bookingdate]" value="{$ALL_DATA[DATA].bookingdate}" size=9 {if $ALL_DATA[DATA].bookingdate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][invoicedate]" value="{$ALL_DATA[DATA].invoicedate}" size=9 {if $ALL_DATA[DATA].invoicedate_error == 1}style="color:red"{/if}></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount|number_format}" size=6 style="text-align:right{if $ALL_DATA[DATA].amount_error == 1};color:red{/if}"> {#CURRENCY#}</td>

						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcp_contractpartnerid]" size=1 style="width:130px{if $ALL_DATA[DATA].contractpartner_error == 1};color:red{/if}" onchange="initContractpartner({$elements}+8)">
							<option value=""> </option>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}" {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $ALL_DATA[DATA].mcp_contractpartnerid}selected{/if} > {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name|escape:htmlall}</option>
						{/section}
						</select></td>

						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][comment]" value="{$ALL_DATA[DATA].comment|escape:htmlall}" size="30" {if $ALL_DATA[DATA].comment_error == 1}style="color:red"{/if}></td>

						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mpa_postingaccountid]" size=1 style="width:150px{if $ALL_DATA[DATA].postingaccount_error == 1};color:red{/if}">
							<option value=""> </option>
						{section name=POSTINGACCOUNT loop=$POSTINGACCOUNT_VALUES}
							<option value="{$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid}" {if $POSTINGACCOUNT_VALUES[POSTINGACCOUNT].postingaccountid == $ALL_DATA[DATA].mpa_postingaccountid}selected{/if} > {$POSTINGACCOUNT_VALUES[POSTINGACCOUNT].name|escape:htmlall}</option>
						{/section}
						</select></td>

						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" size=1 style="width:150px{if $ALL_DATA[DATA].capitalsource_error == 1};color:red{/if}">
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA[DATA].mcs_capitalsourceid}selected{/if} > {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment|escape:htmlall}</option>
						{/section}
						</select></td>
					</tr>
					<tr>
						<td colspan="9">
							<table>
							<tr>
								<td width="80">&nbsp;</td>
								<th width="30"  align="right">{#TEXT_32#}</th>
								<td width="140" class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][accountnumber]" value="{$ALL_DATA[DATA].accountnumber|escape:htmlall}">{$ALL_DATA[DATA].accountnumber|escape:htmlall}</td>
								<th width="25"  align="right">{#TEXT_33#}</th>
								<td width="80"  class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][bankcode]" value="{$ALL_DATA[DATA].bankcode|escape:htmlall}">{$ALL_DATA[DATA].bankcode|escape:htmlall}</td>
								<th width="110" align="right">{#TEXT_269#}</th>
								<td width="150" class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][name]" value="{$ALL_DATA[DATA].name|escape:htmlall}">{$ALL_DATA[DATA].name|escape:htmlall}</td>
								<th width="110" align="right">{#TEXT_270#}</th>
								<td width="190" class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][usage]" value="{$ALL_DATA[DATA].usage|escape:htmlall}">{$ALL_DATA[DATA].usage|escape:htmlall|nl2br}</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="9"><hr></td></tr>
					{assign var="elements" value="`$elements+15`"}
				{/section}
{literal}
<script language="JavaScript">
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
  for(var a=1 ; a <= numflows ; a ++) {
    initContractpartner(elementId + 8);
    changeColor(elementId);
    elementId+=15;
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
  
  function changeColor(elementId) {
    if( document.addmoney.elements[elementId+2].checked ) {
      var color="#00FF00";
    } else if (document.addmoney.elements[elementId+3].checked ) {
      var color="#FF0000";
    }
    
    var flowRow = Math.floor(elementId/15)*5+1;
    var col = document.addmoney.getElementsByTagName("tr");
    var cells = col[flowRow].cells;
    
    for( i = 0 ; i < cells.length ; i++ ) {
      cells[i].style.backgroundColor = color;
    }

    document.addmoney.elements[elementId+5].style.backgroundColor=color;
    document.addmoney.elements[elementId+6].style.backgroundColor=color;
    document.addmoney.elements[elementId+7].style.backgroundColor=color;
    document.addmoney.elements[elementId+8].style.backgroundColor=color;
    document.addmoney.elements[elementId+9].style.backgroundColor=color;
    document.addmoney.elements[elementId+10].style.backgroundColor=color;
    document.addmoney.elements[elementId+11].style.backgroundColor=color;
  }
</script>
{/literal}
			</table>
			<br>
			<input type="submit" value="{#TEXT_22#}">
		</form>
{else}
<br>
<p align="center">{#TEXT_285#}</p>
{/if}
		</td>
{$FOOTER}
