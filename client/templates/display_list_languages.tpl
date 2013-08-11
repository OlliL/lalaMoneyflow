<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_182}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_182}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_languages&amp;letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_languages&amp;letter={$ALL_INDEX_LETTERS[LETTER]}">{$ALL_INDEX_LETTERS[LETTER]}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=add_language&amp;sr=1','_blank','width=800,height=120')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="100">{$TEXT_183}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].language}</td>
							<td class="contrastbgcolor"><a href="{$ENV_INDEX_PHP}?action=edit_language&amp;languageid={$ALL_DATA[DATA].languageid}">{$TEXT_36}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
