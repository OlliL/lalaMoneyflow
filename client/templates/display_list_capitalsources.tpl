<html>
	<head><title>moneyflow: list capital sources</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>list capital sources</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&letter=all">all</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">add</a> 
			{if $COUNT_ALL_DATA > 0}
				<table border=0>
					<tr>
						<th width="200">comment</th>
						<th width="80">type</th>
						<th width="80">state</th>
						<th width="100">accountnumber</th>
						<th width="100">bankcode</th>
						<th width="60">valid from</th>
						<th width="60">valid til</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].type}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].state}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].accountnumber}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].bankcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validfrom}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validtil}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">edit</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_capitalsource&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">delete</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
