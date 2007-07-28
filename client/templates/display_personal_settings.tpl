<html>
	<head><title>lalaMoneyflow: {$TEXT_89}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{$TEXT_89}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="personal_settings">
			<input type="hidden" name="realaction" value="save">
			<table border="0" width="500">
				<tr>
					<th>{$TEXT_90}</th>
					<td width=300><select class="contrastbgcolor" name="all_data[language]" size=1>
					{section name=LANGUAGES loop=$LANGUAGE_VALUES}
						<option value="{$LANGUAGE_VALUES[LANGUAGES].languageid}" {if $LANGUAGE_VALUES[LANGUAGES].languageid == $ALL_DATA.language}selected{/if}> {$LANGUAGE_VALUES[LANGUAGES].language}</option>
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_91}</th>
					<td width=300><select class="contrastbgcolor" name="all_data[currency]" size=1>
					{section name=CURRENCIES loop=$CURRENCY_VALUES}
						<option value="{$CURRENCY_VALUES[CURRENCIES].currencyid}" {if $CURRENCY_VALUES[CURRENCIES].currencyid == $ALL_DATA.currency}selected{/if}> {$CURRENCY_VALUES[CURRENCIES].currency}</option>
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_178}</th>
					<td width=300>
						<select class="contrastbgcolor" name="all_data[date_data1]" size=1>
							<option value="DD"   {if $ALL_DATA.date_data1      ==   "DD"}selected{/if}>{$TEXT_179}</option>
							<option value="MM"   {if $ALL_DATA.date_data1      ==   "MM"}selected{/if}>{$TEXT_56}</option>
							<option value="YYYY" {if $ALL_DATA.date_data1      == "YYYY"}selected{/if}>{$TEXT_57}</option>
						</select>
						<select class="contrastbgcolor" name="all_data[date_delimiter1]" size=1>
							<option value="."    {if $ALL_DATA.date_delimiter1 ==    "."}selected{/if}>.</option>
							<option value="-"    {if $ALL_DATA.date_delimiter1 ==    "-"}selected{/if}>-</option>
						</select>
						<select class="contrastbgcolor" name="all_data[date_data2]" size=1>
							<option value="DD"   {if $ALL_DATA.date_data2      ==   "DD"}selected{/if}>{$TEXT_179}</option>
							<option value="MM"   {if $ALL_DATA.date_data2      ==   "MM"}selected{/if}>{$TEXT_56}</option>
							<option value="YYYY" {if $ALL_DATA.date_data2      == "YYYY"}selected{/if}>{$TEXT_57}</option>
						</select>
						<select class="contrastbgcolor" name="all_data[date_delimiter2]" size=1>
							<option value="."    {if $ALL_DATA.date_delimiter2 ==    "."}selected{/if}>.</option>
							<option value="-"    {if $ALL_DATA.date_delimiter2 ==    "-"}selected{/if}>-</option>
						</select>
						<select class="contrastbgcolor" name="all_data[date_data3]" size=1>
							<option value="DD"   {if $ALL_DATA.date_data3      ==   "DD"}selected{/if}>{$TEXT_179}</option>
							<option value="MM"   {if $ALL_DATA.date_data3      ==   "MM"}selected{/if}>{$TEXT_56}</option>
							<option value="YYYY" {if $ALL_DATA.date_data3      == "YYYY"}selected{/if}>{$TEXT_57}</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>{$TEXT_177}</th>
					<td width=300><input class="contrastbgcolor" type="text" name="all_data[maxrows]" size="10" value="{$ALL_DATA.maxrows}" /></td>
				</tr>
				<tr>
					<th>{$TEXT_86}</th>
					<td width=300><input class="contrastbgcolor" type="password" name="all_data[password1]" size=10 value="" /></td>
				</tr>
				<tr>
					<th>{$TEXT_92}</th>
					<td width=300><input class="contrastbgcolor" type="password" name="all_data[password2]" size=10 value="" /></td>
				</tr>
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}">
		</form>
		</td>
{$FOOTER}
