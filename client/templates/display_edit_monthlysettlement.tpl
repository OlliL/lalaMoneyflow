<html>
{if $CLOSE != 1}
	<head><title>moneyflow: monthly settlement</title>
{$HEADER}

		<td align="center">
		<h1>monthly settlement {$MONTH.name} {$YEAR}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_monthlysettlement">
			{if $NEW != 1 }
				<input type="hidden" name="monthlysettlements_month" value="{$MONTH.nummeric}">
				<input type="hidden" name="monthlysettlements_year" value="{$YEAR}">
			{else}
				<input type="hidden" name="all_data[new]" value="1">
				month <select class="contrastbgcolor" name="monthlysettlements_month">
				<option {if $MONTH.nummeric == 01}selected{/if}> 01
				<option {if $MONTH.nummeric == 02}selected{/if}> 02
				<option {if $MONTH.nummeric == 03}selected{/if}> 03
				<option {if $MONTH.nummeric == 04}selected{/if}> 04
				<option {if $MONTH.nummeric == 05}selected{/if}> 05
				<option {if $MONTH.nummeric == 06}selected{/if}> 06
				<option {if $MONTH.nummeric == 07}selected{/if}> 07
				<option {if $MONTH.nummeric == 08}selected{/if}> 08
				<option {if $MONTH.nummeric == 09}selected{/if}> 09
				<option {if $MONTH.nummeric == 10}selected{/if}> 10
				<option {if $MONTH.nummeric == 11}selected{/if}> 11
				<option {if $MONTH.nummeric == 12}selected{/if}> 12
				</select>
				year <input class="contrastbgcolor" type="text" name="monthlysettlements_year" value="{$YEAR}" size=4">
				<input type="submit" name="realaction" value="reload"><br />
			{/if}
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0 width="350" cellpadding=2>
				<tr>
					<th>capital source</th>
					<th width="30%">amount</th>
				</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="hidden" name="all_data[{$ALL_DATA[DATA].id}][id]" value="{$ALL_DATA[DATA].id}">{$ALL_DATA[DATA].comment}</td>
						<td class="contrastbgcolor" align="right"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][amount]" value="{$ALL_DATA[DATA].amount|string_format:"%.2f"}" size=8 align="right"/> EUR</font></td>
					</tr>
				{/section}
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
