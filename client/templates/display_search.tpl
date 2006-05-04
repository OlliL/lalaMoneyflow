<html>
	<head><title>moneyflow: search moneyflows</title>
{$HEADER}

		<td align="center">
		<h1>search moneyflows</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="do_search">
			<table border=0>
				<tr>
					<th>searched field</th>
					<td class="contrastbgcolor">comment</td>
				</tr>
				<tr>
					<th>string to search</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="searchstring" value="{$SEARCHPARAMS.pattern}"></td>
				</tr>
				<tr>
					<th>type of date</th>
					<td class="contrastbgcolor">bookingdate</td>
				</tr>
				<tr>
					<th>startdate</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="startdate" value="{$SEARCHPARAMS.startdate}"></td>
				</tr>
				<tr>
					<th>enddate</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="enddate" value="{$SEARCHPARAMS.enddate}"></td>
				</tr>
				<tr>
					<th>special features</th>
					<td class="contrastbgcolor">
						<input class="contrastbgcolor" type="checkbox" name="equal" {if $SEARCHPARAMS.equal == 1}checked{/if}> equal <br />
						<input class="contrastbgcolor" type="checkbox" name="casesensitive" {if $SEARCHPARAMS.casesensitive == 1}checked{/if}> case sensitive <br />
						<input class="contrastbgcolor" type="checkbox" name="regexp" {if $SEARCHPARAMS.regexp == 1}checked{/if}> regular expression<br />
						<input class="contrastbgcolor" type="checkbox" name="minus" {if $SEARCHPARAMS.minus == 1}checked{/if}> only negative amount<br />
					</td>
				</tr>
				<tr>
					<th>group by</th>
					<td class="contrastbgcolor">year+month</td>
				</tr>
				

			</table>
			<input type="submit" name="realaction" value="search">
		</form>
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%">bookingyear</th>
					<th width="9%">bookingmonth</th>
					<th width="10%">amount</th>
					<th >comment</th>
				</tr>
				{section name=DATA loop=$RESULTS}
					<tr>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].year}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].month}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $RESULTS[DATA].amount < 0}color="red"{else}color="black"{/if}>{$RESULTS[DATA].amount|number_format} EUR</font></td>
						<td class="contrastbgcolor">{$RESULTS[DATA].comment}</td>
					</tr>
				{/section}
			</table>
{$FOOTER}
