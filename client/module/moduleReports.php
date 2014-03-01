<?php
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleReports.php,v 1.84 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\module;

use client\handler\ReportControllerHandler;
use client\core\coreText;

if (ENABLE_JPGRAPH) {
	require_once 'jpgraph.php';
	require_once 'jpgraph_line.php';
}

class moduleReports extends module {
	private $coreText;

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText( parent::getGuiLanguage() );
	}

	public final function display_list_reports($month, $year, $sortby, $order) {
		if (! $year)
			$year = date( 'Y' );

		$listReports = ReportControllerHandler::getInstance()->listReports( $year, $month );
		$years = $listReports ['allYears'];
		$allMonth = $listReports ['allMonth'];
		$year = $listReports ['year'];
		$month = $listReports ['month'];
		$_all_moneyflow_data = $listReports ['moneyflows'];
		$turnover_capitalsources = $listReports ['turnover_capitalsources'];
		$firstamount = $listReports ['firstamount'];
		$movement_calculated_year = $listReports ['calculated_yearly_turnover'];
		$prev_link = $listReports ['prev_link'];
		$next_link = $listReports ['next_link'];
		$prev_month = $listReports ['prev_month'];
		$prev_year = $listReports ['prev_year'];
		$next_month = $listReports ['next_month'];
		$next_year = $listReports ['next_year'];

		$mms_exists = false;
		$report = 0;

		if (is_array( $allMonth )) {
			foreach ( $allMonth as $key => $value ) {
				$months [] = array (
						'nummeric' => sprintf( '%02d', $value ),
						'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $value )
				);
			}
		}

		if ($month > 0 && $year > 0) {
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

			if ($_all_moneyflow_data) {

				switch ($sortby) {
					case 'capitalsources_comment' :
						$sortby_int = 'capitalsourcecomment';
						$multisort_order = $multisort_order | SORT_STRING;
						break;
					case 'moneyflows_bookingdate' :
						$sortby_int = 'bookingdate';
						break;
					case 'moneyflows_invoicedate' :
						$sortby_int = 'invoicedate';
						break;
					case 'moneyflows_amount' :
						$sortby_int = 'amount';
						break;
					case 'moneyflows_comment' :
						$sortby_int = 'comment';
						$multisort_order = $multisort_order | SORT_STRING;
						break;
					case 'contractpartners_name' :
						$sortby_int = 'contractpartnername';
						$multisort_order = $multisort_order | SORT_STRING;
						break;
					case 'postingaccount_name' :
						$sortby_int = 'postingaccountname';
						$multisort_order = $multisort_order | SORT_STRING;
						break;
					default :
						$sortby_int = '';
				}

				if ($sortby_int != '') {
					foreach ( $_all_moneyflow_data as $key => $value ) {
						$sortKey1 [$key] = strtolower( $value [$sortby_int] );
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
					$all_moneyflow_data [$key] ['postingaccountname'] = htmlentities( $value ['postingaccountname'], ENT_COMPAT | ENT_HTML401 );
					$all_moneyflow_data [$key] ['comment'] = htmlentities( $value ['comment'], ENT_COMPAT | ENT_HTML401 );
					if ($all_moneyflow_data [$key] ['mur_userid'] == USERID) {
						$all_moneyflow_data [$key] ['owner'] = true;
					} else {
						$all_moneyflow_data [$key] ['owner'] = false;
					}
				}

				if (is_array( $turnover_capitalsources )) {
					foreach ( $turnover_capitalsources as $key => $turnover_capitalsource ) {
						$turnover_capitalsources [$key] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $turnover_capitalsource ['type'] );
						$turnover_capitalsources [$key] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $turnover_capitalsource ['state'] );
						$movement_calculated_month += $turnover_capitalsource ['calcamount'] - $turnover_capitalsource ['lastamount'];
						$calcamount += $turnover_capitalsource ['calcamount'];
						$lastamount += $turnover_capitalsource ['lastamount'];
						if (array_key_exists( 'fixamount', $turnover_capitalsource )) {
							$fixamount += $turnover_capitalsource ['fixamount'];
							$mms_exists = true;
						}
					}
				}

				$month_array = array (
						'nummeric' => sprintf( '%02d', $month ),
						'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $month )
				);

				$this->template->assign( 'ALL_MONEYFLOW_DATA', $all_moneyflow_data );

				$this->template->assign( 'PREV_MONTH', $prev_month );
				$this->template->assign( 'PREV_YEAR', $prev_year );
				$this->template->assign( 'PREV_LINK', $prev_link );
				$this->template->assign( 'NEXT_MONTH', $next_month );
				$this->template->assign( 'NEXT_YEAR', $next_year );
				$this->template->assign( 'NEXT_LINK', $next_link );

				$this->template->assign( 'MONTH', $month_array );
				$this->template->assign( 'SORTBY', $sortby );
				$this->template->assign( 'NEWORDER', $neworder );
				$this->template->assign( 'ORDER', $order );
				$this->template->assign( 'SUMMARY_DATA', $turnover_capitalsources );
				$this->template->assign( 'LASTAMOUNT', $lastamount );
				$this->template->assign( 'FIRSTAMOUNT', $firstamount );
				$this->template->assign( 'FIXAMOUNT', $fixamount );
				$this->template->assign( 'MON_CALCAMOUNT', $calcamount );
				$this->template->assign( 'MON_CALCULATEDTURNOVER', $movement_calculated_month );
				$this->template->assign( 'YEA_CALCULATEDTURNOVER', $movement_calculated_year );
				$this->template->assign( 'MONTHLYSETTLEMENT_EXISTS', $mms_exists );
				$report = 1;
			}
		}

		$this->template->assign( 'REPORT', $report );
		$this->template->assign( 'ALL_YEARS', $years );
		$this->template->assign( 'ALL_MONTHS', $months );
		$this->template->assign( 'SELECTED_MONTH', $month );
		$this->template->assign( 'SELECTED_YEAR', $year );

		$this->parse_header();
		return $this->fetch_template( 'display_list_reports.tpl' );
	}

	public final function display_plot_trends($all_data) {
		$showTrendsForm = ReportControllerHandler::getInstance()->showTrendsForm();
		if (is_array( $showTrendsForm ['capitalsources'] )) {
			$this->template->assign( 'CAPITALSOURCE_VALUES', $showTrendsForm ['capitalsources'] );
		}
		$years = $showTrendsForm ['allYears'];
		$this->template->assign( 'ALL_YEARS', $years );

		if (is_array( $all_data ) && isset( $all_data ['mcs_capitalsourceid'] )) {
			$this->template->assign( 'PLOT_GRAPH', 1 );
		} else {
			$all_data ['mcs_capitalsourceid'] = $showTrendsForm ['selected_capitalsources'];
			if (empty( $all_data ['mcs_capitalsourceid'] ))
				foreach ( $showTrendsForm ['capitalsources'] as $capitalsource ) {
					$all_data ['mcs_capitalsourceid'] [$capitalsource ['capitalsourceid']] = 1;
				}
			$all_data ['endyear'] = $years [count( $years ) - 1];
			$all_data ['endmonth'] = 12;
			$this->template->assign( 'PLOT_GRAPH', 0 );
		}

		$this->template->assign( 'ALL_DATA', $all_data );

		$this->parse_header();
		return $this->fetch_template( 'display_plot_trends.tpl' );
	}

	public final function plot_graph($all_capitalsources_ids, $startmonth, $startyear, $endmonth, $endyear) {
		$startdate = new DateTime( $startyear . "-" . $startmonth . "-01" );
		$enddate = new DateTime( $endyear . "-" . $endmonth . "-01" );
		$showTrendsGraph = ReportControllerHandler::getInstance()->showTrendsGraph( $all_capitalsources_ids, $startdate, $enddate );

		$graph_comment = $this->coreText->get_graph( 168 );
		$graph_from = $this->coreText->get_graph( 169 );
		$graph_until = $this->coreText->get_graph( 170 );
		$graph_xaxis = $this->coreText->get_graph( 171 );
		$graph_yaxis = $this->coreText->get_graph( 172 );

		$i = 0;
		if (is_array( $showTrendsGraph ['settled'] )) {
			foreach ( $showTrendsGraph ['settled'] as $settledAmount ) {
				$monthly_data [$i] = $settledAmount ['amount'];
				$monthly2_data [$i] = NULL;
				$monthly_x [$i] = sprintf( "%02s/%02s", $settledAmount ['month'], substr( $settledAmount ['year'], 2 ) );
				$i ++;
			}
		}

		if (is_array( $showTrendsGraph ['calculated'] )) {
			$last_amount = $monthly_data [$i - 1];
			$monthly2_data [$i - 1] = $last_amount;
			foreach ( $showTrendsGraph ['calculated'] as $calculatedAmount ) {
				$monthly2_data [$i] += $calculatedAmount ['amount'];
				$monthly_x [$i] = sprintf( "%02s/%02s", $calculatedAmount ['month'], substr( $calculatedAmount ['year'], 2 ) );
				$i ++;
			}
		} else {
			$monthly2_data = NULL;
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

		$txt = new Text( $graph_comment . "\n" . $graph_from . $monthly_x [0] . $graph_until . end( $monthly_x ) );
		$txt->SetFont( FF_FONT1, FS_BOLD );
		$txt->Center( 0, 700 );
		$txt->ParagraphAlign( 'center' );
		$graph->AddText( $txt );

		$p1 = new LinePlot( $monthly_data );
		$p1->SetWeight( 1 );
		$p1->SetFillGradient( '#E6E6FA', '#B0C4DE' );
		$p1->mark->SetType( MARK_STAR );
		$graph->Add( $p1 );

		if (is_array( $monthly2_data )) {
			$p2 = new LinePlot( $monthly2_data );
			$p2->SetWeight( 1 );
			$p2->SetFillGradient( '#aeaefa', '#689bde' );
			$p2->mark->SetType( MARK_STAR );
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
