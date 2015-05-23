
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<style type="text/css">
	{literal}
	all,body,table,a,td,option,input,select { font-size:10px;font-family:Bitstream Vera Sans,Arial, Helvetica,sans-serif; }
	td { padding: 3px; }
	.contrastbgcolor { background-color: #B0C4DE; }
	.contrastbgcolornobr { background-color: #B0C4DE; white-space:nowrap; }
	.nobr { white-space:nowrap; }
	table.main{ height:100%; }
	{/literal}
	</style>
	</head>
	<body bgcolor="#E6E6FA">
		<table border="0" width="100%" class="main">
			<tr>
{if $NO_NAVIGATION == 0}
				<td valign="top" width="100"><div class="nobr">
<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">lalaMoneyflow<br>{$VERSION}</p><br>
					<a href="{$ENV_INDEX_PHP}?action=add_moneyflow">{#TEXT_8#}</a><br>
					<br>
					<a href="{$ENV_INDEX_PHP}?action=list_capitalsources">{#TEXT_1#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_contractpartners">{#TEXT_2#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">{#TEXT_3#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements">{#TEXT_4#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_reports">{#TEXT_5#}</a><br>
{if $ENABLE_JPGRAPH }
					<a href="{$ENV_INDEX_PHP}?action=plot_trends">{#TEXT_6#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=show_reporting_form">{#TEXT_254#}</a><br>
{/if}
					<a href="{$ENV_INDEX_PHP}?action=search">{#TEXT_7#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=upfrm_cmp_data">{#TEXT_187#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=add_importedmoneyflows">{#TEXT_268#}</a><br>
					<br>
					<b>{#TEXT_14#}</b><br>
					<a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$REPORTS_MONTH}&amp;reports_year={$REPORTS_YEAR}">{#TEXT_9#} {$REPORTS_YEAR}-{$REPORTS_MONTH}</a><br>
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_10#}</a><br>
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_11#}</a><br>
					<a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;sr=1','_blank','width=920,height=120')">{#TEXT_12#}</a><br>
					</div>
					<br>
					<b>{#TEXT_95#}</b><br>
					<a href="{$ENV_INDEX_PHP}?action=personal_settings">{#TEXT_89#}</a><br>
{if $IS_ADMIN }
					<a href="{$ENV_INDEX_PHP}?action=system_settings">{#TEXT_93#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_users">{#TEXT_94#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_groups">{#TEXT_212#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_languages">{#TEXT_182#}</a><br>
					<a href="{$ENV_INDEX_PHP}?action=list_postingaccounts">{#TEXT_247#}</a><br>
{/if}
					<br>
					<br>
					<center>
					<a href="{$ENV_INDEX_PHP}?action=logout"><b>{#TEXT_13#}</b></a>
					</center><br>

<br>
<p style="font-size:11px;color:darkblue;font-weight:bold;text-align:center">&copy; by Oliver Lehmann</p>
		</td>
{/if}
