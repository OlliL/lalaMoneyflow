<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {$TEXT_2}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_2}</h1>
			<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter=all">{$TEXT_28}</a> 
			{section name=LETTER loop=$ALL_INDEX_LETTERS}
				<a href="{$ENV_INDEX_PHP}?action=list_contractpartners&amp;letter={$ALL_INDEX_LETTERS[LETTER]|escape:htmlall}">{$ALL_INDEX_LETTERS[LETTER]|escape:htmlall}</a> 
			{/section}
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;sr=1','_blank','width=800,height=120')">{$TEXT_29}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="150">{$TEXT_41}</th>
						<th width="200">{$TEXT_42}</th>
						<th width="50" >{$TEXT_43}</th>
						<th width="100">{$TEXT_44}</th>
						<th width="100">{$TEXT_45}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].name|escape:htmlall}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].street|escape:htmlall}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].postcode}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].town|escape:htmlall}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].country|escape:htmlall}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=800,height=120')">{$TEXT_36}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartner&amp;contractpartnerid={$ALL_DATA[DATA].contractpartnerid}&amp;sr=1','_blank','width=800,height=120')">{$TEXT_37}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
