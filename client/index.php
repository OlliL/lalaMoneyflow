<?php
#-
# Copyright (c) 2005-2007 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: index.php,v 1.44 2007/10/25 12:58:07 olivleh1 Exp $
#

require_once 'include.php';
require_once 'functions.php';
require_once 'module/moduleCapitalSources.php';
require_once 'module/moduleContractPartners.php';
require_once 'module/moduleCompare.php';
require_once 'module/moduleCurrencies.php';
require_once 'module/moduleCurrencyRates.php';
require_once 'module/moduleFrontPage.php';
require_once 'module/moduleLanguages.php';
require_once 'module/moduleMoneyFlows.php';
require_once 'module/moduleMonthlySettlement.php';
require_once 'module/modulePreDefMoneyFlows.php';
require_once 'module/moduleReports.php';
require_once 'module/moduleSearch.php';
require_once 'module/moduleSettings.php';
require_once 'module/moduleUsers.php';
if( $money_debug === true ) {
	require_once 'util/utilTimer.php';
	$timer = new utilTimer();
	$timer->mStart();
}


$action=$_POST['action']?$_POST['action']:$_GET['action'];

function my_number_format($number) {
	return number_format($number,2);
}

$moduleUsers    = new moduleUsers();
$moduleSettings = new moduleSettings();

$request_uri = $_SERVER['REQUEST_URI'];

if( $action == 'logout' ) {

	/* user tries to logout */

	$moduleUsers->logout();
	$request_uri = $_SERVER['PHP_SELF'];
}

$is_logged_in = $moduleUsers->is_logged_in();

if( $is_logged_in == 2 ) {

	/* user is new and must change his password */

	if( empty($_POST['realaction']) || $_POST['realaction'] != 'save' ) {
		add_error( 152 );
	}
	$realaction =		$_REQUEST['realaction'];
	$language =		$_REQUEST['language'];
	$currency =		$_REQUEST['currency'];
	$password1 =		$_REQUEST['password1'];
	$password2 =		$_REQUEST['password2'];
	$display = $moduleSettings->display_personal_settings( $realaction, $language, $currency, $password1, $password2 );

	if( $_POST['realaction'] == 'save' && !is_array( $ERRORS ) )
		header("Location: ".$_SERVER['PHP_SELF']);

} elseif( $action == 'login_user' || $is_logged_in != 0 ) {

	/* user tries to login */

	$realaction =		$_REQUEST['realaction'];
	$name =			$_REQUEST['name'];
	$password =		$_REQUEST['password'];
	$stay_logged_in =	$_REQUEST['stay_logged_in'];
	$display = $moduleUsers->display_login_user( $realaction, $name, $password, $stay_logged_in, $request_uri );

	if( $_POST['request_uri'] && !is_array( $ERRORS ) )
		header("Location: ".$_POST['request_uri']);

}

if( $money_debug === true )
	error_reporting(E_ALL);

if( $is_logged_in == 0 ) {

	switch( $action ) {
		case'list_capitalsources':
		case'edit_capitalsource':
		case'delete_capitalsource':	$moduleCapitalSources		= new moduleCapitalSources();
						break;
		case'list_contractpartners':
		case'edit_contractpartner':
		case'delete_contractpartner':	$moduleContractPartners		= new moduleContractPartners();
						break;
		case'list_predefmoneyflows':
		case'edit_predefmoneyflow':
		case'delete_predefmoneyflow':	$modulePreDefMoneyFlows		= new modulePreDefMoneyFlows();
						break;
		case'add_moneyflow':
		case'edit_moneyflow':
		case'delete_moneyflow':		$moduleMoneyFlows		= new moduleMoneyFlows();
						break;
		case'list_monthlysettlements':
		case'edit_monthlysettlement':
		case'delete_monthlysettlement':	$moduleMonthlySettlement	= new moduleMonthlySettlement();
						break;
		case'list_reports':
		case'plot_trends':
		case'plot_graph':		$moduleReports			= new moduleReports();
						break;
		case'search':
		case'do_search':		$moduleSearch			= new moduleSearch();
						break;
		case'personal_settings':
		case'system_settings':		break;
		case'list_currencies':
		case'edit_currency':
		case'delete_currency':		$moduleCurrencies		= new moduleCurrencies();
						break;
		case'list_currencyrates':
		case'edit_currencyrate':	$moduleCurrencyRates		= new moduleCurrencyRates();
						break;
		case'list_languages':
		case'edit_language':
		case'add_language':		$moduleLanguages		= new moduleLanguages();
						break;
		case'list_users':
		case'edit_user':
		case'delete_user':		break;

		case 'upfrm_cmp_data':
		case 'analyze_cmp_data':	$moduleCompare			= new moduleCompare();
						break;
		default:			$moduleFrontPage		= new moduleFrontPage();
						break;
	}

	if( $moduleUsers->is_admin() ) {
		switch( $action ) {
			case 'system_settings':		$realaction=		$_REQUEST['realaction'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleSettings->display_system_settings( $realaction, $all_data );
							break;

			/* currencies */

			case 'list_currencies':		$letter=		$_REQUEST['letter'];
							$display=$moduleCurrencies->display_list_currencies( $letter );
							break;
			case 'edit_currency':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['currencyid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleCurrencies->display_edit_currency( $realaction, $id, $all_data );
							break;
			case 'delete_currency':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['currencyid'];
							$display=$moduleCurrencies->display_delete_currency( $realaction, $id );
							break;

			/* currencyrates */

			case 'list_currencyrates':	$letter=		$_REQUEST['letter'];
							$display=$moduleCurrencyRates->display_list_currencyrates( $letter );
							break;
			case 'edit_currencyrate':	$realaction=		$_REQUEST['realaction'];
							$currencyid=		$_REQUEST['mcu_currencyid'];
							$validfrom=		$_REQUEST['validfrom'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleCurrencyRates->display_edit_currencyrate( $realaction, $currencyid, $validfrom, $all_data );
							break;

			/* languages */

			case 'list_languages':		$letter=		$_REQUEST['letter'];
							$display=$moduleLanguages->display_list_languages( $letter );
							break;
			case 'edit_language':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['languageid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleLanguages->display_edit_language( $realaction, $id, $all_data );
							break;
			case 'add_language':		$realaction=		$_REQUEST['realaction'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleLanguages->display_add_language( $realaction, $all_data );
							break;

			/* users */

			case 'list_users':		$letter=		$_REQUEST['letter'];
							$display=$moduleUsers->display_list_users( $letter );
							break;
			case 'edit_user':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['userid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleUsers->display_edit_user( $realaction, $id, $all_data );
							break;
			case 'delete_user':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['userid'];
							$force=			$_REQUEST['force'];
							$display=$moduleUsers->display_delete_user( $realaction, $id, $force );
							break;
		}
	}

	if( empty( $display ) ) {
		switch( $action ) {
			/* capitalsources */

			case 'list_capitalsources':	$letter=		$_REQUEST['letter'];
							$display=$moduleCapitalSources->display_list_capitalsources( $letter );
							break;

			case 'edit_capitalsource':	$realaction=		$_REQUEST['realaction'];
							$capitalsourceid=	$_REQUEST['capitalsourceid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleCapitalSources->display_edit_capitalsource( $realaction, $capitalsourceid, $all_data );
							break;

			case 'delete_capitalsource':	$realaction=		$_REQUEST['realaction'];
							$capitalsourceid=	$_REQUEST['capitalsourceid'];
							$display=$moduleCapitalSources->display_delete_capitalsource( $realaction, $capitalsourceid );
							break;

			/* contractpartners */

			case 'list_contractpartners':	$letter=		$_REQUEST['letter'];
							$display=$moduleContractPartners->display_list_contractpartners( $letter );
							break;

			case 'edit_contractpartner':	$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['contractpartnerid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleContractPartners->display_edit_contractpartner( $realaction, $id, $all_data );
							break;

			case 'delete_contractpartner':	$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['contractpartnerid'];
							$display=$moduleContractPartners->display_delete_contractpartner( $realaction, $id );
							break;

			/* predefmoneyflows */

			case 'list_predefmoneyflows':	$letter=		$_REQUEST['letter'];
							$display=$modulePreDefMoneyFlows->display_list_predefmoneyflows( $letter );
							break;

			case 'edit_predefmoneyflow':	$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['predefmoneyflowid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$modulePreDefMoneyFlows->display_edit_predefmoneyflow( $realaction, $id, $all_data );
							break;

			case 'delete_predefmoneyflow':	$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['predefmoneyflowid'];
							$display=$modulePreDefMoneyFlows->display_delete_predefmoneyflow( $realaction, $id );
							break;

			/* moneyflows */

			case 'add_moneyflow':		$realaction=		$_REQUEST['realaction'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleMoneyFlows->display_add_moneyflow( $realaction, $all_data );
							break;
			case 'edit_moneyflow':		$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['moneyflowid'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleMoneyFlows->display_edit_moneyflow( $realaction, $id, $all_data );
							break;

			case 'delete_moneyflow':	$realaction=		$_REQUEST['realaction'];
							$id=			$_REQUEST['moneyflowid'];
							$display=$moduleMoneyFlows->display_delete_moneyflow( $realaction, $id );
							break;

			/* monthlysettlements */

			case 'list_monthlysettlements':	$month=			$_REQUEST['monthlysettlements_month'];
							$year=			$_REQUEST['monthlysettlements_year'];
							$display=$moduleMonthlySettlement->display_list_monthlysettlements( $month, $year );
							break;
			case 'edit_monthlysettlement':	$month=			$_REQUEST['monthlysettlements_month'];
							$year=			$_REQUEST['monthlysettlements_year'];
							$realaction=		$_REQUEST['realaction'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleMonthlySettlement->display_edit_monthlysettlement( $realaction, $month, $year, $all_data );
							break;
			case 'delete_monthlysettlement':$month=			$_REQUEST['monthlysettlements_month'];
							$year=			$_REQUEST['monthlysettlements_year'];
							$realaction=		$_REQUEST['realaction'];
							$display=$moduleMonthlySettlement->display_delete_monthlysettlement( $realaction, $month, $year );
							break;

			/* reports */

			case 'list_reports':		$month=			$_REQUEST['reports_month'];
							$year=			$_REQUEST['reports_year'];
							$sortby=		$_REQUEST['reports_sortby'];
							$order=			$_REQUEST['reports_order'];
							$display=$moduleReports->display_list_reports( $month, $year, $sortby, $order );
							break;
			case 'plot_trends':		$all_data=		$_REQUEST['all_data'];
							$display = ( ENABLE_JPGRAPH ? $moduleReports->display_plot_trends($all_data) : '' );
							break;
			case 'plot_graph':		$id=			$_REQUEST['id'];
							$startmonth=		$_REQUEST['startmonth'];
							$startyear=		$_REQUEST['startyear'];
							$endmonth=		$_REQUEST['endmonth'];
							$endyear=		$_REQUEST['endyear'];
							$display = ( ENABLE_JPGRAPH ? $moduleReports->plot_graph( $id, $startmonth, $startyear, $endmonth, $endyear ) : '' );
							break;

			/* search */

			case 'search':			$display=$moduleSearch->display_search();
							break;
			case 'do_search':		$searchstring=		$_REQUEST['searchstring'];
							$contractpart=		$_REQUEST['contractpartner'];
							$startdate=		$_REQUEST['startdate'];
							$enddate=		$_REQUEST['enddate'];
							$equal=			$_REQUEST['equal'];
							$casesensitive=		$_REQUEST['casesensitive'];
							$regexp=		$_REQUEST['regexp'];
							$minus=			$_REQUEST['minus'];
							$grouping1=		$_REQUEST['grouping1'];
							$grouping2=		$_REQUEST['grouping2'];
							$order=			$_REQUEST['order'];
							$display=$moduleSearch->do_search( $searchstring, $contractpart, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus, $grouping1, $grouping2, $order );
							break;

			/* settings */

			case 'personal_settings':	$realaction=		$_REQUEST['realaction'];
							$all_data=		$_REQUEST['all_data'];
							$display=$moduleSettings->display_personal_settings( $realaction, $all_data );
							break;
			
			case 'upfrm_cmp_data':		$display=$moduleCompare->display_upload_form();
							break;
			case 'analyze_cmp_data':	$all_data=		$_REQUEST['all_data'];
							$file=			$_FILES['file'];		
							$display=$moduleCompare->display_analyze_form( $file, $all_data );
							break;

			default:			$display=$moduleFrontPage->display_main();
							break;
		}
	}
}
echo $display;
if( $money_debug === true ) {
	echo "SQL Queries: ";
	$timer->mPrintTime( $sql_querytime );
	echo "<br />";
	echo "overall: ";
	$timer->mPrintTime();
}
?>
