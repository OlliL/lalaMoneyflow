<html>
	<head><title>lalaMoneyflow: list monthly settlements</title>
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

		<td align="center" valign="top">
			<form action="#" method="get">
			<h1>list monthly settlements</h1>
			<table border="0" cellpadding=5>
				<tr>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&sr=1','_blank','width=500,height=300')">add</a></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="monthlysettlements_year" size=1 onchange="Go(this.form.monthlysettlements_year.options[this.form.monthlysettlements_year.options.selectedIndex].value)">
					{section name=YEAR loop=$ALL_YEARS}
						<option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_monthlysettlements&monthlysettlements_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
					{/section}
					</select></td>
					{section name=MONTH loop=$ALL_MONTHS}
						<td class="contrastbgcolor">
							<a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements&monthlysettlements_month={$ALL_MONTHS[MONTH].nummeric}&monthlysettlements_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a> 
						</td>
					{/section}
				</tr>
			</table>
			</form>
			{if $COUNT_ALL_DATA > 0}
				<h1>monthly settlement {$MONTH.name} {$YEAR}</h1>
				<table border=0 width="300" cellpadding=2>
					<tr>
						<th>capital source</th>
						<th width="30%">amount</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
						</tr>
					{/section}
					<tr>
						<td align="right">&sum;</td>
						<td align="right" class="contrastbgcolor"><font {if $SUMAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$SUMAMOUNT|number_format} {$CURRENCY}</u></font></td>
					</tr>
				</table>
				<table border="0" cellpadding=2>
					<tr>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&monthlysettlements_month={$MONTH.nummeric}&monthlysettlements_year={$YEAR}&sr=1','_blank','width=500,height=300')">edit</a></td>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_monthlysettlement&monthlysettlements_month={$MONTH.nummeric}&monthlysettlements_year={$YEAR}&sr=1','_blank','width=500,height=250')">delete</a></td>
					</tr>
				</table>
			{/if}
		</td>
{$FOOTER}
