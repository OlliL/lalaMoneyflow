<html>
	<head><title>moneyflow: add moneyflows</title>
{$HEADER}

		<td align="center">
		<h1>add moneyflow</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="add_moneyflow">
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
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[-1][id]" value=1 ></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[-1][bookingdate]" value="{$DATE}" size=10 /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[-1][invoicedate]" value="" size=10 /></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[-1][amount]" value="" size=8 align="right" onchange="this.form.elements[1].checked=true"/> EUR</td>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[-1][contractpartnerid]" size=1>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
						{/section}
						</select></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[-1][comment]" value="" size="50"/></td>
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[-1][capitalsourceid]" size=1>
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}"> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}
						{/section}
						</select></td>
					</tr>
				{assign var=elements value="1"}
				{section name=DATA loop=$ALL_DATA}
					<tr>
						{assign var="elements" value="`$elements+7`"}
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$ALL_DATA[DATA].id}][id]" value=1 {$ALL_DATA[DATA].checked}></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][bookingdate]" value="{$DATE}" size=10 /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][invoicedate]" value="" size=10 /></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][amount]" value="{$ALL_DATA[DATA].amount|string_format:"%.2f"}" size=8 align="right" onchange="this.form.elements[{$elements}].checked=true"/> EUR</td>
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$ALL_DATA[DATA].id}][contractpartnerid]" value="{$ALL_DATA[DATA].contractpartnerid}">{$ALL_DATA[DATA].contractpartnername}</td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][comment]"   value="{$ALL_DATA[DATA].comment}" size="50"/></td>
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$ALL_DATA[DATA].id}][capitalsourceid]" value="{$ALL_DATA[DATA].capitalsourceid}">{$ALL_DATA[DATA].capitalsourcecomment}</td>
					</tr>
				{/section}
			</table>
			<input type="submit" name="realaction" value="save">
		</form>
		</td>
{$FOOTER}
