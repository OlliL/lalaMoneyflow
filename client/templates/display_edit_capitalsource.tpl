<html>
{if $CLOSE != 1}
	<head><title>moneyflow: {if $ALL_DATA.id > 0}edit{else}add{/if} capital source</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="edit_capitalsource">
			<input type="hidden" name="id"      value="{$ALL_DATA.id}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0>
				<tr>
					<th>comment</th>
					<th>type</th>
					<th>state</th>
					<th>accountnumber</th>
					<th>bankcode</th>
					<th>valid from</th>
					<th>valid til</th>
				</tr>
				<tr>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[comment]"       value="{$ALL_DATA.comment}" /></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[type]"  size=1>
					{section name=TYPE loop=$TYPE_VALUES}
						<option {if $TYPE_VALUES[TYPE]   == $ALL_DATA.type}selected{/if} > {$TYPE_VALUES[TYPE]}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[state]" size=1>
					{section name=STATE loop=$STATE_VALUES}
						<option {if $STATE_VALUES[STATE] == $ALL_DATA.state}selected{/if}> {$STATE_VALUES[STATE]}
					{/section}
					</select></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[accountnumber]" value="{$ALL_DATA.accountnumber}" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[bankcode]"      value="{$ALL_DATA.bankcode}" /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[validfrom]"     value="{$ALL_DATA.validfrom}" size=8 /></td>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[validtil]"      value="{$ALL_DATA.validtil}" size=8 /></td>
				</tr>
			</table>
			<input type="submit" name="realaction" value="save">
			<input type="button" value="cancel" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
