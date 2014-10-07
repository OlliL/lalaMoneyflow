<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_263#}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$CONTRACTPARTNER_NAME|escape:htmlall}: {#TEXT_263#}</h1>
			<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;sr=1','_blank','width=400,height=120')">{#TEXT_29#}</a> 
			{if $COUNT_ALL_DATA > 0}
				<br><br>
				<table border=0>
					<tr>
						<th width="215">{#TEXT_32#}</th>
						<th width="85">{#TEXT_33#}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].accountnumber|escape:htmlall}</td>
							<td class="contrastbgcolor">{$ALL_DATA[DATA].bankcode|escape:htmlall}</td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=delete_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;contractpartneraccountid={$ALL_DATA[DATA].contractpartneraccountid}&amp;sr=1','_blank','width=400,height=120')">{#TEXT_37#}</a></td>
							<td class="contrastbgcolor"><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartneraccount&amp;contractpartnerid={$CONTRACTPARTNERID}&amp;contractpartneraccountid={$ALL_DATA[DATA].contractpartneraccountid}&amp;sr=1','_blank','width=400,height=120')">{#TEXT_36#}</a></td>
						</tr>
					{/section}
				</table>
			{/if}
		</td>
{$FOOTER}
