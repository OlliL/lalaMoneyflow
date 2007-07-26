<html>
	<head><title>lalaMoneyflow: {$TEXT_1}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_1}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_capitalsources&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br /><br />
				<table border=0>
					<tr>
						<th width="200">{$TEXT_21}</th>
						<th width="80" >{$TEXT_30}</th>
						<th width="80" >{$TEXT_31}</th>
						<th width="100">{$TEXT_32}</th>
						<th width="100">{$TEXT_33}</th>
						<th width="60" >{$TEXT_34}</th>
						<th width="60" >{$TEXT_35}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].comment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].typecomment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].statecomment}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].accountnumber}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].bankcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validfrom}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validtil}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&sr=1','_blank','width=800,height=80')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_capitalsource&capitalsourceid={$ALL_DATA[DATA].capitalsourceid}&sr=1','_blank','width=800,height=80')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
