<html>
	<head><title>lalaMoneyflow: {$TEXT_89}</title>
{$HEADER}

		<td align="center">
		<h1>{$TEXT_89}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<br />
		<form action="{$ENV_INDEX_PHP}?action=settings" method="POST">
			<input type="hidden" name="action" value="settings">
			<input type="hidden" name="realaction" value="save">
			<table border=0>
				<tr>
					<th>{$TEXT_90}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="language" size=1>
					{section name=LANGUAGES loop=$LANGUAGE_VALUES}
						<option value="{$LANGUAGE_VALUES[LANGUAGES].id}" {if $LANGUAGE_VALUES[LANGUAGES].id == $LANGUAGE}selected{/if}> {$LANGUAGE_VALUES[LANGUAGES].language}</option>
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_91}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="currency" size=1>
					{section name=CURRENCIES loop=$CURRENCY_VALUES}
						<option value="{$CURRENCY_VALUES[CURRENCIES].id}" {if $CURRENCY_VALUES[CURRENCIES].id == $CURRENCY}selected{/if}> {$CURRENCY_VALUES[CURRENCIES].currency}</option>
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_86}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="password1" size=10 value="" /></td>
				</tr>
				<tr>
					<th>{$TEXT_92}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="password" name="password2" size=10 value="" /></td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}">
		</form>
		</td>
{$FOOTER}
