<html>
	<head><title>lalaMoneyflow: {$TEXT_184}</title>
{$HEADER}

		<td align="center" valign="top">
			<h1>{$TEXT_184}</h1>
			<form action="{$ENV_INDEX_PHP}" method="POST">
				<input type="hidden" name="action"     value="edit_language">
				<input type="hidden" name="realaction" value="save">
				<input type="hidden" name="languageid" value="{$LANGUAGEID}">
				{section name=ERROR loop=$ERRORS}
					<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
				{/section}
				<table border=0 width=650>
					<tr>
						<th width="450">{$LANG_ENG}</th>
						<th width="200">{$LANG}</th>
					</tr>
					{section name=DATA loop=$ALL_DATA}
						<tr>
							<td class="contrastbgcolor">{$ALL_DATA_ENG[DATA].text}
							<input type="hidden" name="all_data[{$ALL_DATA[DATA].textid}][orig_text]" value="{$ALL_DATA[DATA].text}" />
							
							</td>
							<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].textid}][text]" value="{$ALL_DATA[DATA].text}" size=50 /></td>
						</tr>
					{/section}
				</table>
				<input type="submit" value="{$TEXT_22}">
				<input type="button" value="{$TEXT_23}" onclick="history.back();">
			</form>
		</td>
{$FOOTER}
