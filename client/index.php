<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: index.php,v 1.18 2006/12/19 12:54:10 olivleh1 Exp $
#

$action=$_POST['action']?$_POST['action']:$_GET['action'];

function my_number_format($number) {
	return number_format($number,2);
}

require_once 'include.php';
require_once 'functions.php';
require_once 'module/moduleCapitalSources.php';
require_once 'module/moduleContractPartners.php';
require_once 'module/moduleFrontPage.php';
require_once 'module/moduleMoneyFlows.php';
require_once 'module/moduleMonthlySettlement.php';
require_once 'module/modulePreDefMoneyFlows.php';
require_once 'module/moduleReports.php';
require_once 'module/moduleSearch.php';
require_once 'module/moduleUser.php';
require_once 'util/utilTimer.php';
#$timer = new utilTimer();
#$timer->mStart();

$moduleCapitalSources		= new moduleCapitalSources();
$moduleContractPartners		= new moduleContractPartners();
$moduleFrontPage		= new moduleFrontPage();
$moduleMoneyFlows		= new moduleMoneyFlows();
$moduleMonthlySettlement	= new moduleMonthlySettlement();
$modulePreDefMoneyFlows		= new modulePreDefMoneyFlows();
$moduleReports			= new moduleReports();
$moduleSearch			= new moduleSearch();
$moduleUser			= new moduleUser();

if( !$moduleUser->is_logged_in() ) {
	$realaction=	$_POST['realaction'];
	$name=		$_POST['name'];
	$password=	$_POST['password'];
	$display=$moduleUser->display_login_user( $realaction, $name, $password );
}

if( $moduleUser->is_logged_in() ) {

switch( $action ) {
	/* capitalsources */

	case 'list_capitalsources':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$moduleCapitalSources->display_list_capitalsources( $letter );
					break;

	case 'edit_capitalsource':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleCapitalSources->display_edit_capitalsource( $realaction, $id, $all_data );
					break;

	case 'delete_capitalsource':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleCapitalSources->display_delete_capitalsource( $realaction, $id );
					break;

	/* contractpartners */

	case 'list_contractpartners':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$moduleContractPartners->display_list_contractpartners( $letter );
					break;

	case 'edit_contractpartner':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleContractPartners->display_edit_contractpartner( $realaction, $id, $all_data );
					break;

	case 'delete_contractpartner':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleContractPartners->display_delete_contractpartner( $realaction, $id );
					break;

	/* predefmoneyflows */

	case 'list_predefmoneyflows':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$modulePreDefMoneyFlows->display_list_predefmoneyflows( $letter );
					break;

	case 'edit_predefmoneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$modulePreDefMoneyFlows->display_edit_predefmoneyflow( $realaction, $id, $all_data );
					break;

	case 'delete_predefmoneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$modulePreDefMoneyFlows->display_delete_predefmoneyflow( $realaction, $id );
					break;

	/* moneyflows */

	case 'add_moneyflow':		$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$all_data=	$_POST['all_data'];
					$display=$moduleMoneyFlows->display_add_moneyflow( $realaction, $all_data );
					break;
	case 'edit_moneyflow':		$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleMoneyFlows->display_edit_moneyflow( $realaction, $id, $all_data );
					break;

	case 'delete_moneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleMoneyFlows->display_delete_moneyflow( $realaction, $id );
					break;


	/* monthlysettlements */

	case 'list_monthlysettlements':	$month=		$_GET['monthlysettlements_month'];
					$year=		$_GET['monthlysettlements_year'];
					$display=$moduleMonthlySettlement->display_list_monthlysettlements( $month, $year );
					break;
	case 'edit_monthlysettlement':	$month=		$_POST['monthlysettlements_month']?$_POST['monthlysettlements_month']:$_GET['monthlysettlements_month'];
					$year=		$_POST['monthlysettlements_year']?$_POST['monthlysettlements_year']:$_GET['monthlysettlements_year'];
					$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$all_data=	$_POST['all_data'];
					$display=$moduleMonthlySettlement->display_edit_monthlysettlement( $realaction, $month, $year, $all_data );
					break;
	case 'delete_monthlysettlement':$month=		$_POST['monthlysettlements_month']?$_POST['monthlysettlements_month']:$_GET['monthlysettlements_month'];
					$year=		$_POST['monthlysettlements_year']?$_POST['monthlysettlements_year']:$_GET['monthlysettlements_year'];
					$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$display=$moduleMonthlySettlement->display_delete_monthlysettlement( $realaction, $month, $year );
					break;

	/* reports */

	case 'list_reports':		$month=		$_GET['reports_month'];
					$year=		$_GET['reports_year'];
					$sortby=	$_GET['reports_sortby'];
					$order=		$_GET['reports_order'];
					$display=$moduleReports->display_list_reports( $month, $year, $sortby, $order );
					break;
	case 'plot_trends':		$all_data=	$_POST['all_data'];
					$display = ( ENABLE_JPGRAPH ? $moduleReports->display_plot_trends($all_data) : '' );
					break;
	case 'plot_graph':		$id=		$_GET['id'];
					$startmonth=	$_GET['startmonth'];
					$startyear=	$_GET['startyear'];
					$endmonth=	$_GET['endmonth'];
					$endyear=	$_GET['endyear'];
					$display = ( ENABLE_JPGRAPH ? $moduleReports->plot_graph( $id, $startmonth, $startyear, $endmonth, $endyear ) : '' );
					break;

	/* search */

	case 'search':			$display=$moduleSearch->display_search();
					break;
	case 'do_search':		$searchstring=	$_POST['searchstring'];
					$contractpart=	$_POST['contractpartner'];
					$startdate=	$_POST['startdate'];
					$enddate=	$_POST['enddate'];
					$equal=		$_POST['equal'];
					$casesensitive=	$_POST['casesensitive'];
					$regexp=	$_POST['regexp'];
					$minus=		$_POST['minus'];
					$display=$moduleSearch->do_search( $searchstring, $contractpart, $startdate, $enddate, $equal, $casesensitive, $regexp, $minus );
					break;
	
	/* users */
	
	case 'logout':			$display=$moduleUser->display_login_user( 'logout', NULL, NULL );
					break;

	default:			$display=$moduleFrontPage->display_main();
					break;
}
}
echo $display;
#$timer->mPrintTime();
?>
