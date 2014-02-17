<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_184#}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{#TEXT_184#}</h1>
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action"     value="edit_language">
				<input type="hidden" name="realaction" value="save">
				<input type="hidden" name="languageid" value="{$LANGUAGEID}">
				{section name=ERROR loop=$ERRORS}
					<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
				{/section}
				<table border=0 width=650>
					<tr>
						<th width="450">{$LANG_ENG}</th>
						<th width="200">{$LANG}</th>
					</tr>
					{foreach name=texts key=id item=text from=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA_ENG[$id]}
							<input type="hidden" name="all_data[{$id}][orig_text]" value="{$text}">
							
							</td>
							<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$id}][text]" value="{$text}" size=50></td>
						</tr>
					{/foreach}
				</table>
				<input type="submit" value="{#TEXT_22#}">
				<input type="button" value="{#TEXT_23#}" onclick="history.back();">
			</form>
		</td>
{$FOOTER}
