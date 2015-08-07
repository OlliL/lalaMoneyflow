<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {if $NEW != 1}{#TEXT_54#}{else}{#TEXT_55#}{/if}</title>
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
		<h1>{if $NEW != 1}{#TEXT_54#}{else}{#TEXT_55#}{/if}</h1>
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}<br>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action" value="edit_monthlysettlement">
			<input type="hidden" name="realaction" value="">
			{#TEXT_56#}
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
			{#TEXT_57#}
			<input class="contrastbgcolor" type="text" name="monthlysettlements_year" value="{$YEAR}" size="4" onchange="Go(this.form.monthlysettlements_month.options[this.form.monthlysettlements_month.options.selectedIndex].value,this.form.monthlysettlements_year.value)">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<br><br>
			{if $COUNT_ALL_DATA gt 0}
			<table border=0 width="350" cellpadding=2>
				<tr>
					<th>{#TEXT_19#}</th>
					<th width="30%">{#TEXT_18#}</th>
				</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor">
							{if $NEW == 1 }<input type="hidden" name="all_data[{$smarty.section.DATA.index}][new]" value="1">
							{/if}<input type="hidden" name="all_data[{$smarty.section.DATA.index}][mcs_capitalsourceid]" value="{$ALL_DATA[DATA].mcs_capitalsourceid}">
							<input type="hidden" name="all_data[{$smarty.section.DATA.index}][month]" value="{$MONTH.nummeric}">
							<input type="hidden" name="all_data[{$smarty.section.DATA.index}][year]" value="{$YEAR}">
							<input type="hidden" name="all_data[{$smarty.section.DATA.index}][capitalsourcecomment]" value="{$ALL_DATA[DATA].capitalsourcecomment|escape:htmlall}">
						{$ALL_DATA[DATA].capitalsourcecomment|escape:htmlall}
						</td>
						<td class="contrastbgcolor" align="right">
							{if $ALL_DATA[DATA].imported == 1}
							<input type="hidden" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}">{$ALL_DATA[DATA].amount}
							{else}
							<input class="contrastbgcolor" type="text" name="all_data[{$smarty.section.DATA.index}][amount]" value="{$ALL_DATA[DATA].amount}" style="text-align:right{if $ALL_DATA[DATA].amount_error == 1};color:red{/if}" size=8 align="right">
							{/if}
							{#CURRENCY#}
						</td>
					</tr>
				{/section}
			</table>
			<br>
			<input type="submit" value="{#TEXT_22#}" onClick="this.form.realaction.value = 'save'">
			{else}
			<font color="#FF0000">{#TEXT_262#}</font><br><br>
			{/if}
			<input type="button" value="{#TEXT_23#}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
