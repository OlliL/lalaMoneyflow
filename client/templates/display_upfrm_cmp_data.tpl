<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><title>lalaMoneyflow: {#TEXT_187#}</title>
{$HEADER}

		<td align="center" valign="top">
		<h1>{#TEXT_187#}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<br>
		<form action="{$ENV_INDEX_PHP}" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="action" value="analyze_cmp_data">
			<table border=0>
				<tr>
					<th align="right">{#TEXT_192#}</th>
					<td>
						<table border=0>
							<tr>
								<th align="left"><input type="radio" value="0" name="all_data[use_imported_data]"> {#TEXT_193#}</th>
								<td>
									<select class="contrastbgcolor" name="all_data[format]" size=1>
									{section name=FORMAT loop=$FORMAT_VALUES}
										<option value="{$FORMAT_VALUES[FORMAT].formatid}" {if $FORMAT_VALUES[FORMAT].formatid == $ALL_DATA.format}selected{/if}>{$FORMAT_VALUES[FORMAT].name}</option>
									{/section}
									</select>
									<input class="contrastbgcolor" type=file size=30 maxlength=100000 name="file">
								</td>
							</tr>
							<tr>
								<th align="left"><input type="radio" value="1" name="all_data[use_imported_data]" checked> {#TEXT_282#}</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th align="right">{#TEXT_69#}</th>
					<td><input class="contrastbgcolor" type="text" name="all_data[startdate]" value="{$ALL_DATA.startdate}" {if $ALL_DATA.startdate_error == 1}style="color:red"{/if}></td>
				</tr>
				<tr>
					<th align="right">{#TEXT_70#}</th>
					<td><input class="contrastbgcolor" type="text" name="all_data[enddate]"   value="{$ALL_DATA.enddate}"   {if $ALL_DATA.enddate_error == 1}style="color:red"{/if}></td>
				</tr>
				<tr>
					<th>{#TEXT_19#}</th>
					<td>
					<select class="contrastbgcolor" name="all_data[mcs_capitalsourceid]" size=1>
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].capitalsourceid == $ALL_DATA.mcs_capitalsourceid}selected{/if} {if $ALL_DATA.capitalsource_error == 1}style="color:red"{/if}>{$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
						{/section}
						</select>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="{#TEXT_190#}">
		</form>
		</td>
{$FOOTER}
