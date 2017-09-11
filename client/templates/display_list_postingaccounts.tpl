<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_247#}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{#TEXT_247#}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_postingaccounts&amp;letter=all">{#TEXT_28#}</a>
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_postingaccounts&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a>
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_postingaccount&amp;sr=1','_blank','width=600,height=230')">{#TEXT_29#}</a>
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="200">{#TEXT_232#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_postingaccount&amp;postingaccountid={$ALL_DATA[DATA].postingaccountid}&amp;sr=1','_blank','width=600,height=230')">{#TEXT_36#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_postingaccount&amp;postingaccountid={$ALL_DATA[DATA].postingaccountid}&amp;sr=1','_blank','width=300,height=130')">{#TEXT_37#}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
