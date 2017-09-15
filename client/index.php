<?php

//
// Copyright (c) 2005-2015 Oliver Lehmann <oliver@laladev.org>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: index.php,v 1.97 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client;

use base\ErrorCode;
use client\module\moduleEvents;
use client\module\moduleUsers;
use client\module\moduleSettings;
use client\module\moduleCapitalSources;
use client\module\moduleContractPartners;
use client\module\moduleContractPartnerAccounts;
use client\module\modulePreDefMoneyFlows;
use client\module\moduleMoneyFlows;
use client\module\moduleImportedMoneyFlows;
use client\module\moduleMonthlySettlement;
use client\module\moduleReports;
use client\module\moduleSearch;
use client\module\moduleLanguages;
use client\module\moduleGroups;
use client\module\moduleCompare;
use client\module\moduleFrontPage;
use client\util\utilTimer;
use client\util\ErrorHandler;
use client\module\modulePostingAccounts;
use base\Configuration;

require_once 'include.php';

session_start();
if ($money_debug > 0) {
	$timer = new utilTimer( $money_debug );
}

Configuration::getInstance()->readConfig( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'client.properties' );

$action = $_POST ['action'] ? $_POST ['action'] : $_GET ['action'];

$moduleEvents = new moduleEvents();
$moduleUsers = new moduleUsers();
$moduleSettings = new moduleSettings();

$request_uri = $_SERVER ['REQUEST_URI'];
$all_data = null;

if ($action == 'logout') {

	/* user tries to logout */

	$moduleUsers->logout();
	$request_uri = $_SERVER ['SCRIPT_NAME'];
}

$is_logged_in = $moduleUsers->is_logged_in();

if ($is_logged_in == 2) {

	/* user is new and must change his password */

	if (empty( $_POST ['realaction'] ) || $_POST ['realaction'] != 'save') {
		ErrorHandler::addError( ErrorCode::PASSWORD_MUST_BE_CHANGED );
	}
	$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
	$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
	$display = $moduleSettings->display_personal_settings( $realaction, $all_data );

	if ($_POST ['realaction'] == 'save' && ! is_array( ErrorHandler::getErrors() ))
		header( "Location: " . $_SERVER ['SCRIPT_NAME'] );
} elseif ($action == 'login_user' || $is_logged_in != 0) {

	/* user tries to login */

	$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
	$name = array_key_exists( 'name', $_REQUEST ) ? $_REQUEST ['name'] : '';
	$password = array_key_exists( 'password', $_REQUEST ) ? $_REQUEST ['password'] : '';
	$display = $moduleUsers->display_login_user( $realaction, $name, $password, $request_uri );

	if (! $display) {
		$display = $moduleEvents->check_events();

		if ($_POST ['request_uri'] && parse_url( $_POST ['request_uri'] ) ['path'] == $_SERVER ['SCRIPT_NAME'] && ! $display)
			header( "Location: " . htmlentities( $_POST ['request_uri'] ) );
	}
}
// if ($money_debug === true)
error_reporting( E_ALL & ~ E_DEPRECATED ); // DEPRECATED for jpGraph

if ($is_logged_in == 0) {

	if (array_key_exists( 'all_data', $_REQUEST ) && is_array( $_REQUEST ['all_data'] ))
		$all_data = $_REQUEST ['all_data'];
error_log(print_r($all_data, true));
	switch ($action) {
		case 'list_capitalsources' :
		case 'edit_capitalsource' :
		case 'edit_capitalsource_submit' :
		case 'delete_capitalsource' :
			$moduleCapitalSources = new moduleCapitalSources();
			break;
		case 'list_contractpartners' :
		case 'edit_contractpartner' :
		case 'edit_contractpartner_submit' :
		case 'delete_contractpartner' :
			$moduleContractPartners = new moduleContractPartners();
			break;
		case 'list_contractpartneraccounts' :
		case 'edit_contractpartneraccount' :
		case 'delete_contractpartneraccount' :
			$moduleContractPartnerAccounts = new moduleContractPartnerAccounts();
			break;
		case 'list_predefmoneyflows' :
		case 'edit_predefmoneyflow' :
		case 'delete_predefmoneyflow' :
			$modulePreDefMoneyFlows = new modulePreDefMoneyFlows();
			break;
		case 'add_moneyflow' :
		case 'add_moneyflow_submit' :
		case 'edit_moneyflow' :
		case 'delete_moneyflow' :
		case 'show_moneyflow_receipt' :
			$moduleMoneyFlows = new moduleMoneyFlows();
			break;
		case 'add_importedmoneyflows' :
			$moduleImportedMoneyFlows = new moduleImportedMoneyFlows();
			break;
		case 'list_monthlysettlements' :
		case 'edit_monthlysettlement' :
		case 'delete_monthlysettlement' :
			$moduleMonthlySettlement = new moduleMonthlySettlement();
			break;
		case 'list_reports' :
		case 'plot_trends' :
		case 'plot_graph' :
		case 'show_reporting_form' :
		case 'plot_report' :
			$moduleReports = new moduleReports();
			break;
		case 'search' :
		case 'do_search' :
			$moduleSearch = new moduleSearch();
			break;
		case 'personal_settings' :
		case 'system_settings' :
			break;
		case 'list_languages' :
		case 'edit_language' :
		case 'add_language' :
			$moduleLanguages = new moduleLanguages();
			break;
		case 'list_users' :
		case 'edit_user' :
		case 'delete_user' :
			break;

		case 'list_groups' :
		case 'edit_group' :
		case 'delete_group' :
			$moduleGroups = new moduleGroups();
			break;

		case 'list_postingaccounts' :
		case 'edit_postingaccount' :
		case 'edit_postingaccount_submit' :
		case 'delete_postingaccount' :
		case 'plot_postingaccounts' :
			$modulePostingAccounts = new modulePostingAccounts();
			break;

		case 'upfrm_cmp_data' :
		case 'analyze_cmp_data' :
			$moduleCompare = new moduleCompare();
			break;
		default :
			$moduleFrontPage = new moduleFrontPage();
			break;
	}

	if (empty( $display ) && $moduleUsers->is_admin()) {
		switch ($action) {
			case 'system_settings' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
				$display = $moduleSettings->display_system_settings( $realaction, $all_data );
				break;

			/* languages */

			case 'list_languages' :
				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $moduleLanguages->display_list_languages( $letter );
				break;
			case 'edit_language' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'languageid', $_REQUEST ) ? $_REQUEST ['languageid'] : '';
				$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
				$display = $moduleLanguages->display_edit_language( $realaction, $id, $all_data );
				break;
			case 'add_language' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
				$display = $moduleLanguages->display_add_language( $realaction, $all_data );
				break;

			/* users */

			case 'list_users' :
				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $moduleUsers->display_list_users( $letter );
				break;
			case 'edit_user' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'userid', $_REQUEST ) ? $_REQUEST ['userid'] : '';
				$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
				$access_relation = array_key_exists( 'access_relation', $_REQUEST ) ? $_REQUEST ['access_relation'] : '';
				$display = $moduleUsers->display_edit_user( $realaction, $id, $all_data, $access_relation );
				break;
			case 'delete_user' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'userid', $_REQUEST ) ? $_REQUEST ['userid'] : '';
				$display = $moduleUsers->display_delete_user( $realaction, $id );
				break;

			/* groups */

			case 'list_groups' :
				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $moduleGroups->display_list_groups( $letter );
				break;
			case 'edit_group' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'groupid', $_REQUEST ) ? $_REQUEST ['groupid'] : '';
				$all_data = array_key_exists( 'all_data', $_REQUEST ) ? $_REQUEST ['all_data'] : '';
				$display = $moduleGroups->display_edit_group( $realaction, $id, $all_data );
				break;
			case 'delete_group' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'groupid', $_REQUEST ) ? $_REQUEST ['groupid'] : '';
				$display = $moduleGroups->display_delete_group( $realaction, $id );
				break;

			/* postingaccounts */

			case 'list_postingaccounts' :
				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $modulePostingAccounts->display_list_postingaccounts( $letter );
				break;

			case 'edit_postingaccount' :

				$id = array_key_exists( 'postingaccountid', $_REQUEST ) ? $_REQUEST ['postingaccountid'] : 0;
				$display = $modulePostingAccounts->display_edit_postingaccount( $id );
				break;

			case 'edit_postingaccount_submit' :

				$id = array_key_exists( 'postingaccountid', $_REQUEST ) ? $_REQUEST ['postingaccountid'] : 0;
				$display = $modulePostingAccounts->edit_postingaccount( $id, $all_data );
				break;

			case 'delete_postingaccount' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'postingaccountid', $_REQUEST ) ? $_REQUEST ['postingaccountid'] : '';
				$display = $modulePostingAccounts->display_delete_postingaccount( $realaction, $id );
				break;
		}
	}

	if (empty( $display )) {

		switch ($action) {
			/* capitalsources */

			case 'list_capitalsources' :
				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$currently_valid = array_key_exists( 'currently_valid', $_REQUEST ) ? $_REQUEST ['currently_valid'] : null;
				$display = $moduleCapitalSources->display_list_capitalsources( $letter, $currently_valid );
				break;

			case 'edit_capitalsource' :
				$capitalsourceid = array_key_exists( 'capitalsourceid', $_REQUEST ) ? $_REQUEST ['capitalsourceid'] : 0;
				$display = $moduleCapitalSources->display_edit_capitalsource( $capitalsourceid );
				break;

			case 'edit_capitalsource_submit' :

				$capitalsourceid = array_key_exists( 'capitalsourceid', $_REQUEST ) ? $_REQUEST ['capitalsourceid'] : 0;
				$display = $moduleCapitalSources->edit_capitalsource( $capitalsourceid, $all_data );
				break;

			case 'delete_capitalsource' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$capitalsourceid = array_key_exists( 'capitalsourceid', $_REQUEST ) ? $_REQUEST ['capitalsourceid'] : '';
				$display = $moduleCapitalSources->display_delete_capitalsource( $realaction, $capitalsourceid );
				break;

			/* contractpartners */

			case 'list_contractpartners' :

				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$currently_valid = array_key_exists( 'currently_valid', $_REQUEST ) ? $_REQUEST ['currently_valid'] : null;
				$display = $moduleContractPartners->display_list_contractpartners( $letter, $currently_valid );
				break;

			case 'edit_contractpartner' :

				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : 0;
				$display = $moduleContractPartners->display_edit_contractpartner( $contractpartnerid );
				break;

			case 'edit_contractpartner_submit' :

				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : 0;
				$display = $moduleContractPartners->edit_contractpartner( $contractpartnerid, $all_data );
				break;

			case 'delete_contractpartner' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$display = $moduleContractPartners->display_delete_contractpartner( $realaction, $id );
				break;

			/* contractpartneraccounts */

			case 'list_contractpartneraccounts' :

				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$display = $moduleContractPartnerAccounts->display_list_contractpartneraccounts( $contractpartnerid );
				break;

			case 'edit_contractpartneraccount' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : 0;
				$display = $moduleContractPartnerAccounts->display_edit_contractpartneraccount( $realaction, $contractpartneraccountid, $contractpartnerid, $all_data );
				break;

			case 'delete_contractpartneraccount' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : '';
				$display = $moduleContractPartnerAccounts->display_delete_contractpartneraccount( $realaction, $contractpartneraccountid );
				break;

			/* predefmoneyflows */

			case 'list_predefmoneyflows' :

				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $modulePreDefMoneyFlows->display_list_predefmoneyflows( $letter );
				break;

			case 'edit_predefmoneyflow' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : 0;
				$display = $modulePreDefMoneyFlows->display_edit_predefmoneyflow( $realaction, $id, $all_data );
				break;

			case 'delete_predefmoneyflow' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : '';
				$display = $modulePreDefMoneyFlows->display_delete_predefmoneyflow( $realaction, $id );
				break;

			/* moneyflows */

			case 'add_moneyflow' :

				$display = $moduleMoneyFlows->display_add_moneyflow();
				break;
			case 'add_moneyflow_submit' :

				$all_subdata = array_key_exists( 'all_subdata', $_REQUEST ) ? $_REQUEST ['all_subdata'] : '';
				$display = $moduleMoneyFlows->add_moneyflow( $all_data, $all_subdata );
				break;

			case 'edit_moneyflow' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$moneyflow_split_entries = array_key_exists( 'moneyflow_split_entries', $_REQUEST ) ? $_REQUEST ['moneyflow_split_entries'] : array ();
				$display = $moduleMoneyFlows->display_edit_moneyflow( $realaction, $id, $all_data, $moneyflow_split_entries );
				break;

			case 'delete_moneyflow' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$display = $moduleMoneyFlows->display_delete_moneyflow( $realaction, $id );
				break;

			case 'show_moneyflow_receipt' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$display = $moduleMoneyFlows->show_moneyflow_receipt( $id );
				break;

			/* imported moneyflows */

			case 'add_importedmoneyflows' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$display = $moduleImportedMoneyFlows->display_add_importedmoneyflow( $realaction, $all_data );
				break;

			/* monthlysettlements */

			case 'list_monthlysettlements' :

				$month = array_key_exists( 'monthlysettlements_month', $_REQUEST ) ? $_REQUEST ['monthlysettlements_month'] : '';
				$year = array_key_exists( 'monthlysettlements_year', $_REQUEST ) ? $_REQUEST ['monthlysettlements_year'] : '';
				$display = $moduleMonthlySettlement->display_list_monthlysettlements( $month, $year );
				break;
			case 'edit_monthlysettlement' :

				$month = array_key_exists( 'monthlysettlements_month', $_REQUEST ) ? $_REQUEST ['monthlysettlements_month'] : '';
				$year = array_key_exists( 'monthlysettlements_year', $_REQUEST ) ? $_REQUEST ['monthlysettlements_year'] : '';
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$display = $moduleMonthlySettlement->display_edit_monthlysettlement( $realaction, $month, $year, $all_data );
				break;
			case 'delete_monthlysettlement' :

				$month = array_key_exists( 'monthlysettlements_month', $_REQUEST ) ? $_REQUEST ['monthlysettlements_month'] : '';
				$year = array_key_exists( 'monthlysettlements_year', $_REQUEST ) ? $_REQUEST ['monthlysettlements_year'] : '';
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$display = $moduleMonthlySettlement->display_delete_monthlysettlement( $realaction, $month, $year );
				break;

			/* reports */

			case 'list_reports' :

				$month = array_key_exists( 'reports_month', $_REQUEST ) ? $_REQUEST ['reports_month'] : '';
				$year = array_key_exists( 'reports_year', $_REQUEST ) ? $_REQUEST ['reports_year'] : '';
				$sortby = array_key_exists( 'reports_sortby', $_REQUEST ) ? $_REQUEST ['reports_sortby'] : '';
				$order = array_key_exists( 'reports_order', $_REQUEST ) ? $_REQUEST ['reports_order'] : '';
				$display = $moduleReports->display_list_reports( $month, $year, $sortby, $order );
				break;
			case 'show_reporting_form' :

				$display = $moduleReports->show_reporting_form();
				break;
			case 'plot_report' :

				$timemode = array_key_exists( 'timemode', $_POST ) ? $_POST ['timemode'] : '';
				$accountmode = array_key_exists( 'accountmode', $_POST ) ? $_POST ['accountmode'] : '';
				$year = array_key_exists( 'year', $_POST ) ? $_POST ['year'] : '';
				$month_month = array_key_exists( 'month_month', $_POST ) ? $_POST ['month_month'] : '';
				$year_month = array_key_exists( 'year_month', $_POST ) ? $_POST ['year_month'] : '';
				$yearfrom = array_key_exists( 'yearfrom', $_POST ) ? $_POST ['yearfrom'] : '';
				$yeartil = array_key_exists( 'yeartil', $_POST ) ? $_POST ['yeartil'] : '';
				$account = array_key_exists( 'account', $_POST ) ? $_POST ['account'] : '';
				$accounts_yes = array_key_exists( 'accounts_yes', $_POST ) ? $_POST ['accounts_yes'] : '';
				$accounts_no = array_key_exists( 'accounts_no', $_POST ) ? $_POST ['accounts_no'] : '';
				$display = $moduleReports->plot_report( $timemode, $accountmode, $year, $month_month, $year_month, $yearfrom, $yeartil, $account, $accounts_yes, $accounts_no );
				break;
			case 'plot_trends' :

				$display = (ENABLE_JPGRAPH ? $moduleReports->display_plot_trends( $all_data ) : '');
				break;
			case 'plot_graph' :

				$id = array_key_exists( 'id', $_REQUEST ) ? $_REQUEST ['id'] : '';
				$startmonth = array_key_exists( 'startmonth', $_REQUEST ) ? $_REQUEST ['startmonth'] : '';
				$startyear = array_key_exists( 'startyear', $_REQUEST ) ? $_REQUEST ['startyear'] : '';
				$endmonth = array_key_exists( 'endmonth', $_REQUEST ) ? $_REQUEST ['endmonth'] : '';
				$endyear = array_key_exists( 'endyear', $_REQUEST ) ? $_REQUEST ['endyear'] : '';
				$display = (ENABLE_JPGRAPH ? $moduleReports->plot_graph( $id, $startmonth, $startyear, $endmonth, $endyear ) : '');
				break;

			/* search */

			case 'search' :

				$display = $moduleSearch->display_search();
				break;
			case 'do_search' :

				$searchstring = array_key_exists( 'searchstring', $_REQUEST ) ? $_REQUEST ['searchstring'] : '';
				$contractpartner = array_key_exists( 'contractpartner', $_REQUEST ) ? $_REQUEST ['contractpartner'] : '';
				$postingaccount = array_key_exists( 'postingaccount', $_REQUEST ) ? $_REQUEST ['postingaccount'] : '';
				$startdate = array_key_exists( 'startdate', $_REQUEST ) ? $_REQUEST ['startdate'] : '';
				$enddate = array_key_exists( 'enddate', $_REQUEST ) ? $_REQUEST ['enddate'] : '';
				$equal = array_key_exists( 'equal', $_REQUEST ) ? $_REQUEST ['equal'] : '';
				$casesensitive = array_key_exists( 'casesensitive', $_REQUEST ) ? $_REQUEST ['casesensitive'] : '';
				$regexp = array_key_exists( 'regexp', $_REQUEST ) ? $_REQUEST ['regexp'] : '';
				$minus = array_key_exists( 'minus', $_REQUEST ) ? $_REQUEST ['minus'] : '';
				$grouping1 = array_key_exists( 'grouping1', $_REQUEST ) ? $_REQUEST ['grouping1'] : '';
				$grouping2 = array_key_exists( 'grouping2', $_REQUEST ) ? $_REQUEST ['grouping2'] : '';
				$order = array_key_exists( 'order', $_REQUEST ) ? $_REQUEST ['order'] : '';
				$display = $moduleSearch->do_search( $searchstring, $contractpartner, $postingaccount, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus, $grouping1, $grouping2, $order );
				break;

			/* settings */

			case 'personal_settings' :

				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
				$display = $moduleSettings->display_personal_settings( $realaction, $all_data );
				break;

			case 'upfrm_cmp_data' :

				$display = $moduleCompare->display_upload_form();
				break;
			case 'analyze_cmp_data' :

				$file = $_FILES ['file'];
				$display = $moduleCompare->display_analyze_form( $file, $all_data );
				break;

			default :

				$display = $moduleFrontPage->display_main();
				break;
		}
	}
}
if ($display) {
	header( 'Content-Type:text/html; charset=UTF-8' );
	echo $display;
}
if ($money_debug > 0) {
	$timer->mPrintTime();
}

?>
