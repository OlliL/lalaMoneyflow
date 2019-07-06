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
// $Id: moduleMonthlySettlement.php,v 1.66 2016/12/24 12:09:27 olivleh1 Exp $
//
namespace client\module;

use client\handler\MonthlySettlementControllerHandler;
use client\core\coreText;

class moduleMonthlySettlement extends module {
	private $coreText;

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText();
	}

	public final function display_list_monthlysettlements($month, $year) {
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
		$count_all_data = 0;
		$months = array();

		if (is_array( $allMonth )) {
			foreach ( $allMonth as $key => $value ) {
				$temp_array = array (
						'nummeric' => sprintf( '%02d', $value ),
						'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $value )
				);
				$months [] = $temp_array;
				if (( int ) $month === ( int ) $value) {
					$monthArray = $temp_array;
				}
			}

			if ($month > 0 && $year > 0 && is_array( $all_data ) && count( $all_data ) > 0) {
				$sumamount = 0;
				$credit_sumamount = 0;
				foreach ( $all_data as $settlement ) {
					// CREDIT capitalsources will be summed up separately
					if($settlement['capitalsourcetype'] != 5) {
						$sumamount += $settlement ['amount'];
					} else {
						$credit_sumamount += $settlement ['amount'];
					}
				}

				$this->template_assign( 'SUMAMOUNT', $sumamount );
				$this->template_assign( 'CREDIT_SUMAMOUNT', $credit_sumamount );
				$this->template_assign( 'MONTH', $monthArray );
				$this->template_assign( 'YEAR', $year );
				$this->template_assign( 'ALL_DATA', $all_data );
				$count_all_data = count( $all_data );
			}
		}
		$this->template_assign( 'ALL_YEARS', $allYears );
		$this->template_assign( 'ALL_MONTHS', $months );
		$this->template_assign( 'SELECTED_MONTH', $month );
		$this->template_assign( 'SELECTED_YEAR', $year );
		$this->template_assign( 'COUNT_ALL_DATA', $count_all_data );
		$this->template_assign( 'NUM_EDITABLE_SETTLEMENTS', $numberOfEditableSettlements );
		$this->template_assign( 'NUM_ADDABLE_SETTLEMENTS', $numberOfAddableSettlements );

		$this->parse_header_without_embedded(0, 'display_list_monthlysettlements_bs.tpl');
		return $this->fetch_template( 'display_list_monthlysettlements_bs.tpl' );
	}

	public final function display_edit_monthlysettlement($realaction, $month, $year, $all_data) {
		$close = 0;
		$new = 0;
		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;

				foreach ( $all_data as $id => $value ) {
					if (is_array( $value )) {
						if (! $this->fix_amount( $all_data [$id] ['amount'] )) {
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}

				if ($data_is_valid === true) {
					$ret = MonthlySettlementControllerHandler::getInstance()->upsertMonthlySettlement( $all_data );
					if ($ret === true) {
						$close = 1;
					} else {
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							$this->add_error( $error );
						}
					}
				}

				$all_data_new = $all_data;
				break;

			default :
				$monthlySettlementCreate = MonthlySettlementControllerHandler::getInstance()->showMonthlySettlementCreate( $year, $month );
				$year = $monthlySettlementCreate ['year'];
				$month = $monthlySettlementCreate ['month'];
				$all_data_new = $monthlySettlementCreate ['monthly_settlements'];
				$all_data_imported = $monthlySettlementCreate ['monthly_settlements_imported'];
				foreach ( $all_data_imported as $imported ) {
					$imported ['imported'] = 1;
					$all_data_new [] = $imported;
				}

				if ($monthlySettlementCreate ['edit_mode'] == 0) {
					$new = 1;
				}
				if(is_array($all_data_new) && count($all_data_new) > 0) {
				foreach ( $all_data_new as $key => $data ) {
					$all_data_new [$key] ['amount'] = sprintf( '%.02f', $data ['amount'] );
					$sort [$key] = sprintf( "%d%20d", $data ['mur_userid'], $data ['mcs_capitalsourceid'] );
				}
				array_multisort( $sort, SORT_ASC, $all_data_new );
				}
				break;
		}

		$monthArray = array (
				'nummeric' => sprintf( '%02d', $month ),
				'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $month )
		);

		$this->template_assign( 'CLOSE', $close );
		$this->template_assign( 'NEW', $new );
		$this->template_assign( 'MONTH', $monthArray );
		$this->template_assign( 'YEAR', $year );
		$this->template_assign( 'ALL_DATA', $all_data_new );
		$this->template_assign( 'ALL_DATA', $all_data_new );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data_new ) );
		$this->template_assign( 'ERRORS', $this->get_errors() );
		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_monthlysettlement.tpl' );
	}

	public final function display_delete_monthlysettlement($realaction, $month, $year) {
		if ($month > 0 && $year > 0) {
			switch ($realaction) {
				case 'yes' :
					if (MonthlySettlementControllerHandler::getInstance()->deleteMonthlySettlement( $year, $month )) {
						$this->template_assign( 'CLOSE', 1 );
						break;
					}
				default :

					$showMonthlySettlementDelete = MonthlySettlementControllerHandler::getInstance()->showMonthlySettlementDelete( $year, $month );
					$all_data = $showMonthlySettlementDelete ['monthly_settlements'];
					$sumamount = 0;
					if (is_array( $all_data )) {
						foreach ( $all_data as $settlement ) {
							$sumamount += $settlement ['amount'];
						}
					}

					$monthArray = array (
							'nummeric' => sprintf( '%02d', $month ),
							'name' => $this->coreText->get_domain_meaning( 'MONTHS', ( int ) $month )
					);
					$this->template_assign( 'SUMAMOUNT', $sumamount );
					$this->template_assign( 'MONTH', $monthArray );
					$this->template_assign( 'YEAR', $year );
					$this->template_assign( 'ALL_DATA', $all_data );
					break;
			}
		}
		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_monthlysettlement.tpl' );
	}
}
?>
