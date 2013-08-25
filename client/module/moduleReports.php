<?php
use rest\client\CallServer;
use rest\model\Capitalsource;
use rest\client\mapper\ClientArrayMapperEnum;
//
// Copyright (c) 2005-2013 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleReports.php,v 1.60 2013/08/25 01:03:32 olivleh1 Exp $
//

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreText.php';
require_once 'core/coreDomains.php';
require_once 'core/coreSettings.php';

if (ENABLE_JPGRAPH) {
	require_once 'jpgraph.php';
	require_once 'jpgraph_line.php';
}

class moduleReports extends module {

	public final function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowMapper', ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );

		// old shit
		$this->coreCurrencies = new coreCurrencies();
		$this->coreMoneyFlows = new coreMoneyFlows();
		$this->coreMonthlySettlement = new coreMonthlySettlement();
		$this->coreDomains = new coreDomains();
		$this->coreSettings = new coreSettings();
	}

	// uses REST Service
	public final function display_list_reports($month, $year, $sortby, $order) {
		if (! $year)
			$year = date( 'Y' );

		$years = rest\client\CallServer::getInstance()->getAllMoneyflowYears();
		$temp_months = rest\client\CallServer::getInstance()->getAllMoneyflowMonth( $year );

		// there are no months for the selected year
		if (! is_array( $temp_months )) {
			$year = $years [count( $years ) - 1];
			$temp_months = rest\client\CallServer::getInstance()->getAllMoneyflowMonth( $year );
			$month = NULL;
		}

		if (is_array( $temp_months )) {
			foreach ( $temp_months as $key => $value ) {
				$months [] = array (
						'nummeric' => sprintf( '%02d', $value ),
						'name' => $this->coreDomains->get_domain_meaning( 'MONTHS', ( int ) $value )
				);
			}
		}

		if ($month > 0 && $year > 0) {
			$report = $this->generate_report( $month, $year, $sortby, $order );
			$this->template->assign( 'REPORT', $report );
		}

		$this->template->assign( 'ALL_YEARS', $years );
		$this->template->assign( 'ALL_MONTHS', $months );
		$this->template->assign( 'SELECTED_MONTH', $month );
		$this->template->assign( 'SELECTED_YEAR', $year );

		$this->parse_header();
		return $this->fetch_template( 'display_list_reports.tpl' );
	}

	function generate_report($month, $year, $sortby, $order) {
		$lastamount = 0;
		$fixamount = 0;
		$movement_calculated_month = 0;
		$calcamount = 0;
		$i = 0;

		switch ($order) {
			case 'DESC' :
				$neworder = 'ASC';
				$multisort_order = SORT_DESC;
				break;
			case 'ASC' :
				$neworder = 'DESC';
				$multisort_order = SORT_ASC;
				break;
			default :
				$order = '';
				$multisort_order = SORT_DESC;
				$neworder = 'ASC';
		}

		$moneyflow = CallServer::getInstance()->getMoneyflowsByMonth( $year, $month );
		if ($moneyflow) {
			$_all_moneyflow_data = parent::mapArray($moneyflow);

			//TODO: old shit
			$displayed_currency = $this->coreCurrencies->get_displayed_currency();


			switch ($sortby) {
				case 'capitalsources_comment' :
					$sortby = 'capitalsourcecomment';
					$multisort_order = $multisort_order | SORT_STRING;
					break;
				case 'moneyflows_bookingdate' :
					$sortby = 'bookingdate';
					break;
				case 'moneyflows_invoicedate' :
					$sortby = 'invoicedate';
					break;
				case 'moneyflows_amount' :
					$sortby = 'amount';
					break;
				case 'moneyflows_comment' :
					$sortby = 'comment';
					$multisort_order = $multisort_order | SORT_STRING;
					break;
				case 'contractpartners_name' :
					$sortby = 'contractpartnername';
					$multisort_order = $multisort_order | SORT_STRING;
					break;
				default :
					$sortby = '';
			}

			if ($sortby != '') {
				foreach ( $_all_moneyflow_data as $key => $value ) {
					$sortKey1 [$key] = strtolower( $value [$sortby] );
					$sortKey2 [$key] = $value ['bookingdate'];
					$sortKey3 [$key] = $value ['invoicedate'];
					$sortKey4 [$key] = $value ['moneyflowid'];
				}

				array_multisort( $sortKey1, $multisort_order, $sortKey2, SORT_ASC, $sortKey3, SORT_ASC, $sortKey4, SORT_ASC, $_all_moneyflow_data );
			}

			$all_moneyflow_data = $_all_moneyflow_data;

			foreach ( $all_moneyflow_data as $key => $value ) {
				$all_moneyflow_data [$key] ['contractpartnername'] = htmlentities( $value ['contractpartnername'], ENT_COMPAT | ENT_HTML401 );
				$all_moneyflow_data [$key] ['capitalsourcecomment'] = htmlentities( $value ['capitalsourcecomment'], ENT_COMPAT | ENT_HTML401 );
				$all_moneyflow_data [$key] ['comment'] = htmlentities( $value ['comment'], ENT_COMPAT | ENT_HTML401 );
				if ($all_moneyflow_data [$key] ['mur_userid'] == USERID) {
					$all_moneyflow_data [$key] ['owner'] = true;
				} else {
					$all_moneyflow_data [$key] ['owner'] = false;
				}
			}
			$this->template->assign( 'ALL_MONEYFLOW_DATA', $all_moneyflow_data );

			// 1. check if there is already a monthly settlement entered in mms
			$mms_exists = $this->coreMonthlySettlement->monthlysettlement_exists( $month, $year );

			// 2. go through all capitalsources and determine there
			// a) comment
			// b) type comment
			// c) state comment
			// d) amount they had at the end of the previous month
			// e) amount they had at the end of the month (if mms_exists)
			// f) movement during the month
			// g) amount they should had at the end of the month
			// h) differnece between e and f (if mms_exists)

			$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( mktime( 0, 0, 0, $month, 1, $year ), mktime( 0, 0, 0, $month + 1, 0, $year ) );
			if (is_array( $capitalsourceArray )) {
				$all_capitalsources = parent::mapArray( $capitalsourceArray );
			}

			foreach ( $all_capitalsources as $capitalsource ) {
				$capitalsourceid = $capitalsource ['capitalsourceid'];

				$summary_data [$i] ['comment'] = $capitalsource ['comment'];
				$summary_data [$i] ['typecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_TYPE', $capitalsource ['type'] );
				$summary_data [$i] ['statecomment'] = $this->coreDomains->get_domain_meaning( 'CAPITALSOURCE_STATE', $capitalsource ['state'] );
				$summary_data [$i] ['lastamount'] = $this->coreMonthlySettlement->get_amount( $capitalsource ['mur_userid'], $capitalsourceid, date( 'm', mktime( 0, 0, 0, $month - 1, 1, $year ) ), date( 'Y', mktime( 0, 0, 0, $month - 1, 1, $year ) ) );
				if ($mms_exists === true) {
					$settlement_data = $this->coreMonthlySettlement->get_data( $capitalsourceid, $month, $year, true );

					$summary_data [$i] ['fixamount'] = $settlement_data ['amount'];
					$summary_data [$i] ['movement'] = round( $settlement_data ['movement_calculated'], 2 );
					$summary_data [$i] ['calcamount'] = round( $summary_data [$i] ['lastamount'] + $summary_data [$i] ['movement'], 2 );
					$summary_data [$i] ['difference'] = round( $summary_data [$i] ['fixamount'] - $summary_data [$i] ['calcamount'], 2 );
				} else {
					$summary_data [$i] ['movement'] = round( $this->coreMoneyFlows->get_monthly_capitalsource_movement( $capitalsource ['mur_userid'], $capitalsourceid, $month, $year ), 2 );
					$summary_data [$i] ['calcamount'] = $summary_data [$i] ['lastamount'] + $summary_data [$i] ['movement'];
				}

				// 3. sum fields up for various sum statistics
				// a) sum the amount of all capitalsources of the last month up
				// b) sum the amount of all capitalsources of the month up (if mms_exists)
				// c) sum the calculated amount of all capitalsources of the month up

				$lastamount += $summary_data [$i] ['lastamount'];
				if ($mms_exists === true)
					$fixamount += $summary_data [$i] ['fixamount'];
				$movement_calculated_month += $summary_data [$i] ['movement'];
				$calcamount += $summary_data [$i] ['calcamount'];

				$i ++;
			}

			// 4. retrieve the movement over the year until the selected month by using the values stored in mms

			$movement_calculated_year_data = $this->coreMonthlySettlement->get_year_movement( $month, $year, true );
			$movement_calculated_year = $movement_calculated_year_data ['movement_calculated'];
			$movement_calculated_year_month = $movement_calculated_year_data ['month'];

			// 4a.if mms doesn't contain all data up to the selected month, retrieve the missing data by using
			// the value calculated for the selected month (if the diference is just this month), or if the
			// difference is higher then calculate the missing range vby using mmf

			if ($movement_calculated_year_month != $month) {
				$startmonth = date( 'm', mktime( 0, 0, 0, $movement_calculated_year_month + 1, 1, $year ) );
				if ($startmonth == $month) {
					$movement_calculated_year += $movement_calculated_month;
				} else {
					$movement_calculated_year += $this->coreMoneyFlows->get_range_movement( $startmonth, $month, $year );
				}
			}

			// 4b.finally round it

			$movement_calculated_year = round( $movement_calculated_year, 2 );

			// 5. select the final amount of the last year (to calculate the turnover/movement since the last year)

			$firstamount = $this->coreMonthlySettlement->get_sum_amount( 12, $year - 1, true );

			if ($month == 1) {
				$prev_month = 12;
				$prev_year = $year - 1;
				$next_month = $month + 1;
				$next_year = $year;
			} elseif ($month == 12) {
				$prev_month = $month - 1;
				$prev_year = $year;
				$next_month = 1;
				$next_year = $year + 1;
			} else {
				$prev_month = $month - 1;
				$prev_year = $year;
				$next_month = $month + 1;
				$next_year = $year;
			}

			$prev_link = $this->coreMoneyFlows->month_has_moneyflows( $prev_month, $prev_year );
			$next_link = $this->coreMoneyFlows->month_has_moneyflows( $next_month, $next_year );

			$month = array (
					'nummeric' => sprintf( '%02d', $month ),
					'name' => $this->coreDomains->get_domain_meaning( 'MONTHS', ( int ) $month )
			);

			$this->template->assign( 'PREV_MONTH', $prev_month );
			$this->template->assign( 'PREV_YEAR', $prev_year );
			$this->template->assign( 'PREV_LINK', $prev_link );
			$this->template->assign( 'NEXT_MONTH', $next_month );
			$this->template->assign( 'NEXT_YEAR', $next_year );
			$this->template->assign( 'NEXT_LINK', $next_link );

			$this->template->assign( 'MONTH', $month );
			$this->template->assign( 'YEAR', $year );
			$this->template->assign( 'SORTBY', $sortby );
			$this->template->assign( 'ORDER', $neworder );
			$this->template->assign( 'SUMMARY_DATA', $summary_data );
			$this->template->assign( 'LASTAMOUNT', $lastamount );
			$this->template->assign( 'FIRSTAMOUNT', $firstamount );
			$this->template->assign( 'FIXAMOUNT', $fixamount );
			$this->template->assign( 'MON_CALCAMOUNT', $calcamount );
			$this->template->assign( 'MON_CALCULATEDTURNOVER', $movement_calculated_month );
			$this->template->assign( 'YEA_CALCULATEDTURNOVER', $movement_calculated_year );
			$this->template->assign( 'MONTHLYSETTLEMENT_EXISTS', $mms_exists );
			$this->template->assign( 'CURRENCY', $displayed_currency );

			return $this->fetch_template( 'display_generate_report.tpl' );
		}
	}

	function display_plot_trends($all_data) {
		$capitalsourceArray = CallServer::getInstance()->getAllCapitalsources();
		if (is_array( $capitalsourceArray )) {
			$capitalsource_values = parent::mapArray( $capitalsourceArray );
		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		}


		$years = $this->coreMonthlySettlement->get_all_years();
		// add the actual year to the years if the year changed no monthlysettlement
		// exists in that year during january - but you might want to see a prognose
		if ($years [count( $years ) - 1] != date( 'Y' )) {
			$years [] = date( 'Y' );
		}
		$this->template->assign( 'ALL_YEARS', $years );

		if (is_array( $all_data ) && isset( $all_data ['mcs_capitalsourceid'] )) {
			$this->coreSettings->set_trend_capitalsourceid( USERID, $all_data ['mcs_capitalsourceid'] );
			$this->template->assign( 'PLOT_GRAPH', 1 );
		} else {

			$all_data ['mcs_capitalsourceid'] = $this->coreSettings->get_trend_capitalsourceid( USERID );
			if (empty( $all_data [mcs_capitalsourceid] ))
				foreach ( $capitalsource_values as $capitalsource ) {
					$all_data [mcs_capitalsourceid] [$capitalsource ['capitalsourceid']] = 1;
				}
			$all_data ['endyear'] = $years [count( $years ) - 1];
			$all_data ['endmonth'] = 12;
			$this->template->assign( 'PLOT_GRAPH', 0 );
		}

		$this->template->assign( 'ALL_DATA', $all_data );

		$this->parse_header();
		return $this->fetch_template( 'display_plot_trends.tpl' );
	}

	function plot_graph($all_capitalsources_ids, $startmonth, $startyear, $endmonth, $endyear) {
		$coreText = new coreText();

		$graph_comment = $coreText->get_graph( 168 );
		$graph_from = $coreText->get_graph( 169 );
		$graph_until = $coreText->get_graph( 170 );
		$graph_xaxis = $coreText->get_graph( 171 );
		$graph_yaxis = $coreText->get_graph( 172 );

		$startdate = new DateTime( $startyear . "-" . $startmonth . "-01" );
		$enddate = new DateTime( $endyear . "-" . $endmonth . "-01" );
		$startdate_orig = clone $startdate;
		$enddate_orig = clone $enddate;

		// find first recorded monthly settlement
		$exists = false;
		while ( $exists === false && $startdate->format( "U" ) <= $enddate->format( "U" ) ) {
			$exists = $this->coreMonthlySettlement->monthlysettlement_exists( $startdate->format( "m" ), $startdate->format( "Y" ), $all_capitalsources_ids );
			if ($exists === false)
				$startdate->modify( "+1 month" );
		}

		// find last recorded monthly settlement
		$exists = false;
		while ( $exists === false && $enddate->format( "U" ) >= $startdate->format( "U" ) ) {
			$exists = $this->coreMonthlySettlement->monthlysettlement_exists( $enddate->format( "m" ), $enddate->format( "Y" ), $all_capitalsources_ids );
			if ($exists === false)
				$enddate->modify( "-1 month" );
		}

		$startdate_real = clone $startdate;
		$enddate_real = clone $enddate;

		// build the 1st graph containing all monthly settlements
		$i = 0;
		while ( $startdate->format( "U" ) <= $enddate->format( "U" ) ) {
			foreach ( $all_capitalsources_ids as $capitalsources_id ) {
				$monthly_data [$i] += $this->coreMonthlySettlement->get_amount( USERID, $capitalsources_id, $startdate->format( "m" ), $startdate->format( "Y" ) );
			}
			$monthly2_data [$i] = NULL;
			$monthly_x [$i] = $startdate->format( "m/y" );
			$i ++;
			$startdate->modify( "+1 month" );
		}

		// build the 12st graph containing all calculated monthly settlements based on the moneyflows happend after the last settlement
		if ($startdate->format( "U" ) > $enddate->format( "U" ) && $startdate->format( "U" ) <= $enddate_orig->format( "U" )) {
			$last_amount = $monthly_data [$i - 1];
			$monthly2_data [$i - 1] = $last_amount;
			$max_moneyflow_date = $this->coreMoneyFlows->get_max_year_month();

			$enddate = new DateTime( $max_moneyflow_date ['year'] . "-" . $max_moneyflow_date ['month'] . "-01" );
			$enddate_real = clone $enddate;

			while ( $startdate->format( "U" ) <= $enddate->format( "U" ) ) {
				foreach ( $all_capitalsources_ids as $capitalsources_id ) {
					$monthly2_data [$i] += $this->coreMoneyFlows->get_monthly_capitalsource_movement( USERID, $capitalsources_id, $startdate->format( "m" ), $startdate->format( "Y" ) );
				}
				$monthly2_data [$i] += $last_amount;
				$last_amount = $monthly2_data [$i];
				$monthly_x [$i] = $startdate->format( "m/y" );
				$i ++;
				$startdate->modify( "+1 month" );
			}
		} else {
			$monthly2_data = NULL;
		}

		$i --;
		// fill dummy x labels because the graph gets always stretcht x-wide as a multiple of 5
		while ( $i % 5 != 0 ) {
			$i ++;
			$monthly_x [$i] = $startdate->format( "m/y" );
			$startdate->modify( "+1 month" );
		}

		$graph = new Graph( 700, 400 );
		$graph->SetMargin( 50, 20, 40, 35 );
		$graph->SetScale( "intlin" );
		$graph->SetMarginColor( '#E6E6FA' );
		$graph->SetFrame( true, array (
				0,
				0,
				0
		), 0 );

		$txt = new Text( $graph_comment . "\n" . $graph_from . $startdate_real->format( "m/y" ) . $graph_until . $enddate_real->format( "m/y" ) );
		$txt->SetFont( FF_FONT1, FS_BOLD );
		$txt->Center( 0, 700 );
		$txt->ParagraphAlign( 'center' );
		$graph->AddText( $txt );

		$p1 = new LinePlot( $monthly_data );
		$p1->SetWeight( 1 );
		$p1->SetFillGradient( '#E6E6FA', '#B0C4DE' );
		$p1->mark->SetType( MARK_STAR );
		// $p1->value->Show();
		// $p1->value->SetAngle(90);
		$graph->Add( $p1 );

		if (is_array( $monthly2_data )) {
			$p2 = new LinePlot( $monthly2_data );
			$p2->SetWeight( 1 );
			$p2->SetFillGradient( '#aeaefa', '#689bde' );
			$p2->mark->SetType( MARK_STAR );
			// $p2->value->Show();
			// $p2->value->SetAngle(90);
			$graph->Add( $p2 );
		}

		$graph->xaxis->title->Set( $graph_xaxis );
		$graph->xaxis->SetTitleMargin( 10 );
		$graph->xaxis->SetTickLabels( $monthly_x );
		$graph->xaxis->SetFont( FF_FONT0 );
		$graph->xaxis->title->SetFont( FF_FONT1, FS_BOLD );

		$graph->yaxis->title->Set( $graph_yaxis );
		$graph->yaxis->SetTitleMargin( 35 );
		$graph->yaxis->SetFont( FF_FONT0 );
		$graph->yaxis->title->SetFont( FF_FONT1, FS_BOLD );

		$graph->xgrid->Show();

		$graph->Stroke();
	}
}
?>
