<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_185#}</title>
{$HEADER}

		<td align="center" valign="top">
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action"     value="add_language">
				<input type="hidden" name="realaction" value="save">
				<input type="hidden" name="REFERER"          value="{$ENV_REFERER}">
				{section name=ERROR loop=$ERRORS}
					<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
				{/section}
				<table border=0>
					<tr>
						<th>{#TEXT_183#}</th>
						<th>{#TEXT_186#}</th>
					</tr>
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[language]" value="{$ALL_DATA.language}" size=50></td>
						<td ><select class="contrastbgcolor" name="all_data[source]" size=1>
						{section name=LANGUAGES loop=$LANGUAGE_VALUES}
							<option value="{$LANGUAGE_VALUES[LANGUAGES].languageid}" {if $LANGUAGE_VALUES[LANGUAGES].languageid == $ALL_DATA.source}selected{/if}> {$LANGUAGE_VALUES[LANGUAGES].language}</option>
						{/section}
						</select></td>
					</tr>
				</table>
				<input type="submit" value="{#TEXT_29#}">
				<input type="button" value="{#TEXT_23#}" onclick="javascript:void self.close();">
			</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
