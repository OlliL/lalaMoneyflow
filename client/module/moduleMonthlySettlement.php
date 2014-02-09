<?php
use rest\client\handler\CapitalsourceControllerHandler;
use rest\client\handler\MonthlySettlementControllerHandler;
use rest\base\ErrorCode;
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleMonthlySettlement.php,v 1.47 2014/02/09 14:19:03 olivleh1 Exp $
//

require_once 'module/module.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreDomains.php';
require_once 'core/coreMoneyFlows.php';

class moduleMonthlySettlement extends module {

	function moduleMonthlySettlement() {
		parent::__construct();
		$this->coreCurrencies = new coreCurrencies();
		$this->coreMoneyFlows = new coreMoneyFlows();
		$this->coreMonthlySettlement = new coreMonthlySettlement();
		$this->coreDomains = new coreDomains();
	}

	// TODO - duplicate code
	// filter only the capitalsources which are owned by the user or allowed for group use.
	private function filterCapitalsource($capitalsourceArray) {
		if (is_array( $capitalsourceArray )) {
			foreach ( $capitalsourceArray as $capitalsource ) {
				if ($capitalsource ['mur_userid'] == USERID)
					$capitalsource_values [] = $capitalsource;
			}
		}
		return $capitalsource_values;
	}

	function display_list_monthlysettlements($month, $year) {
		if (! $year)
			$year = date( 'Y' );

		$showMonthlySettlementList = MonthlySettlementControllerHandler::getInstance()->showMonthlySettlementList( $year, $month );
		$allYears = $showMonthlySettlementList ['allYears'];
		$allMonth = $showMonthlySettlementList ['allMonth'];
		$year = $showMonthlySettlementList ['year'];
		$month = $showMonthlySettlementList ['month'];
		$all_data = $showMonthlySettlementList ['monthly_settlements'];
		$numberOfEditableSettlements = $showMonthlySettlementList ['numberOfEditableSettlements'];
		$numberOfAddableSettlements = $showMonthlySettlementList ['numberOfAddableSettlements'];

		if (is_array( $allMonth )) {
			foreach ( $allMonth as $key => $value ) {
				$temp_array = array (
						'nummeric' => sprintf( '%02d', $value ),
						'name' => $this->coreDomains->get_domain_meaning( 'MONTHS', ( int ) $value )
				);
				$months [] = $temp_array;
				if (( int ) $month === ( int ) $value) {
					$monthArray = $temp_array;
				}
			}

			if ($month > 0 && $year > 0 && is_array( $all_data )) {
				foreach ( $all_data as $settlement ) {
					$sumamount += $settlement ['amount'];
				}

				$this->template->assign( 'SUMAMOUNT', $sumamount );
				$this->template->assign( 'MONTH', $monthArray );
				$this->template->assign( 'YEAR', $year );
				$this->template->assign( 'ALL_DATA', $all_data );
				$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
			}
		}
		$this->template->assign( 'ALL_YEARS', $allYears );
		$this->template->assign( 'ALL_MONTHS', $months );
		$this->template->assign( 'SELECTED_MONTH', $month );
		$this->template->assign( 'SELECTED_YEAR', $year );
		$this->template->assign( 'NUM_EDITABLE_SETTLEMENTS', $numberOfEditableSettlements );
		$this->template->assign( 'NUM_ADDABLE_SETTLEMENTS', $numberOfAddableSettlements );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );

		$this->parse_header();
		return $this->fetch_template( 'display_list_monthlysettlements.tpl' );
	}

	function display_edit_monthlysettlement($realaction, $month, $year, $all_data) {
		switch ($realaction) {
			case 'save' :
				$ret = true;
				$exists = $this->coreMonthlySettlement->monthlysettlement_exists( $month, $year );
				$data_is_valid = true;

				foreach ( $all_data as $id => $value ) {
					if (is_array( $value )) {
						if ($value ['new'] === "1") {
							$new = 2;
							if ($exists === true) {
								add_error( ErrorCode::MONTHLY_SETTLEMENT_ALREADY_EXISTS );
								$data_is_valid = false;
							}
						}
						if (! fix_amount( $value ['amount'] )) {
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}

				if ($data_is_valid === true) {
					foreach ( $all_data as $id => $value ) {
						if (is_array( $value )) {
							if ($value ['new'] === "1" || ! is_array( $this->coreMonthlySettlement->get_data( $value ['mcs_capitalsourceid'], $month, $year ) )) {
								if (! $this->coreMonthlySettlement->insert_monthlysettlement( $value ['mcs_capitalsourceid'], $month, $year, $value ['amount'] ))
									$ret = false;
							} else {
								if (! $this->coreMonthlySettlement->update_monthlysettlement( $value ['mcs_capitalsourceid'], $month, $year, $value ['amount'] ))
									$ret = false;
							}
						}
					}
				}

				if ($data_is_valid === true && $ret === true) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					$all_data_new = $all_data;
				}
				break;

			default :
				$monthlySettlementCreate = MonthlySettlementControllerHandler::getInstance()->showMonthlySettlementCreate( $year, $month );
				$year = $monthlySettlementCreate ['year'];
				$month = $monthlySettlementCreate ['month'];
				$all_data_new = $monthlySettlementCreate ['monthly_settlements'];
				if ($monthlySettlementCreate ['edit_mode'] == 0) {
					$this->template->assign( 'NEW', 1 );
				}
				break;
		}

		$monthArray = array (
				'nummeric' => sprintf( '%02d', $month ),
				'name' => $this->coreDomains->get_domain_meaning( 'MONTHS', ( int ) $month )
		);
		$this->template->assign( 'MONTH', $monthArray );
		$this->template->assign( 'YEAR', $year );
		$this->template->assign( 'ALL_DATA', $all_data_new );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data_new ) );
		$this->template->assign( 'ERRORS', $this->get_errors() );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_monthlysettlement.tpl' );
	}

	function display_delete_monthlysettlement($realaction, $month, $year) {
		if ($month > 0 && $year > 0) {
			switch ($realaction) {
				case 'yes' :
					if (MonthlySettlementControllerHandler::getInstance()->deleteMonthlySettlement( $year, $month )) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					}
				default :

					$showMonthlySettlementDelete = MonthlySettlementControllerHandler::getInstance()->showMonthlySettlementDelete( $year, $month );
					$all_data = $showMonthlySettlementDelete ['monthly_settlements'];
					if (is_array( $all_data )) {
						foreach ( $all_data as $settlement ) {
							$sumamount += $settlement ['amount'];
						}
					}

					$monthArray = array (
							'nummeric' => sprintf( '%02d', $month ),
							'name' => $this->coreDomains->get_domain_meaning( 'MONTHS', ( int ) $month )
					);
					$this->template->assign( 'SUMAMOUNT', $sumamount );
					$this->template->assign( 'MONTH', $monthArray );
					$this->template->assign( 'YEAR', $year );
					$this->template->assign( 'ALL_DATA', $all_data );
					break;
			}
		}
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_monthlysettlement.tpl' );
	}
}
?>
