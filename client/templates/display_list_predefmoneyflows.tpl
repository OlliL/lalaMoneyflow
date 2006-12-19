<html>
	<head><title>lalaMoneyflow: list predefined moneyflows</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>predefined moneyflows</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&letter=all">all</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=800,height=80')">add</a> 
			{if $COUNT_ALL_DATA > 0}
				<table border=0>
					<tr>
						<th width="80">amount</th>
						<th width="100">capital source</th>
						<th width="200">contract partner</th>
						<th width="200">comment</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {$CURRENCY}</font></td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].capitalsource_comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].contractpartner_name}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">edit</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_predefmoneyflow&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">delete</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
