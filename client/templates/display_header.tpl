	<style type="text/css">
	{literal}
	all,body,table,a,td,option,input,select { font-size:10px;font-family:arial,helvetica; }
	.contrastbgcolor { background-color: #B0C4DE; }
	{/literal}
	</style>				
	</head>
	<body name="main" bgcolor="#E6E6FA">
		<table border=0 width="100%">
			<tr>
{if $NO_NAVIGATION == 0}
				<td valign="top" width="100"><nobr>
					<a href="{$ENV_INDEX_PHP}?action=list_capitalsources">list capital sources</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_contractpartners">list contract partners</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">list predefined moneyflows</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements">list monthly settlements</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_reports">list reports</a><br />
					<br />
					<a href="{$ENV_INDEX_PHP}?action=search">search moneyflows</a><br />
					<a href="{$ENV_INDEX_PHP}?action=add_moneyflow">add moneyflow</a><br />
					<br />
					<b>shortcuts</b><br />
					<a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$REPORTS_MONTH}&reports_year={$REPORTS_YEAR}">report {$REPORTS_YEAR}-{$REPORTS_MONTH}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">add capital source</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&sr=1','_blank','width=800,height=80')">add contract partner</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=800,height=80')">add predefined moneyflow</a><br />
					</nobr>
		</td>
{/if}
