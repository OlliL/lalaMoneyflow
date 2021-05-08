<?php

//
// Copyright (c) 2005-2019 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: moduleReports.php,v 1.113 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client\module;

use client\handler\ReportControllerHandler;
use client\handler\EtfControllerHandler;
use client\core\coreText;
use client\util\Environment;
use base\Configuration;

class moduleReports extends module {
	private $coreText;
	private const TRENDS_DATE_FORMAT = 'm/Y';
	private const TRENDS_YEAR_FORMAT = 'Y';

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText();
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
		$moneyflow_split_entries = $listReports ['moneyflow_split_entries'];
		$moneyflows_with_receipt = $listReports ['moneyflows_with_receipt'];

		$mms_exists = false;
		$report = 0;
		$months = array ();

		if (is_array( $allMonth )) {
			foreach ( $allMonth as $key => $value ) {
				$months [] = array (
						'nummeric' => sprintf( '%02d', $value ),
						'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $value )
				);
			}
		}

		if ($month > 0 && $year > 0) {

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

				$movement = 0;
				foreach ( $all_moneyflow_data as $key => $value ) {
					if ($all_moneyflow_data [$key] ['mur_userid'] == Environment::getInstance()->getUserId()) {
						$all_moneyflow_data [$key] ['owner'] = true;
					} else {
						$all_moneyflow_data [$key] ['owner'] = false;
					}
					$movement += $value ['amount'];

					$moneyflowId = $value ['moneyflowid'];
					$moneyflowSplitEntries = array ();

					foreach ( $moneyflow_split_entries as $key2 => $value2 ) {
						if ($moneyflowId == $value2 ['moneyflowid']) {
							$moneyflowSplitEntries [] = $value2;
							unset( $moneyflow_split_entries [$key2] );
						}
					}

					$all_moneyflow_data [$key] ['has_moneyflow_split_entries'] = count( $moneyflowSplitEntries );
					if (count( $moneyflowSplitEntries ) > 0) {
						$all_moneyflow_data [$key] ['moneyflow_split_entries'] = $moneyflowSplitEntries;
					}

					if (is_array( $moneyflows_with_receipt ) && in_array( $value ['moneyflowid'], $moneyflows_with_receipt )) {
						$all_moneyflow_data [$key] ['has_receipt'] = 1;
					} else {
						$all_moneyflow_data [$key] ['has_receipt'] = 0;
					}
				}

				$assets_turnover_capitalsources = null;
				$assets_lastamount = 0;
				$assets_fixamount = 0;
				$assets_movement_calculated_month = 0;
				$assets_calcamount = 0;
				$assets_currentamount = 0;
				$assets_counter = 0;

				$liabilities_turnover_capitalsources = null;
				$liabilities_lastamount = 0;
				$liabilities_fixamount = 0;
				$liabilities_calcamount = 0;
				$liabilities_currentamount = 0;
				$liabilities_counter = 0;

				$credit_turnover_capitalsources = null;
				$credit_lastamount = 0;
				$credit_fixamount = 0;
				$credit_calcamount = 0;
				$credit_currentamount = 0;
				$credit_counter = 0;

				if (is_array( $turnover_capitalsources ) && count( $turnover_capitalsources ) > 0) {
					foreach ( $turnover_capitalsources as $turnover_capitalsource ) {
						switch ($turnover_capitalsource ['type']) {
							case 1 :
							case 2 :
								$assets_turnover_capitalsources [$assets_counter] = $turnover_capitalsource;
								$assets_turnover_capitalsources [$assets_counter] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $turnover_capitalsource ['type'] );
								$assets_turnover_capitalsources [$assets_counter] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $turnover_capitalsource ['state'] );
								$assets_movement_calculated_month += $turnover_capitalsource ['calcamount'] - $turnover_capitalsource ['lastamount'];
								$assets_calcamount += $turnover_capitalsource ['calcamount'];
								$assets_lastamount += $turnover_capitalsource ['lastamount'];
								if (array_key_exists( 'amount_current', $turnover_capitalsource ))
									$assets_currentamount += $turnover_capitalsource ['amount_current'];
								if (array_key_exists( 'fixamount', $turnover_capitalsource )) {
									$assets_fixamount += $turnover_capitalsource ['fixamount'];
									$mms_exists = true;
								}
								$assets_counter ++;
								break;
							case 3 :
							case 4 :
								$liabilities_turnover_capitalsources [$liabilities_counter] = $turnover_capitalsource;
								$liabilities_turnover_capitalsources [$liabilities_counter] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $turnover_capitalsource ['type'] );
								$liabilities_turnover_capitalsources [$liabilities_counter] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $turnover_capitalsource ['state'] );
								$liabilities_calcamount += $turnover_capitalsource ['calcamount'];
								$liabilities_lastamount += $turnover_capitalsource ['lastamount'];
								if (array_key_exists( 'amount_current', $turnover_capitalsource ))
									$liabilities_currentamount += $turnover_capitalsource ['amount_current'];
								if (array_key_exists( 'fixamount', $turnover_capitalsource )) {
									$liabilities_fixamount += $turnover_capitalsource ['fixamount'];
									$mms_exists = true;
								}
								$liabilities_counter ++;
								break;
							case 5 :
								$credit_turnover_capitalsources [$credit_counter] = $turnover_capitalsource;
								$credit_turnover_capitalsources [$credit_counter] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $turnover_capitalsource ['type'] );
								$credit_turnover_capitalsources [$credit_counter] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $turnover_capitalsource ['state'] );
								$credit_calcamount += $turnover_capitalsource ['calcamount'];
								$credit_lastamount += $turnover_capitalsource ['lastamount'];
								if (array_key_exists( 'amount_current', $turnover_capitalsource ))
									$credit_currentamount += $turnover_capitalsource ['amount_current'];
								if (array_key_exists( 'fixamount', $turnover_capitalsource )) {
									$credit_fixamount += $turnover_capitalsource ['fixamount'];
									$mms_exists = true;
								}
								$credit_counter ++;
								break;
						}
					}
				}

				$month_array = array (
						'nummeric' => sprintf( '%02d', $month ),
						'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $month )
				);
				$listEtfOverviewResponse = EtfControllerHandler::getInstance()->listEtfOverview( $year, $month );
				if (is_array( $listEtfOverviewResponse ))
					$this->template_assign( 'ETF_OVERVIEW_DATA', $listEtfOverviewResponse ['etfData'] );

				$this->template_assign( 'ALL_MONEYFLOW_DATA', $all_moneyflow_data );

				$this->template_assign( 'PREV_MONTH', $prev_month );
				$this->template_assign( 'PREV_YEAR', $prev_year );
				$this->template_assign( 'PREV_LINK', $prev_link );
				$this->template_assign( 'NEXT_MONTH', $next_month );
				$this->template_assign( 'NEXT_YEAR', $next_year );
				$this->template_assign( 'NEXT_LINK', $next_link );

				$this->template_assign( 'MONTH', $month_array );
				$this->template_assign( 'SORTBY', $sortby );
				$this->template_assign( 'NEWORDER', $neworder );
				$this->template_assign( 'ORDER', $order );

				// Assets
				$this->template_assign( 'SUMMARY_DATA', $assets_turnover_capitalsources );
				$this->template_assign( 'LASTAMOUNT', $assets_lastamount );
				$this->template_assign( 'FIXAMOUNT', $assets_fixamount );
				$this->template_assign( 'CURRENTAMOUNT', $assets_currentamount );
				$this->template_assign( 'MON_CALCAMOUNT', $assets_calcamount );
				$this->template_assign( 'MON_CALCULATEDTURNOVER', $assets_movement_calculated_month );
				$this->template_assign( 'FIRSTAMOUNT', $firstamount );
				$this->template_assign( 'YEA_CALCULATEDTURNOVER', $movement_calculated_year );

				// Liabilites
				$this->template_assign( 'LIABILITIES_SUMMARY_DATA', $liabilities_turnover_capitalsources );
				$this->template_assign( 'LIABILITIES_LASTAMOUNT', $liabilities_lastamount );
				$this->template_assign( 'LIABILITIES_FIXAMOUNT', $liabilities_fixamount );
				$this->template_assign( 'LIABILITIES_CURRENTAMOUNT', $liabilities_currentamount );
				$this->template_assign( 'LIABILITIES_MON_CALCAMOUNT', $liabilities_calcamount );

				// Credits
				$this->template_assign( 'CREDITS_SUMMARY_DATA', $credit_turnover_capitalsources );
				$this->template_assign( 'CREDITS_LASTAMOUNT', $credit_lastamount );
				$this->template_assign( 'CREDITS_FIXAMOUNT', $credit_fixamount );
				$this->template_assign( 'CREDITS_CURRENTAMOUNT', $credit_currentamount );
				$this->template_assign( 'CREDITS_MON_CALCAMOUNT', $credit_calcamount );

				$this->template_assign( 'MOVEMENT', $movement );
				$this->template_assign( 'MONTHLYSETTLEMENT_EXISTS', $mms_exists );
				$report = 1;
			}
		}

		$this->template_assign( 'REPORT', $report );
		$this->template_assign( 'ALL_YEARS', $years );
		$this->template_assign( 'ALL_MONTHS', $months );
		$this->template_assign( 'SELECTED_MONTH', $month );
		$this->template_assign( 'SELECTED_YEAR', $year );

		$this->parse_header_bootstraped( 0, 'display_list_reports_bs.tpl' );
		return $this->fetch_template( 'display_list_reports_bs.tpl' );
	}

	public final function display_plot_trends($all_data) {
		$showTrendsForm = ReportControllerHandler::getInstance()->showTrendsForm();

		if (is_array( $showTrendsForm ['capitalsources'] )) {
			$this->template_assign( 'CAPITALSOURCE_VALUES', $showTrendsForm ['capitalsources'] );
		}

		$this->template_assign( 'SELECTED_CAPITALSOURCEIDS', $showTrendsForm ['selected_capitalsources'] );
		$this->template_assign( 'START_DATE', date_format( new \DateTime( $showTrendsForm ['minDate'] ), self::TRENDS_DATE_FORMAT ) );
		$this->template_assign( 'END_DATE', date_format( new \DateTime( $showTrendsForm ['maxDate'] ), self::TRENDS_DATE_FORMAT ) );

		$this->parse_header_without_embedded( 0, 'display_plot_trends_bs.tpl' );
		return $this->fetch_template( 'display_plot_trends_bs.tpl' );
	}

	public final function plot_graph($all_capitalsources_ids, $aStartdate, $aEnddate) {
		$startdate = \DateTime::createFromFormat( self::TRENDS_DATE_FORMAT . "/d", $aStartdate . "/01" );
		$enddate = \DateTime::createFromFormat( self::TRENDS_DATE_FORMAT . "/d", $aEnddate . "/01" );

		$showTrendsGraph = ReportControllerHandler::getInstance()->showTrendsGraph( $all_capitalsources_ids, $startdate, $enddate );

		return $this->handleReturnForAjax( $showTrendsGraph );
	}

	public final function show_reporting_form() {
		$showReportingFormResponse = ReportControllerHandler::getInstance()->showReportingForm();

		$postingaccount_values = $showReportingFormResponse ['postingaccounts'];
		$accounts_no_settings = $showReportingFormResponse ['accounts_no'];

		$accounts_no = array ();
		$accounts_yes = array ();

		foreach ( $postingaccount_values as $postingaccount ) {
			if (is_array( $accounts_no_settings ) && in_array( $postingaccount ['postingaccountid'], $accounts_no_settings )) {
				$accounts_no [] = $postingaccount;
			} else {
				$accounts_yes [] = $postingaccount;
			}
		}

		$this->template_assign( 'START_DATE', date_format( new \DateTime( $showReportingFormResponse ['minDate'] ), self::TRENDS_DATE_FORMAT ) );
		$this->template_assign( 'END_DATE', date_format( new \DateTime( $showReportingFormResponse ['maxDate'] ), self::TRENDS_DATE_FORMAT ) );

		$this->template_assign( 'START_YEAR', date_format( new \DateTime( $showReportingFormResponse ['minDate'] ), self::TRENDS_YEAR_FORMAT ) );
		$this->template_assign( 'END_YEAR', date_format( new \DateTime( $showReportingFormResponse ['maxDate'] ), self::TRENDS_YEAR_FORMAT ) );

		$this->template_assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
		$this->template_assign( 'ACCOUNTS_YES', $accounts_yes );
		$this->template_assign( 'ACCOUNTS_NO', $accounts_no );

		$this->parse_header_without_embedded( 0, 'display_show_reporting_form_bs.tpl' );
		return $this->fetch_template( 'display_show_reporting_form_bs.tpl' );
	}

	private final function randomColor() {
		$possibilities = array (
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8,
				9,
				"A",
				"B",
				"C",
				"D"
		);
		shuffle( $possibilities );
		$color = "#";
		for($i = 1; $i <= 6; $i ++) {
			$color .= $possibilities [rand( 0, 12 )];
		}
		return $color;
	}

	public final function plot_report($aggregate_month, $account_mode, $_startdate, $_enddate, $startyear, $endyear, $account, $accounts_yes, $accounts_no) {
		$perMonthReport = $aggregate_month == '1' ? false : true;
		$accountBasedGraph = $account_mode == '1' ? false : true;
		$encoding = Configuration::getInstance()->getProperty( 'encoding' );

		if ($perMonthReport) {
			$startdate = \DateTime::createFromFormat( 'm/Y-d H:i:s', $_startdate . '-01 00:00:00' );
			$enddate = \DateTime::createFromFormat( 'm/Y-d H:i:s', $_enddate . '-01 00:00:00' );
			$enddate->modify( 'last day of this month' );
		} else {
			// years
			$startdate = \DateTime::createFromFormat( 'Y-m-d H:i:s', $startyear . '-01-01 00:00:00' );
			$enddate = \DateTime::createFromFormat( 'Y-m-d H:i:s', $endyear . '-12-31 00:00:00' );
		}

		if (!$accountBasedGraph) {
			// time based - only 1 account
			$accounts_yes = array (
					$account
			);
		} else {
			// multiple accounts
			if (! is_array( $accounts_yes ))
				$accounts_yes = array ();
		}

		if (! is_array( $accounts_no ))
			$accounts_no = array ();

		$report = array ();

		if ($perMonthReport) {
			$report = ReportControllerHandler::getInstance()->showMonthlyReportGraph( $accounts_yes, $accounts_no, $startdate, $enddate );
		} else {
			$report = ReportControllerHandler::getInstance()->showYearlyReportGraph( $accounts_yes, $accounts_no, $startdate, $enddate );
		}

		if (is_array( $report ) && array_key_exists( 'postingAccounts', $report ) && count( $report ['data'] ) > 0) {
			$all_data = $report ['data'];

			$chartLabels = array ();
			$chartData = array ();
			$chartColors = array ();
			$chartTitle = '';

			if ($startdate->format( 'Y' ) == $enddate->format( 'Y' )) {
				$sameYear = true;
			} else {
				$sameYear = false;
			}
			if ($startdate->format( 'Y-m' ) == $enddate->format( 'Y-m' )) {
				$sameMonth = true;
			} else {
				$sameMonth = false;
			}

			if ($perMonthReport) {
				if ($sameMonth) {
					$chartTitle = sprintf( "%s %s", html_entity_decode( $this->coreText->get_domain_meaning( 'MONTHS', $startdate->format( 'n' ) ), ENT_COMPAT | ENT_HTML401, $encoding ), $startdate->format( 'Y' ) );
				} else {
					$chartTitle = sprintf( "%s %s%s%s %s", html_entity_decode( $this->coreText->get_domain_meaning( 'MONTHS', $startdate->format( 'n' ) ), ENT_COMPAT | ENT_HTML401, $encoding ), $startdate->format( 'Y' ), $this->coreText->get_text( 170 ), html_entity_decode( $this->coreText->get_domain_meaning( 'MONTHS', $enddate->format( 'n' ) ), ENT_COMPAT | ENT_HTML401, $encoding ), $enddate->format( 'Y' ) );
				}
			} else {
				if ($sameYear) {
					$chartTitle = sprintf( "%s", $startdate->format( 'Y' ) );
				} else {
					$chartTitle = sprintf( "%s%s%s", $startdate->format( 'Y' ), $this->coreText->get_text( 170 ), $enddate->format( 'Y' ) );
				}
			}

			if ($accountBasedGraph) {
				$summedData = array ();

				foreach ( $all_data as $data ) {
					if (array_key_exists( $data ['postingaccountid'], $summedData )) {
						$fixedData = $summedData [$data ['postingaccountid']];
					} else {
						$fixedData = array (
								'label' => $data ['postingaccountname'],
								'amount' => 0
						);
					}
					$fixedData ['amount'] += $data ['amount'] * - 1;

					$summedData [$data ['postingaccountid']] = $fixedData;
				}

				usort( $summedData, function ($a, $b) {
					return $a ['amount'] < $b ['amount'];
				} );

				foreach ( $summedData as $data ) {
					$chartLabels [] = $data ['label'];
					$chartData [] = round( $data ['amount'], 2 );
					$chartColors [] = $this->randomColor();
				}
			} else {
				foreach ( $all_data as $data ) {
					$date = new \DateTime( $data ['date_ts'] );
					if ($perMonthReport) {
						$key = html_entity_decode( $this->coreText->get_domain_meaning( 'MONTHS', $date->format( 'n' ) ), ENT_COMPAT | ENT_HTML401, $encoding );
						if (!$sameYear) {
							$key .= " '" . $date->format( 'y' );
						}
					} else {
						$key = $date->format( 'Y' );
					}
					$chartLabels [] = $key;
					$chartData [] = $data ['amount'] * - 1;
					$chartColors [] = $this->randomColor();
				}

				$chartTitle = sprintf( "%s - %s", $all_data [0] ['postingaccountname'], $chartTitle );
			}

			$result = array (
					"chartLabels" => $chartLabels,
					"chartData" => $chartData,
					"chartColors" => $chartColors,
					"chartTitle" => $chartTitle
			);

			return $this->handleReturnForAjax( $result );
		}

		$this->add_error( 261 );
		return $this->handleReturnForAjax( false );
	}
}

?>
