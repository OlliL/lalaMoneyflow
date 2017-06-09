<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_2#}</title>
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
			<h1>{#TEXT_2#}</h1>
			{if $LETTER eq "all" || ($LETTER eq null && $COUNT_ALL_DATA > 0)}
				{#TEXT_28#}
			{else}
				<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter=all">{#TEXT_28#}</a>
			{/if}
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				{if $LETTER eq $ALL_INDEX_LETTERS[LETTER]}
					{$LETTER}
				{else}
					<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a>
				{/if}
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_29#}</a> 
			<form action="{$ENV_INDEX_PHP}" method="GET" name="validForm">
				<input type="hidden"   name="action"                   value="list_contractpartners">
				<input type="hidden"   name="letter"                   value="{$LETTER}"          >		
				<input type="hidden"   name="currently_valid"          value="{$CURRENTLY_VALID}" >		
				<input type="checkbox" name="currently_valid_checkbox" onchange="mySubmit()" {if $CURRENTLY_VALID eq true}checked{/if}> {#TEXT_287#}</input>
			</form>
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="150">{#TEXT_41#}</th>
						<th width="200">{#TEXT_42#}</th>
						<th width="50" >{#TEXT_43#}</th>
						<th width="100">{#TEXT_44#}</th>
						<th width="100">{#TEXT_45#}</th>
						<th width="60" >{#TEXT_34#}</th>
						<th width="60" >{#TEXT_35#}</th>
						<th width="100">{#TEXT_272#}</th>
						<th width="100">{#TEXT_232#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].street}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].postcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].town}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].country}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validfrom}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validtil}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].moneyflow_comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].mpa_postingaccountname}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=1000,height=150')">{#TEXT_37#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_36#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=list_contractpartneraccounts&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=500,height=350')">{#TEXT_263#}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
