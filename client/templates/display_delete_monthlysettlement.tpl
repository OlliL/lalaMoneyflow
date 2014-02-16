<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
{if $CLOSE != 1}
	<head><title>lalaMoneyflow: {#TEXT_60#}</title>
{$HEADER}

		<td align="center">
		{section name=ERROR loop=$ERRORS}
			<font color="#FF0000">{$ERRORS[ERROR]}</font><br>
		{/section}
		<u>{#TEXT_59#}</u><br>
		<h1>{#TEXT_53#} {$MONTH.name} {$YEAR}</h1>
		<form action="{$ENV_INDEX_PHP}" method="POST">
			<input type="hidden" name="action"  value="delete_monthlysettlement">
			<input type="hidden" name="realaction" value="yes">
			<input type="hidden" name="monthlysettlements_month" value="{$MONTH.nummeric}">
			<input type="hidden" name="monthlysettlements_year" value="{$YEAR}">
			<input type="hidden" name="REFERER" value="{$ENV_REFERER}">
			<table border=0 width="300" cellpadding=2>
				<tr>
					<th>{#TEXT_19#}</th>
					<th width="30%">{#TEXT_18#}</th>
				</tr>
				{section name=DATA loop=$ALL_DATA}
					<tr>
						<td class="contrastbgcolor">{$ALL_DATA[DATA].capitalsourcecomment}</td>
						<td class="contrastbgcolor" align="right"><font {if $ALL_DATA[DATA].amount < 0}color="red"{else}color="black"{/if}>{$ALL_DATA[DATA].amount|number_format} {#CURRENCY#}</font></td>
					</tr>
				{/section}
				<tr>
					<td align="right">&sum;</td>
					<td align="right" class="contrastbgcolor"><font {if $SUMAMOUNT < 0}color="red"{else}color="black"{/if}><u>{$SUMAMOUNT|number_format} {#CURRENCY#}</u></font></td>
				</tr>
			</table><br>
			<input type="submit" value="{#TEXT_25#}">
			<input type="button" value="{#TEXT_26#}" onclick="javascript:void self.close();">
		</form>
		</td>
{$FOOTER}
{else}
	<body onLoad='opener.location = "{$ENV_REFERER}" ;parent.close()'>
	</html>
{/if}
