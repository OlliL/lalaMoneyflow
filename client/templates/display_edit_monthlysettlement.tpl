<html>
	<head><title>moneyflow: monthly settlement</title>
{$HEADER}

		<td align="center">
		<h1>monthly settlement</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_monthlysettlement">
			month <select class="contrastbgcolor" name="month">
			<option {if $MONTH == 01}selected{/if}> 01
			<option {if $MONTH == 02}selected{/if}> 02
			<option {if $MONTH == 03}selected{/if}> 03
			<option {if $MONTH == 04}selected{/if}> 04
			<option {if $MONTH == 05}selected{/if}> 05
			<option {if $MONTH == 06}selected{/if}> 06
			<option {if $MONTH == 07}selected{/if}> 07
			<option {if $MONTH == 08}selected{/if}> 08
			<option {if $MONTH == 09}selected{/if}> 09
			<option {if $MONTH == 10}selected{/if}> 10
			<option {if $MONTH == 11}selected{/if}> 11
			<option {if $MONTH == 12}selected{/if}> 12
			</select>
			year <input class="contrastbgcolor" type="text" name="year" value="{$YEAR}" size=4"><br />
			<table border=0>
				<tr>
					<th>capital source</th>
					<th>amount</th>
				</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="hidden" name="id[{$ALL_DATA[DATA].id}]" value="1">{$ALL_DATA[DATA].comment}</td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="amount[{$ALL_DATA[DATA].id}]" value="{$ALL_DATA[DATA].amount|string_format:"%.2f"}" size=8 align="right"/></td>
					</tr>
				{/section}
			</table>
			<input type="submit" name="realaction" value="save">
			<input type="submit" name="realaction" value="load">
			<input type="submit" name="realaction" value="delete">
		</form>
		</td>
{$FOOTER}
