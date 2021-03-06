<!DOCTYPE html>
<html lang="en">
  <head>
    <title>moneyjinn</title>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="contrib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="contrib/bootstrap-datepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="contrib/bootstrap-float-label/dist/bootstrap-float-label.css">
    <link rel="stylesheet" href="contrib/bootstrap-toggle/css/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="contrib/bootstrap-fileinput/css/fileinput.css">

    <script src="contrib/jquery/dist/jquery.min.js"></script>
    <script src="contrib/jquery-form/dist/jquery.form.min.js"></script>
    <script src="contrib/moment/moment.js"></script>
    <script src="contrib/bootstrap/js/bootstrap.js"></script>
    <script src="contrib/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="contrib/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script src="contrib/bootstrap-validator/dist/validator.min.js"></script>
    <script src="contrib/mustache.js/mustache.min.js"></script>
    <script src="site.js"></script>
    <script src="contrib/bootstrap-fileinput/js/fileinput.js"></script>
    <script>
      var today = "{$TODAY}";
      var maxDate = "{$MAX_DATE}";
      var currency = "{#CURRENCY#}";
      var ENV_REFERER = $('<textarea />').html("{$ENV_REFERER}").text();
        
      moment.updateLocale('en', {
        week : {
          dow : 1 // Monday is the first day of the week
        }
      });
    </script>
    
  </head>
  <body>
        
{if $NO_NAVIGATION == 0}
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="{$ENV_INDEX_PHP}"><small>moneyjinn {$VERSION}</small></a>
          <ul class="nav navbar-nav pull-left">
            <li {if $TEMPLATE == "display_edit_moneyflow_bs.tpl"} class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=edit_moneyflow"><i class="glyphicon glyphicon-euro"></i></a></li>
          </ul>

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#moneyjinn-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse" id="moneyjinn-navbar-collapse">
          <ul class="nav navbar-nav">
            
            <li{if $TEMPLATE == "display_list_reports_bs.tpl"              } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$REPORTS_MONTH}&amp;reports_year={$REPORTS_YEAR}"><i class="glyphicon glyphicon-list-alt"></i></a></li>
            <li{if $TEMPLATE == "display_search_bs.tpl"                     } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=search"><i class="glyphicon glyphicon-search"></i></a></li>
            <li{if $TEMPLATE == "display_add_importedmoneyflows_bs.tpl"     } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=add_importedmoneyflows"><i class="glyphicon glyphicon-import"></i></a></li>
            <li{if $TEMPLATE == "display_upfrm_cmp_data_bs.tpl"             } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=upfrm_cmp_data"><i class="glyphicon glyphicon-transfer"></i></a></li>

            <li class="dropdown {if $TEMPLATE == "display_plot_trends_bs.tpl"
                                 || $TEMPLATE == "display_show_reporting_form_bs.tpl"}active{/if}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-stats"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li {if $TEMPLATE == "display_plot_trends_bs.tpl"           } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=plot_trends">{#TEXT_6#}</a></li>
                <li {if $TEMPLATE == "display_show_reporting_form_bs.tpl"   } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=show_reporting_form">{#TEXT_254#}</a></li>
              </ul>
            </li>

            <li class="dropdown {if $TEMPLATE == "display_import_imported_moneyflow_receipts_bs.tpl"}active{/if}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-plus"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{$ENV_INDEX_PHP}?action=edit_moneyflow">{#TEXT_8#}</a>
                <li role="separator" class="divider"></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=display_add_imported_moneyflow_receipt&amp;sr=1','_blank','width=1000,height=600')">{#TEXT_362#}</a>
                <li{if $TEMPLATE == "display_import_imported_moneyflow_receipts_bs.tpl"  } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=display_import_imported_moneyflow_receipts">{#TEXT_364#}</a></li>                
                <li role="separator" class="divider"></li>
                <li><a href="javascript:showOverlayCapitalsource()">{#TEXT_1#}</a></li>
                <li><a href="javascript:showOverlayContractpartner()">{#TEXT_2#}</a></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_3#}</a></li>
              </ul>
            </li>

            <li class="dropdown {if $TEMPLATE == "display_list_monthlysettlements_bs.tpl"
                                 || $TEMPLATE == "display_list_contractpartners_bs.tpl"
                                 || $TEMPLATE == "display_list_capitalsources_bs.tpl"
                                 || $TEMPLATE == "display_list_predefmoneyflows_bs.tpl"}active{/if}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-wrench"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li{if $TEMPLATE == "display_list_capitalsources_bs.tpl"    } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_capitalsources">{#TEXT_1#}</a></li>
                <li{if $TEMPLATE == "display_list_contractpartners_bs.tpl"  } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_contractpartners">{#TEXT_2#}</a></li>
                <li{if $TEMPLATE == "display_list_predefmoneyflows_bs.tpl"  } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">{#TEXT_3#}</a></li>
                <li{if $TEMPLATE == "display_list_monthlysettlements_bs.tpl"} class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements">{#TEXT_4#}</a></li>
              </ul>
            </li>

            <li class="dropdown {if $TEMPLATE == "display_list_etf_flows_bs.tpl"}active{/if}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-asterisk"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li{if $TEMPLATE == "display_list_etf_flows_bs.tpl"    } class="active"{/if}><a href="{$ENV_INDEX_PHP}?action=list_etf_flows">{#TEXT_340#}</a></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=display_edit_etf_flow&amp;sr=1','_blank','width=500,height=400')">{#TEXT_354#}</a></li>
              </ul>
            </li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{$ENV_INDEX_PHP}?action=personal_settings">{#TEXT_89#}</a></li>
{if $IS_ADMIN }
                <li role="separator" class="divider"></li>
                <li><a href="{$ENV_INDEX_PHP}?action=system_settings">{#TEXT_93#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_users">{#TEXT_94#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_groups">{#TEXT_212#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_languages">{#TEXT_182#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_postingaccounts">{#TEXT_247#}</a></li>
 {/if}
              </ul>
            </li>
            <li><a href="{$ENV_INDEX_PHP}?action=logout"><i class="glyphicon glyphicon-log-out"></i></a></li>
          </ul>
        </div>
      </div>
    </nav>
{/if}
    <div class="main-wrapper">
{if $EMBEDDED_ADD_CONTRACTPARTNER }
      <div id="contractpartnerModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-body">
{$EMBEDDED_ADD_CONTRACTPARTNER}
      </div>
      </div>
      </div>
      </div>
{literal}
<script>
        function showOverlayContractpartner() {
          saveFocusedElement();
          $('#contractpartnerModal').modal('show');
          document.editcontractpartner.edtmcpname.focus();
          
        }
        function hideOverlayContractpartner() {
          $('#contractpartnerModal').modal('hide');
          setTimeout("restoreLastFocusedElement()", 150);
        }
</script>
{/literal}
{/if}
{if $EMBEDDED_ADD_POSTINGACCOUNT }
      <div id="postingAccountModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-body">
{$EMBEDDED_ADD_POSTINGACCOUNT}
      </div>
      </div>
      </div>
      </div>

<script>
        function showOverlayPostingAccount() {
          saveFocusedElement();
          $('#postingAccountModal').modal('show');
          document.editpostingaccount.edtmpaname.focus();
        }

        function hideOverlayPostingAccount() {
          $('#postingAccountModal').modal('hide');
          setTimeout("restoreLastFocusedElement()", 150);
        }
</script>
{/if}
{if $EMBEDDED_ADD_CAPITALSOURCE }
      <div id="capitalsourceModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-body">
{$EMBEDDED_ADD_CAPITALSOURCE}
      </div>
      </div>
      </div>
      </div>

<script>
        function showOverlayCapitalsource() {
          saveFocusedElement();
          $('#capitalsourceModal').modal('show');
          document.editcapitalsource.edtmcscomment.focus();
        }
        function hideOverlayCapitalsource() {
          $('#capitalsourceModal').modal('hide');
          setTimeout("restoreLastFocusedElement()", 150);
        }

</script>
{/if}