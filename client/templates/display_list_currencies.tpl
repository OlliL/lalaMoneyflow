<html>
	<head><title>lalaMoneyflow: {$TEXT_1}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_106}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_currencies&letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_currencies&letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_currency&sr=1','_blank','width=800,height=80')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br /><br />
				<table border=0>
					<tr>
						<th width="100">{$TEXT_107}</th>
						<th width="80" >{$TEXT_109}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].currency}</td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].att_default == 1}<font color="green">{$TEXT_25}{else}<font color="red">{$TEXT_26}{/if}</font></b></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_currency&currencyid={$ALL_DATA[DATA].currencyid}&sr=1','_blank','width=800,height=80')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_currency&currencyid={$ALL_DATA[DATA].currencyid}&sr=1','_blank','width=800,height=80')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
