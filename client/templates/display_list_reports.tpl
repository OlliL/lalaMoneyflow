<html>
	<head><title>moneyflow: list reports</title>
{literal}
<script type="text/javascript">
<!--
function Go(x)
{
 if(x == "nothing")
 {
   document.reports_year.reset();
   document.reports_year.elements[0].blur();
   return;
 }
 else
  {
   location.href = x;
   document.reports_year.reset();
   document.reports_year.elements[0].blur();
  }
}
//-->
</script>
{/literal}

{$HEADER}

		<td align="center" valign="top" width=600>
<form action="#" method="get">
			<h1>list reports</h1>
			<table border="0" cellpadding=5>
				<tr>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="reports_year" size=1 onchange="Go(this.form.reports_year.options[this.form.reports_year.options.selectedIndex].value)">
					{section name=YEAR loop=$ALL_YEARS}
						<option {if $ALL_YEARS[YEAR] == $SELECTED_YEAR}selected{/if} value="{$ENV_INDEX_PHP}?action=list_reports&reports_year={$ALL_YEARS[YEAR]}"> {$ALL_YEARS[YEAR]}
					{/section}
					</select></td>
					{section name=MONTH loop=$ALL_MONTHS}
						<td class="contrastbgcolor">
							<a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$ALL_MONTHS[MONTH].nummeric}&reports_year={$SELECTED_YEAR}">{$ALL_MONTHS[MONTH].name}</a> 
						</td>
					{/section}
				</tr>
			</table>
</form>
{$REPORT}
		</td>
{$FOOTER}
