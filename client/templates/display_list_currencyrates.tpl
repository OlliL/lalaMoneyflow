<html>
	<head><title>lalaMoneyflow: {$TEXT_110}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_110}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_currencyrates&letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_currencyrates&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_currencyrate&sr=1','_blank','width=800,height=80')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br /><br />
				<table border=0>
					<tr>
						<th width="100">{$TEXT_107}</th>
						<th width="80" >{$TEXT_108}</th>
						<th width="60" >{$TEXT_34}</th>
						<th width="60" >{$TEXT_35}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].currency}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].rate}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validfrom}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].validtil}</td>
							{if $ALL_DATA[DATA].att_past == 0}<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_currencyrate&mcu_currencyid={$ALL_DATA[DATA].mcu_currencyid}&validfrom={$ALL_DATA[DATA].validfrom}&sr=1','_blank','width=800,height=80')">{$TEXT_36}</a></td>{/if}
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
