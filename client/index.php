<?php

/*
	$Id: index.php,v 1.3 2005/03/05 00:31:27 olivleh1 Exp $
*/

if( ! empty($_GET['action']) || ! empty($_POST['action']) )
	$action=$_POST['action']?$_POST['action']:$_GET['action'];
else
	$action='main';

function my_number_format($number) {
	return number_format($number,2);
}

require_once 'include.php';
require_once 'module/moduleCapitalSources.php';
require_once 'module/moduleContractPartners.php';
require_once 'module/moduleFrontPage.php';
require_once 'module/moduleMoneyFlows.php';
require_once 'module/moduleMonthlySettlement.php';
require_once 'module/modulePreDefMoneyFlows.php';
require_once 'module/moduleReports.php';
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

switch( $action ) {
	case 'main':			$display=$moduleFrontPage->display_main();
					break;

	/* capitalsources */

	case 'list_capitalsources':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$moduleCapitalSources->display_list_capitalsources($letter);
					break;

	case 'edit_capitalsource':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleCapitalSources->display_edit_capitalsource($realaction,$id,$all_data);
					break;

	case 'delete_capitalsource':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleCapitalSources->display_delete_capitalsource($realaction,$id);
					break;

	/* contractpartners */

	case 'list_contractpartners':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$moduleContractPartners->display_list_contractpartners($letter);
					break;

	case 'edit_contractpartner':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleContractPartners->display_edit_contractpartner($realaction,$id,$all_data);
					break;

	case 'delete_contractpartner':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleContractPartners->display_delete_contractpartner($realaction,$id);
					break;

	/* predefmoneyflows */

	case 'list_predefmoneyflows':	$letter=	$_POST['letter']?$_POST['letter']:$_GET['letter'];
					$display=$modulePreDefMoneyFlows->display_list_predefmoneyflows($letter);
					break;

	case 'edit_predefmoneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$modulePreDefMoneyFlows->display_edit_predefmoneyflow($realaction,$id,$all_data);
					break;

	case 'delete_predefmoneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$modulePreDefMoneyFlows->display_delete_predefmoneyflow($realaction,$id);
					break;

	/* moneyflows */

	case 'edit_moneyflow':		$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$all_data=	$_POST['all_data'];
					$display=$moduleMoneyFlows->display_edit_moneyflow($realaction,$id,$all_data);
					break;

	case 'delete_moneyflow':	$realaction=	$_POST['realaction']?$_POST['realaction']:$_GET['realaction'];
					$id=		$_POST['id']?$_POST['id']:$_GET['id'];
					$display=$moduleMoneyFlows->display_delete_moneyflow($realaction,$id);
					break;



/* START: REWRITE ME */

	case 'add_moneyflows':		$display=$moduleMoneyFlows->display_add_moneyflows();
					break;
	case 'save_moneyflows':		$display=$moduleMoneyFlows->save_moneyflows();
					break;
	case 'show_monthlysettlement':	$display=$moduleMonthlySettlement->display_show_monthlysettlement();
					break;
	case 'edit_monthlysettlement':	$display=$moduleMonthlySettlement->edit_monthlysettlement();
					break;
	case 'generate report':		$display=$moduleReports->generate_report();
					break;
/* END: REWRITE ME */
}

echo $display;
#$timer->mPrintTime();
?>
