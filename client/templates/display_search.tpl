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
					<th align="right">{$TEXT_72}</th>
					<td class="contrastbgcolor">{$TEXT_21}</td>
				</tr>
				<tr>
					<th align="right">{$TEXT_2}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="contractpartner" size=1>
					<option value=""> </option>
					{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
						<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid}"  {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].contractpartnerid == $SEARCHPARAMS.mcp_contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}
					{/section}
					</select></td>
				</tr>
				<tr>
					<th align="right">{$TEXT_73}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="searchstring" value="{$SEARCHPARAMS.pattern}"></td>
				</tr>
				<tr>
					<th align="right">{$TEXT_74}</th>
					<td class="contrastbgcolor">{$TEXT_16}</td>
				</tr>
				<tr>
					<th align="right">{$TEXT_69}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="startdate" value="{$SEARCHPARAMS.startdate}"></td>
				</tr>
				<tr>
					<th align="right">{$TEXT_70}</th>
					<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="enddate" value="{$SEARCHPARAMS.enddate}"></td>
				</tr>
				<tr>
					<th align="right">{$TEXT_75}</th>
					<td class="contrastbgcolor">
						<input class="contrastbgcolor" type="checkbox" name="equal" {if $SEARCHPARAMS.equal == 1}checked{/if}> {$TEXT_76} <br />
						<input class="contrastbgcolor" type="checkbox" name="casesensitive" {if $SEARCHPARAMS.casesensitive == 1}checked{/if}> {$TEXT_77} <br />
						<input class="contrastbgcolor" type="checkbox" name="regexp" {if $SEARCHPARAMS.regexp == 1}checked{/if}> {$TEXT_78}<br />
						<input class="contrastbgcolor" type="checkbox" name="minus" {if $SEARCHPARAMS.minus == 1}checked{/if}> {$TEXT_79}<br />
					</td>
				</tr>
				<tr>
					<th align="right">{$TEXT_80}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="grouping1" size=1>
					<option value=""> </option>
					<option value="year"             {if $SEARCHPARAMS.grouping1 == "year"           }selected{/if}> {$TEXT_57}</option>
					<option value="month"            {if $SEARCHPARAMS.grouping1 == "month"          }selected{/if}> {$TEXT_56}</option>
					<option value="contractpartner"  {if $SEARCHPARAMS.grouping1 == "contractpartner"}selected{/if}> {$TEXT_2}</option>
					</select></td>
				<tr>
					<th align="right">{$TEXT_103}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="grouping2" size=1>
					<option value=""> </option>
					<option value="year"             {if $SEARCHPARAMS.grouping2 == "year"           }selected{/if}> {$TEXT_57}</option>
					<option value="month"            {if $SEARCHPARAMS.grouping2 == "month"          }selected{/if}> {$TEXT_56}</option>
					<option value="contractpartner"  {if $SEARCHPARAMS.grouping2 == "contractpartner"}selected{/if}> {$TEXT_2}</option>
					</select></td>
				</tr>
				<tr>
					<th align="right">{$TEXT_104}</th>
					<td class="contrastbgcolor"><select class="contrastbgcolor" name="order" size=1>
					<option value=""> </option>
					<option value="grouping" {if $SEARCHPARAMS.order == "grouping"}selected{/if}> {$TEXT_105}</option>
					<option value="amount"   {if $SEARCHPARAMS.order == "amount"  }selected{/if}> {$TEXT_18}</option>
					<option value="comment"  {if $SEARCHPARAMS.order == "comment" }selected{/if}> {$TEXT_21}</option>
					</select></td>
				</tr>
				

			</table>
			<br />
			<input type="submit" name="realaction" value="{$TEXT_83}">
		</form>
			{if $SEARCH_DONE == 1}
			<table border=0 width=830 align="center" cellpadding=2>
				<tr>
					{if $COLUMNS.year  == "1"}<th width="9%">{$TEXT_81}</th>{/if}
					{if $COLUMNS.month == "1"}<th width="9%">{$TEXT_82}</th>{/if}
					{if $COLUMNS.name  == "1"}<th width=16%>{$TEXT_2}</th>{/if}
					<th width="10%">{$TEXT_18}</th>
					<th >{$TEXT_21}</th>
				</tr>
				{section name=DATA loop=$RESULTS}
					<tr>
						{if $COLUMNS.year  == "1"}<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].year}</p></td>{/if}
						{if $COLUMNS.month == "1"}<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].month}</p></td>{/if}
						{if $COLUMNS.name  == "1"}<td class="contrastbgcolor"><p style="margin-left:8px;">{$RESULTS[DATA].name}</p></td>{/if}
						<td align="right" class="contrastbgcolor"><font {if $RESULTS[DATA].amount < 0}color="red"{else}color="black"{/if}>{$RESULTS[DATA].amount|number_format} {$CURRENCY}</font></td>
						<td class="contrastbgcolor">{$RESULTS[DATA].comment}</td>
					</tr>
				{/section}
			</table>
			{/if}
{$FOOTER}
