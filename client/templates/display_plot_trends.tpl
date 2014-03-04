<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_6#}</title>
{$HEADER}
		<td align="center" valign="top">
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action" value="plot_trends">
				<input type="hidden" name="realaction" value="plot">
				<table border=0>
					<tr>
						<td align="center">
							<h1>{#TEXT_6#}</h1>
							<table>
					<tr>
						<th>{#TEXT_69#}</th>
						<td class="contrastbgcolor">
							<select class="contrastbgcolor" name="all_data[startmonth]" size=1>
		 					{section name=MONTH loop=$MONTHS}
								<option value="{$MONTHS[MONTH].value}" {if $MONTHS[MONTH].value == $ALL_DATA.startmonth}selected{/if}> {$MONTHS[MONTH].text}
							{/section}
							</select>
							<select class="contrastbgcolor" name="all_data[startyear]" size=1>
							{section name=YEAR loop=$ALL_YEARS}
								<option value="{$ALL_YEARS[YEAR]}" {if $ALL_YEARS[YEAR] == $ALL_DATA.startyear}selected{/if}> {$ALL_YEARS[YEAR]}
							{/section}
							</select>
						</td>
					</tr>
					<tr>
						<th>{#TEXT_70#}</th>
						<td class="contrastbgcolor">
							<select class="contrastbgcolor" name="all_data[endmonth]" size=1>
		 					{section name=MONTH loop=$MONTHS}
								<option value="{$MONTHS[MONTH].value}" {if $MONTHS[MONTH].value == $ALL_DATA.endmonth}selected{/if}> {$MONTHS[MONTH].text}
							{/section}
							</select>
							<select class="contrastbgcolor" name="all_data[endyear]" size=1>
								{section name=YEAR loop=$ALL_YEARS}
								<option value="{$ALL_YEARS[YEAR]}" {if $ALL_YEARS[YEAR] == $ALL_DATA.endyear}selected{/if}> {$ALL_YEARS[YEAR]}
							{/section}
							</select>
						</td>
					</tr>
					<tr>
						<th>{#TEXT_19#}</th>
						<td class="contrastbgcolor">
							<select name="all_data[mcs_capitalsourceid][]" size="5" multiple>
							{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
								<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}"
								{foreach $ALL_DATA.mcs_capitalsourceid as $id}
									{if $id == $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}selected{/if}
								{/foreach}
								>{$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
							{/section}
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<br>
							<input type="submit" value="{#TEXT_71#}">
						</td>
					</tr>
							</table>
						</td>
					</tr>
					{if $PLOT_GRAPH == 1}
					<tr>
						<td align="center">
							<br>
							<img border="0" alt="trend" src="{$ENV_INDEX_PHP}?action=plot_graph{assign var="checkedids" value=$ALL_DATA.mcs_capitalsourceid}{foreach $checkedids as $capitalsourceid}&amp;id[]={$capitalsourceid}{/foreach}
&amp;startmonth={$ALL_DATA.startmonth}&amp;startyear={$ALL_DATA.startyear}&amp;endmonth={$ALL_DATA.endmonth}&amp;endyear={$ALL_DATA.endyear}">
						</td>
					</tr>
					{/if}
				</table>
			</form>
		</td>
{$FOOTER}
