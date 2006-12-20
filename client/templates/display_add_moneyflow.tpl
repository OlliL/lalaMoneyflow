<html>
	<head><title>lalaMoneyflow: {$TEXT_8}</title>
{$HEADER}

		<td align="center">
		<h1>{$TEXT_8}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}
		<form action="{$ENV_INDEX_PHP}?action=add_moneyflow" method="POST">
			<input type="hidden" name="action" value="add_moneyflow">
			<table border=0>
				<tr>
					<th>&nbsp;</th>
					<th>{$TEXT_16}</th>
					<th>{$TEXT_17}</th>
					<th>{$TEXT_18}</th>
					<th>{$TEXT_20}</th>
					<th>{$TEXT_21}</th>
					<th>{$TEXT_19}</th>
				</tr>
				{assign var=elements value="1"}
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="checkbox" name="all_data[{$smarty.section.DATA.index}][checked]" value=1 {if $ALL_DATA[DATA].checked == 1}checked{/if} /><input type="hidden" name="all_data[{$smarty.section.DATA.index}][id]" value="{$ALL_DATA[DATA].id}" /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][bookingdate]" value="{$ALL_DATA[DATA].bookingdate}" size=10 {if $ALL_DATA[DATA].bookingdate_error == 1}style="color:red"{/if} /></td>
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][invoicedate]" value="{$ALL_DATA[DATA].invoicedate}" size=10 {if $ALL_DATA[DATA].invoicedate_error == 1}style="color:red"{/if} /></td>
						<td class="contrastbgcolor" nowrap><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}" size=8 onchange="this.form.elements[{$elements}].checked=true" style="text-align:right{if $ALL_DATA[DATA].amount_error == 1};color:red{/if}" /> {$CURRENCY}</td>

						{if $ALL_DATA[DATA].id eq -1 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][contractpartnerid]" size=1>
						{section name=CONTRACTPARTNER loop=$CONTRACTPARTNER_VALUES}
							<option value="{$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id}" {if $CONTRACTPARTNER_VALUES[CONTRACTPARTNER].id == $ALL_DATA[DATA].contractpartnerid}selected{/if}> {$CONTRACTPARTNER_VALUES[CONTRACTPARTNER].name}</option>
						{/section}
						</select></td>
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][contractpartnerid]" value="{$ALL_DATA[DATA].contractpartnerid}" /><input type="hidden" name="all_data[{$smarty.section.DATA.index}][contractpartnername]" value="{$ALL_DATA[DATA].contractpartnername}" />{$ALL_DATA[DATA].contractpartnername}</td>
						{/if}
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][comment]" value="{$ALL_DATA[DATA].comment}" size="50" {if $ALL_DATA[DATA].comment_error == 1}style="color:red"{/if}/></td>

						{if $ALL_DATA[DATA].id eq -1 }
						<td class="contrastbgcolor"><select class="contrastbgcolor" name="all_data[{$smarty.section.DATA.index}][capitalsourceid]" size=1>
						{section name=CAPITALSOURCE loop=$CAPITALSOURCE_VALUES}
							<option value="{$CAPITALSOURCE_VALUES[CAPITALSOURCE].id}" {if $CAPITALSOURCE_VALUES[CAPITALSOURCE].id == $ALL_DATA[DATA].capitalsourceid}selected{/if}> {$CAPITALSOURCE_VALUES[CAPITALSOURCE].comment}</option>
						{/section}
						</select></td>
						{assign var="elements" value="`$elements+8`"}
						{else}
						<td class="contrastbgcolor"><input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourceid]" value="{$ALL_DATA[DATA].capitalsourceid}" /><input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment}" />{$ALL_DATA[DATA].capitalsourcecomment}</td>
						{assign var="elements" value="`$elements+10`"}
						{/if}
					</tr>
				{/section}
			</table>
			<br />
			<input type="hidden" name="realaction" value="save">
			<input type="submit" value="{$TEXT_22}">
		</form>
		</td>
{$FOOTER}
