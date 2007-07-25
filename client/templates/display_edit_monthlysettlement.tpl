<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $NEW != 1}{$TEXT_54}{else}{$TEXT_55}{/if}</title>
{$HEADER}

		<td align="center">
		<h1>{$TEXT_53} {if $NEW != 1 }{$MONTH.name} {$YEAR}{/if}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_monthlysettlement">
			<input type="hidden" name="realaction" value="">
			{if $NEW != 1 }
				<input type="hidden" name="monthlysettlements_month" value="{$MONTH.nummeric}">
				<input type="hidden" name="monthlysettlements_year" value="{$YEAR}">
			{else}
				{$TEXT_56} <select class="contrastbgcolor" name="monthlysettlements_month">
				<option {if $MONTH.nummeric == "01"}selected{/if}> 01
				<option {if $MONTH.nummeric == "02"}selected{/if}> 02
				<option {if $MONTH.nummeric == "03"}selected{/if}> 03
				<option {if $MONTH.nummeric == "04"}selected{/if}> 04
				<option {if $MONTH.nummeric == "05"}selected{/if}> 05
				<option {if $MONTH.nummeric == "06"}selected{/if}> 06
				<option {if $MONTH.nummeric == "07"}selected{/if}> 07
				<option {if $MONTH.nummeric == "08"}selected{/if}> 08
				<option {if $MONTH.nummeric == "09"}selected{/if}> 09
				<option {if $MONTH.nummeric == "10"}selected{/if}> 10
				<option {if $MONTH.nummeric == "11"}selected{/if}> 11
				<option {if $MONTH.nummeric == "12"}selected{/if}> 12
				</select>
				{$TEXT_57} <input class="contrastbgcolor" type="text" name="monthlysettlements_year" value="{$YEAR}" size=4">
			{/if}
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0 width="350" cellpadding=2>
				<tr>
					<th>{$TEXT_19}</th>
					<th width="30%">{$TEXT_18}</th>
				</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						{if $NEW == 1 }<input type="hidden" name="all_data[{$ALL_DATA[DATA].id}][new]" value="1">{/if}
						<td class="contrastbgcolor"><input class="contrastbgcolor" type="hidden" name="all_data[{$ALL_DATA[DATA].id}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].id}">{$ALL_DATA[DATA].comment}</td>
						<td class="contrastbgcolor" align="right"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][amount]" value="{$ALL_DATA[DATA].amount|string_format:"%.2f"}" size=8 align="right"/> {$CURRENCY}</font></td>
					</tr>
				{/section}
			</table>
			<br />
			<input type="submit" value="{$TEXT_22}" onClick="this.form.realaction.value = 'save'">
			<input type="button" value="{$TEXT_23}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
