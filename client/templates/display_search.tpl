<html>
	<head><title>lalaMoneyflow: {$TEXT_7}</title>
{$HEADER}

		<td align="center">
		<h1>{$TEXT_7}</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="do_search">
			<input type="hidden" name="realaction" value="search">
			{section name=ERROR loop=$ERRORS}
				<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
			{/section}
			<br />
			<table border=0>
				<tr>
					<th>{$TEXT_72}</th>
					<td class="contrastbgcolor">{$TEXT_21}</td>
				</tr>
				<tr>
					<th>{$TEXT_20}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="contractpartner" size=1>
					<option value=""> </option>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $SEARCHPARAMS.contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
				</tr>
				<tr>
					<th>{$TEXT_73}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="searchstring" value="{$SEARCHPARAMS.pattern}"></td>
				</tr>
				<tr>
					<th>{$TEXT_74}</th>
					<td class="contrastbgcolor">{$TEXT_16}</td>
				</tr>
				<tr>
					<th>{$TEXT_69}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="startdate" value="{$SEARCHPARAMS.startdate}"></td>
				</tr>
				<tr>
					<th>{$TEXT_70}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="enddate" value="{$SEARCHPARAMS.enddate}"></td>
				</tr>
				<tr>
					<th>{$TEXT_75}</th>
					<td class="contrastbgcolor">
						<input class="contrastbgcolor" type="checkbox" name="equal" {if $SEARCHPARAMS.equal == 1}checked{/if}> {$TEXT_76} <br />
						<input class="contrastbgcolor" type="checkbox" name="casesensitive" {if $SEARCHPARAMS.casesensitive == 1}checked{/if}> {$TEXT_77} <br />
						<input class="contrastbgcolor" type="checkbox" name="regexp" {if $SEARCHPARAMS.regexp == 1}checked{/if}> {$TEXT_78}<br />
						<input class="contrastbgcolor" type="checkbox" name="minus" {if $SEARCHPARAMS.minus == 1}checked{/if}> {$TEXT_79}<br />
					</td>
				</tr>
				<tr>
					<th>{$TEXT_80}</th>
					<td class="contrastbgcolor">{$TEXT_57}+{$TEXT_56}</td>
				</tr>
				

			</table>
			<br />
			<input type="submit" name="realaction" value="{$TEXT_83}">
		</form>
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					<th width="9%">{$TEXT_81}</th>
					<th width="9%">{$TEXT_82}</th>
					<th width="10%">{$TEXT_18}</th>
					<th >{$TEXT_21}</th>
				</tr>
				{section name=DATA loop=$RESULTS}
					<tr>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].year}</p></td>
						<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].month}</p></td>
						<td align="right" class="contrastbgcolor"><font {if $RESULTS[DATA].amount < 0}color="red"{else}color="black"{/if}>{$RESULTS[DATA].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$RESULTS[DATA].comment}</td>
					</tr>
				{/section}
			</table>
{$FOOTER}
