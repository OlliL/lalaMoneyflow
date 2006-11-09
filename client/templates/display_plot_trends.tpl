<html>
	<head><title>moneyflow: plot trends</title>
{$HEADER}
		<td align="center" valign="top" width=600>
			<h1>plot trends</h1>
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action" value="plot_trends">
				<table>
					<tr>
				<tr>
					<th>startdate</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[startmonth]" size=1>
							<option value="1"  {if 1 == $ALL_DATA.startmonth}selected{/if}>Jan</option>
							<option value="2"  {if 2 == $ALL_DATA.startmonth}selected{/if}>Feb</option>
							<option value="3"  {if 3 == $ALL_DATA.startmonth}selected{/if}>Mar</option>
							<option value="4"  {if 4 == $ALL_DATA.startmonth}selected{/if}>Apr</option>
							<option value="5"  {if 5 == $ALL_DATA.startmonth}selected{/if}>May</option>
							<option value="6"  {if 6 == $ALL_DATA.startmonth}selected{/if}>Jun</option>
							<option value="7"  {if 7 == $ALL_DATA.startmonth}selected{/if}>Jul</option>
							<option value="8"  {if 8 == $ALL_DATA.startmonth}selected{/if}>Aug</option>
							<option value="9"  {if 9 == $ALL_DATA.startmonth}selected{/if}>Sep</option>
							<option value="10" {if 10 == $ALL_DATA.startmonth}selected{/if}>Oct</option>
							<option value="11" {if 11 == $ALL_DATA.startmonth}selected{/if}>Nov</option>
							<option value="12" {if 12 == $ALL_DATA.startmonth}selected{/if}>Dec</option>
						</select>
						<select class="contrastbgcolor" name="all_data[startyear]" size=1>
							{section name=YEAR loop=$ALL_YEARS}
							<option value="{$ALL_YEARS[YEAR]}" {if $ALL_YEARS[YEAR] == $ALL_DATA.startyear}selected{/if}> {$ALL_YEARS[YEAR]}
						{/section}
						</select>
					</td>
				</tr>
				<tr>
					<th>enddate</th>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[endmonth]" size=1>
							<option value="1"  {if 1 == $ALL_DATA.endmonth}selected{/if}>Jan</option>
							<option value="2"  {if 2 == $ALL_DATA.endmonth}selected{/if}>Feb</option>
							<option value="3"  {if 3 == $ALL_DATA.endmonth}selected{/if}>Mar</option>
							<option value="4"  {if 4 == $ALL_DATA.endmonth}selected{/if}>Apr</option>
							<option value="5"  {if 5 == $ALL_DATA.endmonth}selected{/if}>May</option>
							<option value="6"  {if 6 == $ALL_DATA.endmonth}selected{/if}>Jun</option>
							<option value="7"  {if 7 == $ALL_DATA.endmonth}selected{/if}>Jul</option>
							<option value="8"  {if 8 == $ALL_DATA.endmonth}selected{/if}>Aug</option>
							<option value="9"  {if 9 == $ALL_DATA.endmonth}selected{/if}>Sep</option>
							<option value="10" {if 10 == $ALL_DATA.endmonth}selected{/if}>Oct</option>
							<option value="11" {if 11 == $ALL_DATA.endmonth}selected{/if}>Nov</option>
							<option value="12" {if 12 == $ALL_DATA.endmonth}selected{/if}>Dec</option>
						</select>
						<select class="contrastbgcolor" name="all_data[endyear]" size=1>
							{section name=YEAR loop=$ALL_YEARS}
							<option value="{$ALL_YEARS[YEAR]}" {if $ALL_YEARS[YEAR] == $ALL_DATA.endyear}selected{/if}> {$ALL_YEARS[YEAR]}
						{/section}
						</select>
					</td>
				</tr>
						<th>capitalsource</th>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[capitalsourceid]" size=1>
							<option value="0">all</option>
							{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_DATA.capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
							{/section}
						</select></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<br />
							<input type="submit" name="realaction" value="plot">
						</td>
					</tr>
				</table>
				<br />
				{if $PLOT_GRAPH == 1}
				<center>
				 <img border="0" src="{$ENV_INDEX_PHP}?action=plot_graph&id={$ALL_DATA.capitalsourceid}&startmonth={$ALL_DATA.startmonth}&startyear={$ALL_DATA.startyear}&endmonth={$ALL_DATA.endmonth}&endyear={$ALL_DATA.endyear}" />
				</center>
				{/if}
			</form>
		</td>
{$FOOTER}
