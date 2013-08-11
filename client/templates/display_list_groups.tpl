<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_212}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_212}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_groups&amp;letter=all">{$TEXT_28}</a>
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_groups&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a>
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_group&amp;sr=1','_blank','width=230,height=200')">{$TEXT_29}</a>
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="200">{$TEXT_210}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_group&amp;groupid={$ALL_DATA[DATA].groupid}&amp;sr=1','_blank','width=230,height=200')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_group&amp;groupid={$ALL_DATA[DATA].groupid}&amp;sr=1','_blank','width=800,height=120')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
