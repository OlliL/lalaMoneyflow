	<style type="text/css">
	{literal}
	all,body,table,a,td,option,input,select { font-size:10px;font-family:Bitstream Vera Sans,Arial, Helvetica,sans-serif; }
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
					<a href="{$ENV_INDEX_PHP}?action=upfrm_cmp_data">{$TEXT_187}</a><br />
					<br />
					<b>{$TEXT_14}</b><br />
					<a href="{$ENV_INDEX_PHP}?action=list_reports&reports_month={$REPORTS_MONTH}&reports_year={$REPORTS_YEAR}">{$TEXT_9} {$REPORTS_YEAR}-{$REPORTS_MONTH}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&sr=1','_blank','width=800,height=80')">{$TEXT_10}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&sr=1','_blank','width=840,height=80')">{$TEXT_11}</a><br />
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&sr=1','_blank','width=800,height=80')">{$TEXT_12}</a><br />
					</nobr>
					<br />
					<b>{$TEXT_95}</b><br />
					<a href="{$ENV_INDEX_PHP}?action=personal_settings">{$TEXT_89}</a><br />
{if $IS_ADMIN }
					<a href="{$ENV_INDEX_PHP}?action=system_settings">{$TEXT_93}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_users">{$TEXT_94}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_currencies">{$TEXT_106}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_currencyrates">{$TEXT_110}</a><br />
					<a href="{$ENV_INDEX_PHP}?action=list_languages">{$TEXT_182}</a><br />
{/if}
					<br />
					<br />
					<center>
					<a href="{$ENV_INDEX_PHP}?action=logout"><b>{$TEXT_13}</b></a>
					</center><br />
					
<br />
<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">&copy; by Oliver Lehmann</p>
		</td>
{/if}
