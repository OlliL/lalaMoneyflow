<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow:  {if $ALL_DATA.id > 0}{$TEXT_116}{else}{$TEXT_115}{/if}</title>
{$HEADER}

		<td align="center">
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_currency">
			<input type="hidden" name="realaction"    value="save">
			<input type="hidden" name="id"            value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<table border=0>
				<tr>
					<th width="100">{$TEXT_107}</th>
					<th width="80" >{$TEXT_109}</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[currency]" value="{$ALL_DATA.currency}"                 size=10 /></td>
					<td class="contrastbgcolor">
						<select class="contrastbgcolor" name="all_data[att_default]" size=1>
							<option value=0 {if $ALL_DATA.att_default == 0}selected{/if} > {$TEXT_26}
							<option value=1 {if $ALL_DATA.att_default == 1}selected{/if} > {$TEXT_25}
						</select>
					</td>
				</tr>
			</table>
			<input type="submit" value="{$TEXT_22}">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
