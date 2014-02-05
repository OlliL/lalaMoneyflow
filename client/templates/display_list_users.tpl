<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_94#}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{#TEXT_94#}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_users&amp;letter=all">{#TEXT_28#}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_users&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_user&amp;sr=1','_blank','width=230,height=200')">{#TEXT_29#}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="200">{#TEXT_85#}</th>
						<th width="40" >{#TEXT_96#}</th>
						<th width="40" >{#TEXT_97#}</th>
						<th width="40" >{#TEXT_98#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name}</td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].perm_login == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].perm_admin == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
							<td class="contrastbgcolor" align="center"><b>{if $ALL_DATA[DATA].att_new == 1}<font color="green">{#TEXT_25#}{else}<font color="red">{#TEXT_26#}{/if}</font></b></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_user&amp;userid={$ALL_DATA[DATA].userid}&amp;sr=1','_blank','width=230,height=200')">{#TEXT_36#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_user&amp;userid={$ALL_DATA[DATA].userid}&amp;sr=1','_blank','width=800,height=120')">{#TEXT_37#}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
