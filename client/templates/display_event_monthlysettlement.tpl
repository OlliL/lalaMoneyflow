<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_204#}</title>
{$HEADER}
{math equation="y * x + z" y=$NUM_ADDABLE_SETTLEMENTS x=35 z=135 assign=ADD_WIN_HEIGHT}
		<td align="center" valign="middle">
			<form action="#" method="get">
			<p>{#TEXT_205#}</p>
			<table border="0" cellpadding=5>
				<tr>
					<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_monthlysettlement&amp;monthlysettlements_month={$MONTH}&amp;monthlysettlements_year={$YEAR}&amp;sr=1','_blank','width=500,height={$ADD_WIN_HEIGHT}')">{#TEXT_25#}</a></td>
					<td class="contrastbgcolor"><a href="{$REFFERER}">{#TEXT_26#}</a></td>
				</tr>
			</table>
		</td>
{$FOOTER}
