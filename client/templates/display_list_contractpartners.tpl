<html>
	<head><title>moneyflow: list contract partners</title>
{$HEADER}

		<td align="center" valign="top" width=600>
			<h1>list contract partners</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&letter=all">all</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&sr=1','_blank','width=800,height=80')">add</a> 
			{if $COUNT_ALL_DATA > 0}
				<table border=0 width=600>
					<tr>
						<th width="150">name</th>
						<th width="200">street</th>
						<th width="50">postcode</th>
						<th width="100">town</th>
						<th width="100">country</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name}</td>
							<td class="contrastbgcolor" wrap>{$ALL_DATA[DATA].street}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].postcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].town}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].country}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">edit</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartner&id={$ALL_DATA[DATA].id}&sr=1','_blank','width=800,height=80')">delete</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
