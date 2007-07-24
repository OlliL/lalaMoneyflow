<html>
	<head><title>lalaMoneyflow: {$TEXT_93}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{$TEXT_93}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="system_settings">
			<input type="hidden" name="realaction" value="save">
			<table border=0>
				<tr>
					<th>{$TEXT_90}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="language" size=1>
					{section name=LANGUAGES loop=$LANGUAGE_VALUES}
						<option value="{$LANGUAGE_VALUES[LANGUAGES].languageid}" {if $LANGUAGE_VALUES[LANGUAGES].languageid == $LANGUAGE}selected{/if}> {$LANGUAGE_VALUES[LANGUAGES].language}</option>
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_91}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="currency" size=1>
					{section name=CURRENCIES loop=$CURRENCY_VALUES}
						<option value="{$CURRENCY_VALUES[CURRENCIES].currencyid}" {if $CURRENCY_VALUES[CURRENCIES].currencyid == $CURRENCY}selected{/if}> {$CURRENCY_VALUES[CURRENCIES].currency}</option>
					{/section}
					</select></td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}">
		</form>
		</td>
{$FOOTER}
