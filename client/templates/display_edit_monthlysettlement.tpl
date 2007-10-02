<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $NEW != 1}{$TEXT_54}{else}{$TEXT_55}{/if}</title>
{literal}
<script type="text/javascript">
<!--
function Go(month,year)
{
{/literal}
   referer = "{$ENV_REFERER}"
{literal}

   location.href = "index.php?action=edit_monthlysettlement&monthlysettlements_month=" + month + "&monthlysettlements_year=" + year + "&REFERER=" + referer;
   document.monthlysettlements.reset();
   document.monthlysettlements.elements[0].blur();
}
//-->
</script>
{/literal}
{$HEADER}

		<td align="center">
		<h1>{if $NEW != 1}{$TEXT_54}{else}{$TEXT_55}{/if}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br />
		{/section}<br />
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_monthlysettlement">
			<input type="hidden" name="realaction" value="">
			{$TEXT_56}
			<select class="contrastbgcolor" name="monthlysettlements_month" onchange="Go(this.form.monthlysettlements_month.options[this.form.monthlysettlements_month.options.selectedIndex].value,this.form.monthlysettlements_year.value)">
				<option {if $MONTH.nummeric == "01"}selected{/if} value="01"> 01
				<option {if $MONTH.nummeric == "02"}selected{/if} value="02"> 02
				<option {if $MONTH.nummeric == "03"}selected{/if} value="03"> 03
				<option {if $MONTH.nummeric == "04"}selected{/if} value="04"> 04
				<option {if $MONTH.nummeric == "05"}selected{/if} value="05"> 05
				<option {if $MONTH.nummeric == "06"}selected{/if} value="06"> 06
				<option {if $MONTH.nummeric == "07"}selected{/if} value="07"> 07
				<option {if $MONTH.nummeric == "08"}selected{/if} value="08"> 08
				<option {if $MONTH.nummeric == "09"}selected{/if} value="09"> 09
				<option {if $MONTH.nummeric == "10"}selected{/if} value="10"> 10
				<option {if $MONTH.nummeric == "11"}selected{/if} value="11"> 11
				<option {if $MONTH.nummeric == "12"}selected{/if} value="12"> 12
			</select>
			{$TEXT_57}
			<input class="contrastbgcolor" type="text" name="monthlysettlements_year" value="{$YEAR}" size=4" onchange="Go(this.form.monthlysettlements_month.options[this.form.monthlysettlements_month.options.selectedIndex].value,this.form.monthlysettlements_year.value)">
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
						<td class="contrastbgcolor" align="right"><input class="contrastbgcolor" type="text" name="all_data[{$ALL_DATA[DATA].id}][amount]" value="{$ALL_DATA[DATA].amount}" {if $ALL_DATA[DATA].amount_error == 1}style="color:red"{/if} size=8 align="right"/> {$CURRENCY}</font></td>
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
