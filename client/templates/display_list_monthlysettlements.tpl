<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_4#}</title>
{literal}
<script type="text/javascript">
<!--
function Go(x)
{
 if(x == "nothing")
 {
   document.monthlysettlements_year.reset();
   document.monthlysettlements_year.elements[0].blur();
   return;
 }
 else
  {
   location.href = x;
   document.monthlysettlements.reset();
   document.monthlysettlements.elements[0].blur();
  }
}
//-->
</script>
{/literal}

{$HEADER}
{math equation="y * x + z" y=$NUM_ADDABLE_SETTLEMENTS x=35 z=135 assign=ADD_WIN_HEIGHT}
		<td align="center" valign="top">
			<form action="#" method="get">
			<h1>{#TEXT_4#}</h1>
			<table border="0" cellpadding=5>
				<tr>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;sr=1','_blank','width=500,height={$ADD_WIN_HEIGHT}')">{#TEXT_29#}</a></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="monthlysettlements_year" size=1 onchange="Go(this.form.monthlysettlements_year.options[this.form.monthlysettlements_year.options.selectedIndex].value)">
					{section name=YEAR loop=$ALL_YEARS}
						<option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_monthlysettlements&amp;monthlysettlements_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
					{/section}
					</select></td>
					{section name=MONTH loop=$ALL_MONTHS}
						<td class="contrastbgcolor">
							{if $ALL_MONTHS[MONTH].nummeric ne $SELECTED_MONTH}
							<a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements&amp;monthlysettlements_month={$ALL_MONTHS[MONTH].nummeric}&amp;monthlysettlements_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a> 
							{else}
							{$ALL_MONTHS[MONTH].name}
							{/if}
						</td>
					{/section}
				</tr>
			</table>
			</form>
			{if $COUNT_ALL_DATA > 0}
{math equation="y * x + z" y=$NUM_EDITABLE_SETTLEMENTS x=35 z=135 assign=EDIT_WIN_HEIGHT}
				<br>
				<h1>{#TEXT_53#} {$MONTH.name} {$YEAR}</h1>
				<table border=0 width="300" cellpadding=2>
					<tr>
						<th>{#TEXT_19#}</th>
						<th width="30%">{#TEXT_18#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].capitalsourcecomment}</td>
							<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
						</tr>
					{/section}
					<tr>
						<td align="right">&sum;</td>
						<td align="right" class="contrastbgcolor"><font {if $SUMAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$SUMAMOUNT|number_format} {$CURRENCY}</u></font></td>
					</tr>
				</table>
				<br>
				<table border="0" cellpadding=2>
					<tr>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;monthlysettlements_month={$MONTH.nummeric}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=500,height={$EDIT_WIN_HEIGHT}')">{#TEXT_36#}</a></td>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_monthlysettlement&amp;monthlysettlements_month={$MONTH.nummeric}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=500,height={$EDIT_WIN_HEIGHT}')">{#TEXT_37#}</a></td>
					</tr>
				</table>
			{/if}
		</td>
{$FOOTER}
