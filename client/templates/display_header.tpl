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
<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">lalaMoneyflow<br />{$VERSION}</p><br />
					<a href="{$ENV_INDEX_PHP}?action=list_capitalsources">{$TEXT_1}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_contractpartners">{$TEXT_2}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">{$TEXT_3}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements">{$TEXT_4}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_reports">{$TEXT_5}</a><br />
{if $ENABLE_JPGRAPH }
					<a href="{$ENV_INDEX_PHP}?action=plot_trends">{$TEXT_6}</a><br />
{/if}
					<br />
					<a href="{$ENV_INDEX_PHP}?action=search">{$TEXT_7}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=add_moneyflow">{$TEXT_8}</a><br />
					<br />
					<b>{$TEXT_14}</b><br />
					<a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$REPORTS_MONTH}&reports_year={$REPORTS_YEAR}">{$TEXT_9} {$REPORTS_YEAR}-{$REPORTS_MONTH}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">{$TEXT_10}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&sr=1','_blank','width=840,height=80')">{$TEXT_11}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=800,height=80')">{$TEXT_12}</a><br />
					</nobr>
					<br />
					<br />
					<center>
					<a href="{$ENV_INDEX_PHP}?action=settings">{$TEXT_89}</a><br /><br />
					<a href="{$ENV_INDEX_PHP}?action=logout"><b>{$TEXT_13}</b></a>
					</center><br />
					
<br />
<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">&copy; by Oliver Lehmann</p>
		</td>
{/if}
