<html>
	<head><title>moneyflow: add moneyflows</title>
{$HEADER}

		<td align="center">
		<h1>add moneyflows</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="save_moneyflows">
			<table border=0>
				<tr>
					<th>&nbsp;</th>
					<th>bookingdate</th>
					<th>invoicedate</th>
					<th>amount</th>
					<th>contractpartner</th>
					<th>comment</th>
					<th>capitalsource</th>
				</tr>
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="id[-1]" value=1></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="bookingdate[-1]" value="{$DATE}" size=10 /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="invoicedate[-1]" value="" size=10 /></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="amount[-1]" value="" size=8 align="right"/> EUR</td>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="contractpartnerid[-1]" size=1>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
						{/section}
						</select></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="comment[-1]" value="" size="50"/></td>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="capitalsourceid[-1]" size=1>
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
						{/section}
						</select></td>
					</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="id[{$ALL_DATA[DATA].id}]" value=1 {$ALL_DATA[DATA].checked}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="bookingdate[{$ALL_DATA[DATA].id}]" value="{$DATE}" size=10 /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="invoicedate[{$ALL_DATA[DATA].id}]" value="" size=10 /></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="amount[{$ALL_DATA[DATA].id}]" value="{$ALL_DATA[DATA].amount|string_format:"%.2f"}" size=8 align="right"/> EUR</td>
						<td class="contrastbgcolor"><input type="hidden" name="contractpartnerid[{$ALL_DATA[DATA].id}]" value="{$ALL_DATA[DATA].contractpartnerid}">{$ALL_DATA[DATA].contractpartnername}</td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="comment[{$ALL_DATA[DATA].id}]"   value="{$ALL_DATA[DATA].comment}" size="50"/></td>
						<td class="contrastbgcolor"><input type="hidden" name="capitalsourceid[{$ALL_DATA[DATA].id}]" value="{$ALL_DATA[DATA].capitalsourceid}">{$ALL_DATA[DATA].capitalsourcecomment}</td>
					</tr>
				{/section}
			</table>
			<input type="submit" name="realaction" value="save">
			<input type="submit" name="realaction" value="reload">
		</form>
		</td>
{$FOOTER}
