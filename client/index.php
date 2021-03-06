<?php

//
// Copyright (c) 2005-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
use client\module\moduleEtf;
use client\module\moduleImportedMoneyflowReceipt;

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
if (array_key_exists( 'all_data', $_REQUEST ) && is_array( $_REQUEST ['all_data'] ))
	$all_data = $_REQUEST ['all_data'];

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
		// login successfull

		if (array_key_exists( 'request_uri', $_POST ) && parse_url( $_POST ['request_uri'] ) ['path'] == $_SERVER ['SCRIPT_NAME']) {
			$request_uri = $_POST ['request_uri'];
		} else {
			$request_uri = $_SERVER ['SCRIPT_NAME'];
		}

		$display = $moduleEvents->check_events( $request_uri );

		if (! $display) {
			// no events
			header( "Location: " . htmlentities( $request_uri ) );
		}
	}
}
// if ($money_debug === true)
error_reporting( E_ALL & ~ E_DEPRECATED ); // DEPRECATED for jpGraph

if ($is_logged_in == 0) {

	switch ($action) {
		case 'list_capitalsources' :
		case 'edit_capitalsource' :
		case 'edit_capitalsource_submit' :
		case 'delete_capitalsource' :
		case 'delete_capitalsource_submit' :
			$moduleCapitalSources = new moduleCapitalSources();
			break;
		case 'list_contractpartners' :
		case 'edit_contractpartner' :
		case 'edit_contractpartner_submit' :
		case 'delete_contractpartner' :
		case 'delete_contractpartner_submit' :
			$moduleContractPartners = new moduleContractPartners();
			break;
		case 'list_contractpartneraccounts' :
		case 'edit_contractpartneraccount' :
		case 'edit_contractpartneraccount_submit' :
		case 'delete_contractpartneraccount' :
		case 'delete_contractpartneraccount_submit' :
			$moduleContractPartnerAccounts = new moduleContractPartnerAccounts();
			break;
		case 'list_predefmoneyflows' :
		case 'edit_predefmoneyflow' :
		case 'edit_predefmoneyflow_submit' :
		case 'delete_predefmoneyflow' :
		case 'delete_predefmoneyflow_submit' :
			$modulePreDefMoneyFlows = new modulePreDefMoneyFlows();
			break;
		case 'edit_moneyflow' :
		case 'edit_moneyflow_submit' :
		case 'delete_moneyflow' :
		case 'delete_moneyflow_submit' :
		case 'show_moneyflow_receipt' :
		case 'delete_moneyflowreceipt_submit' :
		case 'search_moneyflow_by_amount' :
			$moduleMoneyFlows = new moduleMoneyFlows();
			break;
		case 'add_importedmoneyflows' :
		case 'add_importedmoneyflow_submit' :
			$moduleImportedMoneyFlows = new moduleImportedMoneyFlows();
			break;
		case 'list_monthlysettlements' :
		case 'edit_monthlysettlement' :
		case 'edit_monthlysettlement_submit' :
		case 'delete_monthlysettlement' :
		case 'delete_monthlysettlement_submit' :
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
		case 'list_etf_flows' :
		case 'calc_etf_sale' :
		case 'display_edit_etf_flow' :
		case 'edit_etf_flow' :
		case 'display_delete_etf_flow' :
		case 'delete_etf_flow' :
			$moduleEtf = new moduleEtf();
			break;
		case 'display_add_imported_moneyflow_receipt' :
		case 'add_imported_moneyflow_receipt' :
		case 'display_import_imported_moneyflow_receipts' :
		case 'import_imported_moneyflow_receipt_submit' :
			$moduleImportedMoneyflowReceipt = new moduleImportedMoneyflowReceipt();
			break;
		default :
			$moduleFrontPage = new moduleFrontPage();
			break;
	}

	if (empty( $display ) && $moduleUsers->is_admin()) {
		switch ($action) {
			case 'system_settings' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
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
				$display = $moduleLanguages->display_edit_language( $realaction, $id, $all_data );
				break;
			case 'add_language' :
				$realaction = array_key_exists( 'realaction', $_REQUEST ) ? $_REQUEST ['realaction'] : '';
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
				$access_relation = array_key_exists( 'access_relation', $_REQUEST ) ? $_REQUEST ['access_relation'] : array ();
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

				$capitalsourceid = array_key_exists( 'capitalsourceid', $_REQUEST ) ? $_REQUEST ['capitalsourceid'] : '';
				$display = $moduleCapitalSources->display_delete_capitalsource(  $capitalsourceid );
				break;

			case 'delete_capitalsource_submit' :

				$capitalsourceid = array_key_exists( 'capitalsourceid', $_REQUEST ) ? $_REQUEST ['capitalsourceid'] : '';
				$display = $moduleCapitalSources->delete_capitalsource(  $capitalsourceid );
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

				$id = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$display = $moduleContractPartners->display_delete_contractpartner( $id );
				break;

			case 'delete_contractpartner_submit' :

				$id = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$display = $moduleContractPartners->delete_contractpartner( $id );
				break;

			/* contractpartneraccounts */

			case 'list_contractpartneraccounts' :

				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$display = $moduleContractPartnerAccounts->display_list_contractpartneraccounts( $contractpartnerid );
				break;

			case 'edit_contractpartneraccount' :

				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : 0;
				$display = $moduleContractPartnerAccounts->display_edit_contractpartneraccount( $contractpartneraccountid, $contractpartnerid );
				break;

			case 'edit_contractpartneraccount_submit' :
				$contractpartnerid = array_key_exists( 'contractpartnerid', $_REQUEST ) ? $_REQUEST ['contractpartnerid'] : '';
				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : 0;
				$display = $moduleContractPartnerAccounts->edit_contractpartneraccount( $contractpartneraccountid, $contractpartnerid, $all_data );
				break;

			case 'delete_contractpartneraccount' :

				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : '';
				$display = $moduleContractPartnerAccounts->display_delete_contractpartneraccount( $contractpartneraccountid );
				break;

			case 'delete_contractpartneraccount_submit' :

				$contractpartneraccountid = array_key_exists( 'contractpartneraccountid', $_REQUEST ) ? $_REQUEST ['contractpartneraccountid'] : '';
				$display = $moduleContractPartnerAccounts->delete_contractpartneraccount( $contractpartneraccountid );
				break;

			/* predefmoneyflows */

			case 'list_predefmoneyflows' :

				$letter = array_key_exists( 'letter', $_REQUEST ) ? $_REQUEST ['letter'] : '';
				$display = $modulePreDefMoneyFlows->display_list_predefmoneyflows( $letter );
				break;

			case 'edit_predefmoneyflow' :

				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : 0;
				$display = $modulePreDefMoneyFlows->display_edit_predefmoneyflow( $id );
				break;

			case 'edit_predefmoneyflow_submit' :
				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : 0;
				$display = $modulePreDefMoneyFlows->edit_predefmoneyflow( $id, $all_data );
				break;

			case 'delete_predefmoneyflow' :
				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : '';
				$display = $modulePreDefMoneyFlows->display_delete_predefmoneyflow(  $id );
				break;

			case 'delete_predefmoneyflow_submit' :

				$id = array_key_exists( 'predefmoneyflowid', $_REQUEST ) ? $_REQUEST ['predefmoneyflowid'] : '';
				$display = $modulePreDefMoneyFlows->delete_predefmoneyflow(  $id );
				break;

			/* moneyflows */

			case 'edit_moneyflow' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : 0;
				$display = $moduleMoneyFlows->display_edit_moneyflow( $id );
				break;
			case 'edit_moneyflow_submit' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : 0;
				$all_subdata = array_key_exists( 'all_subdata', $_REQUEST ) ? $_REQUEST ['all_subdata'] : '';
				$display = $moduleMoneyFlows->edit_moneyflow( $id, $all_data, $all_subdata );
				break;

			case 'delete_moneyflow' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$display = $moduleMoneyFlows->display_delete_moneyflow( $id );
				break;

			case 'delete_moneyflow_submit' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : 0;
				$display = $moduleMoneyFlows->delete_moneyflow( $id );
				break;

			case 'show_moneyflow_receipt' :

				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$display = $moduleMoneyFlows->show_moneyflow_receipt( $id );
				break;

			case 'delete_moneyflowreceipt_submit' :
				$id = array_key_exists( 'moneyflowid', $_REQUEST ) ? $_REQUEST ['moneyflowid'] : '';
				$display = $moduleMoneyFlows->delete_moneyflowreceipt_submit( $id );
				break;

			case 'search_moneyflow_by_amount' :
				$amount = array_key_exists( 'amount', $_REQUEST ) ? $_REQUEST ['amount'] : '';
				$dateFrom = array_key_exists( 'datefrom', $_REQUEST ) ? $_REQUEST ['datefrom'] : '';
				$dateTil = array_key_exists( 'datetil', $_REQUEST ) ? $_REQUEST ['datetil'] : '';
				$display = $moduleMoneyFlows->search_moneyflow_by_amount( $amount, $dateFrom, $dateTil );
				break;

			/* imported moneyflows */

			case 'add_importedmoneyflow_submit' :

				$all_subdata = array_key_exists( 'all_subdata', $_REQUEST ) ? $_REQUEST ['all_subdata'] : '';
				$display = $moduleImportedMoneyFlows->process_importedmoneyflow( $all_data, $all_subdata );
				break;
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
				$display = $moduleMonthlySettlement->display_edit_monthlysettlement( $month, $year, $all_data );
				break;
			case 'edit_monthlysettlement_submit' :

				$display = $moduleMonthlySettlement->edit_monthlysettlement( $all_data );
				break;
			case 'delete_monthlysettlement' :

				$month = array_key_exists( 'monthlysettlements_month', $_REQUEST ) ? $_REQUEST ['monthlysettlements_month'] : '';
				$year = array_key_exists( 'monthlysettlements_year', $_REQUEST ) ? $_REQUEST ['monthlysettlements_year'] : '';
				$display = $moduleMonthlySettlement->display_delete_monthlysettlement( $month, $year );
				break;
			case 'delete_monthlysettlement_submit' :

				$month = array_key_exists( 'monthlysettlements_month', $_REQUEST ) ? $_REQUEST ['monthlysettlements_month'] : '';
				$year = array_key_exists( 'monthlysettlements_year', $_REQUEST ) ? $_REQUEST ['monthlysettlements_year'] : '';
				$display = $moduleMonthlySettlement->delete_monthlysettlement( $month, $year );
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
				$account_mode = array_key_exists( 'account_mode', $_POST ) ? $_POST ['account_mode'] : '0';
				$aggregate_month = array_key_exists( 'aggregate_month', $_POST ) ? $_POST ['aggregate_month'] : '0';
				$startdate = array_key_exists( 'startdate', $_POST ) ? $_POST ['startdate'] : '';
				$enddate = array_key_exists( 'enddate', $_POST ) ? $_POST ['enddate'] : '';
				$startyear = array_key_exists( 'startyear', $_POST ) ? $_POST ['startyear'] : '';
				$endyear = array_key_exists( 'endyear', $_POST ) ? $_POST ['endyear'] : '';
				$account = array_key_exists( 'account', $_POST ) ? $_POST ['account'] : '';

				$_accounts_yes = array_key_exists( 'accounts_yes', $_POST ) ? $_POST ['accounts_yes'] : '';
				if (strlen( $_accounts_yes ) > 0) {
					$accounts_yes = array_filter( explode( ',', $_accounts_yes ) );
				} else {
					$accounts_yes = array ();
				}

				$_accounts_no = array_key_exists( 'accounts_no', $_POST ) ? $_POST ['accounts_no'] : '';
				if (strlen( $_accounts_no ) > 0) {
					$accounts_no = array_filter( explode( ',', $_accounts_no ) );
				} else {
					$accounts_no = array ();
				}

				$display = $moduleReports->plot_report( $aggregate_month, $account_mode, $startdate, $enddate, $startyear, $endyear, $account, $accounts_yes, $accounts_no );
				break;
			case 'plot_trends' :

				$display = $moduleReports->display_plot_trends( $all_data );
				break;
			case 'plot_graph' :

				$id = array_key_exists( 'mcs_capitalsourceid', $_REQUEST ) ? $_REQUEST ['mcs_capitalsourceid'] : '';
				$startdate = array_key_exists( 'startdate', $_REQUEST ) ? $_REQUEST ['startdate'] : '';
				$enddate = array_key_exists( 'enddate', $_REQUEST ) ? $_REQUEST ['enddate'] : '';
				$display = $moduleReports->plot_graph( $id, $startdate, $enddate );
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

			/* ETF */
			case 'list_etf_flows' :
				$display = $moduleEtf->display_list_etf_flows();
				break;

			case 'calc_etf_sale' :
				$display = $moduleEtf->calc_etf_sale( $all_data );
				break;

			case 'display_edit_etf_flow' :
				$id = array_key_exists( 'etfflowid', $_REQUEST ) ? $_REQUEST ['etfflowid'] : 0;
				$display = $moduleEtf->display_edit_etf_flow( $id, $all_data );
				break;

			case 'edit_etf_flow' :
				$id = array_key_exists( 'etfflowid', $_REQUEST ) ? $_REQUEST ['etfflowid'] : 0;
				$display = $moduleEtf->edit_etf_flow( $id, $all_data );
				break;

			case 'display_delete_etf_flow' :
				$id = array_key_exists( 'etfflowid', $_REQUEST ) ? $_REQUEST ['etfflowid'] : 0;
				$display = $moduleEtf->display_delete_etf_flow( $id );
				break;

			case 'delete_etf_flow' :
				$id = array_key_exists( 'etfflowid', $_REQUEST ) ? $_REQUEST ['etfflowid'] : 0;
				$display = $moduleEtf->delete_etf_flow( $id );
				break;

			/* Imported Moneyflow Receipt */
			case 'display_add_imported_moneyflow_receipt' :
				$display = $moduleImportedMoneyflowReceipt->display_add_imported_moneyflow_receipt();
				break;

			case 'add_imported_moneyflow_receipt' :
				if (array_key_exists( 'all_data', $_FILES ) && is_array( $_FILES ['all_data'] )) {
					$file_data = $_FILES ['all_data'];
				} else {
					$file_data = null;
				}
				$display = $moduleImportedMoneyflowReceipt->add_imported_moneyflow_receipt( $file_data );
				break;

			case 'display_import_imported_moneyflow_receipts' :
				$display = $moduleImportedMoneyflowReceipt->display_import_imported_moneyflow_receipts();
				break;

			case 'import_imported_moneyflow_receipt_submit' :
				$display = $moduleImportedMoneyflowReceipt->import_imported_moneyflow_receipt_submit( $all_data );
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
