<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_3}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_3}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&amp;letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;sr=1','_blank','width=840,height=120')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0 align="center" cellpadding=2>
					<tr>
						<th width="85">{$TEXT_18}</th>
						<th width="130">{$TEXT_2}</th>
						<th width="240">{$TEXT_21}</th>
						<th width="180">{$TEXT_19}</th>
						<th width="20">{$TEXT_206}</th>
						<th width="80">{$TEXT_207}</th>
						<th width="20">&nbsp</th>
						<th width="20">&nbsp</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].contractpartner_name}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].capitalsource_comment}</td>
							<td class="contrastbgcolor">{if $ALL_DATA[DATA].once_a_month == 1}{$TEXT_25}{else}{$TEXT_26}{/if}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].last_used}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&amp;sr=1','_blank','width=800,height=120')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_predefmoneyflow&amp;predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&amp;sr=1','_blank','width=800,height=120')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
