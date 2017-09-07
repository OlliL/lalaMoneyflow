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
    <link rel="stylesheet" href="contrib/bootstrap-toggle-master/css/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="site.css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script type="text/javascript" src="contrib/moment/moment.js"></script>
    <script type="text/javascript" src="contrib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="contrib/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="contrib/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>

  </head>
  <body>
        
{if $NO_NAVIGATION == 0}
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><small>moneyjinn {$VERSION}</small></a>
          <ul class="nav navbar-nav pull-left">
            <li><a href="{$ENV_INDEX_PHP}?action=add_moneyflow"><i class="glyphicon glyphicon-euro"></i></a></li>
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
            
            <li><a href="{$ENV_INDEX_PHP}?action=list_reports&amp;reports_month={$REPORTS_MONTH}&amp;reports_year={$REPORTS_YEAR}"><i class="glyphicon glyphicon-list-alt"></i></a></li>
            <li><a href="{$ENV_INDEX_PHP}?action=search"><i class="glyphicon glyphicon-search"></i></a></li>
            <li><a href="{$ENV_INDEX_PHP}?action=add_importedmoneyflows"><i class="glyphicon glyphicon-import"></i></a></li>
            <li><a href="{$ENV_INDEX_PHP}?action=upfrm_cmp_data"><i class="glyphicon glyphicon-transfer"></i></a></li>

{if $ENABLE_JPGRAPH }
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-stats"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{$ENV_INDEX_PHP}?action=plot_trends">{#TEXT_6#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=show_reporting_form">{#TEXT_254#}</a></li>
              </ul>
            </li>
{/if}                
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-plus"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{$ENV_INDEX_PHP}?action=add_moneyflow">{#TEXT_8#}</a>
                <li role="separator" class="divider"></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_capitalsource&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_1#}</a></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_contractpartner&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_2#}</a></li>
                <li><a href="javascript:void window.open('{$ENV_INDEX_PHP}?action=edit_predefmoneyflow&amp;sr=1','_blank','width=1000,height=120')">{#TEXT_3#}</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-wrench"></i> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{$ENV_INDEX_PHP}?action=list_capitalsources">{#TEXT_1#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_contractpartners">{#TEXT_2#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_predefmoneyflows">{#TEXT_3#}</a></li>
                <li><a href="{$ENV_INDEX_PHP}?action=list_monthlysettlements">{#TEXT_4#}</a></li>
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

    <main class="col-md-12">
    <!--<main class="">-->
    