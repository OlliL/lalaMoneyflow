<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_1#}</title>
{literal}
<script language="JavaScript">
	function mySubmit() {
		if(document.validForm.currently_valid_checkbox.checked) {
			document.validForm.currently_valid.value = "1";
		} else {
			document.validForm.currently_valid.value = "0";
		}
		document.validForm.submit();
	}
</script>
{/literal}
{$HEADER}

		<td align="center" valign="top">
			<h1>{#TEXT_1#}</h1>
			{if $LETTER eq "all" || ($LETTER eq null && $COUNT_ALL_DATA > 0)}
				{#TEXT_28#}
			{else}
				<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&amp;letter=all">{#TEXT_28#}</a>
			{/if}
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				{if $LETTER eq $ALL_INDEX_LETTERS[LETTER]}
					{$LETTER}
				{else}
					<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&amp;letter={$ALL_INDEX_LETTERS[LETTER]|escape:htmlall}">{$ALL_INDEX_LETTERS[LETTER]|escape:htmlall}</a>
				{/if}
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_29#}</a>
			<form action="{$ENV_INDEX_PHP}" method="GET" name="validForm">
				<input type="hidden"   name="action"                   value="list_capitalsources">
				<input type="hidden"   name="letter"                   value="{$LETTER}"          >		
				<input type="hidden"   name="currently_valid"          value="{$CURRENTLY_VALID}" >		
				<input type="checkbox" name="currently_valid_checkbox" onchange="mySubmit()" {if $CURRENTLY_VALID eq true}checked{/if}> {#TEXT_287#}</input>
			</form>
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="200">{#TEXT_21#}</th>
						<th width="80" >{#TEXT_30#}</th>
						<th width="40" >{#TEXT_31#}</th>
						<th width="100">{#TEXT_32#}</th>
						<th width="100">{#TEXT_33#}</th>
						<th width="60" >{#TEXT_34#}</th>
						<th width="60" >{#TEXT_35#}</th>
						<th width="50" >{#TEXT_210#}</th>
						<th width="50" >{#TEXT_282#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment|escape:htmlall}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].typecomment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].statecomment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].accountnumber}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].bankcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validfrom}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validtil}</td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].att_group_use == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].import_allowed == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
							{if $ALL_DATA[DATA].owner == true }
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_36#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_capitalsource&amp;capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&amp;sr=1','_blank','width=800,height=120')">{#TEXT_37#}</a></td>
							{/if}
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
