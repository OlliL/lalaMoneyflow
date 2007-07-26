<html>
	<head><title>lalaMoneyflow: {$TEXT_3}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_3}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=840,height=80')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br /><br />
				<table border=0>
					<tr>
						<th width="80" >{$TEXT_18}</th>
						<th width="200">{$TEXT_2}</th>
						<th width="200">{$TEXT_21}</th>
						<th width="100">{$TEXT_19}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].contractpartner_name}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].capitalsource_comment}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&sr=1','_blank','width=800,height=80')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_predefmoneyflow&predefmoneyflowid={$ALL_DATA[DATA].predefmoneyflowid}&sr=1','_blank','width=800,height=80')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
