	<style type="text/css">
	{literal}
	all,body,table,a,td,option,input,select { font-size:10px; }
	.contrastbgcolor { background-color: #B0C4DE; }
	{/literal}
	</style>				
	</head>
	<body name="main" bgcolor="#E6E6FA">
		<table border=0 width="100%">
			<tr>
{if $NO_NAVIGATION == 0}
				<td valign="top" width="200">
		<a href="{$ENV_INDEX_PHP}?action=list_capitalsources">list capital sources</a><br />
		<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">add capital source</a><br />
		<a href="{$ENV_INDEX_PHP}?action=list_contractpartners">list contract partners</a><br />
		<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&sr=1','_blank','width=800,height=80')">add contract partner</a><br />
		<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">list predefined moneyflows</a><br />
		<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=800,height=80')">add predefined moneyflow</a><br />
		<br />
		<a href="{$ENV_INDEX_PHP}?action=add_moneyflows">add moneyflows</a><br />
		<br />
		<a href="{$ENV_INDEX_PHP}?action=show_monthlysettlement">do a monthly settlement</a><br />
		<br />
		<form action="{$ENV_INDEX_PHP}" method="GET">
			<select name="header_month">
				<option {if $HEADER_MONTH == 01}selected{/if}> 01
				<option {if $HEADER_MONTH == 02}selected{/if}> 02
				<option {if $HEADER_MONTH == 03}selected{/if}> 03
				<option {if $HEADER_MONTH == 04}selected{/if}> 04
				<option {if $HEADER_MONTH == 05}selected{/if}> 05
				<option {if $HEADER_MONTH == 06}selected{/if}> 06
				<option {if $HEADER_MONTH == 07}selected{/if}> 07
				<option {if $HEADER_MONTH == 08}selected{/if}> 08
				<option {if $HEADER_MONTH == 09}selected{/if}> 09
				<option {if $HEADER_MONTH == 10}selected{/if}> 10
				<option {if $HEADER_MONTH == 11}selected{/if}> 11
				<option {if $HEADER_MONTH == 12}selected{/if}> 12
			</select>
			<select name="header_year">
				{section name=YEAR loop=$YEARS}
					<option {if $YEARS[YEAR] == $HEADER_YEAR}selected{/if}> {$YEARS[YEAR]}
				{/section}
			</select>
			<input type="submit" name="action" value="generate report">
		</form>
	</td>
{/if}
