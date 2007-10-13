<html>
	<head><title>lalaMoneyflow: {$TEXT_185}</title>
{$HEADER}

		<td align="center" valign="top">
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action"     value="add_language">
				<input type="hidden" name="realaction" value="save">
				{section name=ERROR loop=$ERRORS}
					<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
				{/section}
				<table border=0>
					<tr>
						<th>{$TEXT_183}</th>
						<th>{$TEXT_186}</th>
					</tr>
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[language]" value="{$ALL_DATA.language}" size=50 /></td>
						<td ><select class="contrastbgcolor" name="all_data[source]" size=1>
						{section name=LANGUAGES loop=$LANGUAGE_VALUES}
							<option value="{$LANGUAGE_VALUES[LANGUAGES].languageid}" {if $LANGUAGE_VALUES[LANGUAGES].languageid == $ALL_DATA.source}selected{/if}> {$LANGUAGE_VALUES[LANGUAGES].language}</option>
						{/section}
						</select></td>
					</tr>
				</table>
				<input type="submit" value="{$TEXT_29}">
				<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
			</form>
		</td>
{$FOOTER}
