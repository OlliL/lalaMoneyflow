<?php
use rest\client\CallServer;
use rest\client\mapper\ClientArrayMapperEnum;
//
// Copyright (c) 2005-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleMoneyFlows.php,v 1.56 2013/08/24 00:10:28 olivleh1 Exp $
//
require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/corePreDefMoneyFlows.php';
require_once 'core/coreSettings.php';

class moduleMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', rest\client\mapper\ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerMapper', rest\client\mapper\ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowMapper', rest\client\mapper\ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );

		// TODO: old shit
		$this->coreCapitalSources = new coreCapitalSources();
		$this->coreContractPartners = new coreContractPartners();
		$this->coreCurrencies = new coreCurrencies();
		$this->corePreDefMoneyFlows = new corePreDefMoneyFlows();
		$this->coreSettings = new coreSettings();
	}

	// filter only the capitalsources which are owned by the user or allowed for group use.
	private function filterCapitalsource($capitalsourceArray) {
		if (is_array( $capitalsourceArray )) {
			$temp_capitalsource_values = parent::mapArray( $capitalsourceArray );
			foreach ( $temp_capitalsource_values as $capitalsource ) {
				if ($capitalsource ['att_group_use'] == 1 || $capitalsource ['mur_userid'] == USERID)
					$capitalsource_values [] = $capitalsource;
			}
		}
		return $capitalsource_values;
	}

	function display_edit_moneyflow($realaction, $id, $all_data) {
		if (empty( $id ))
			return;

		$bookingdate_orig = $all_data ['bookingdate'];
		$invoicedate_orig = $all_data ['invoicedate'];

		$checkdate = $all_data ['bookingdate'];

		switch ($realaction) {
			case 'save' :
				$all_data ['bookingdate'] = convert_date_to_db( $all_data ['bookingdate'], GUI_DATE_FORMAT );
				$all_data ['invoicedate'] = convert_date_to_db( $all_data ['invoicedate'], GUI_DATE_FORMAT );
				$valid_data = true;

				if ($all_data ['bookingdate'] === false) {
					add_error( 147, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['bookingdate'] = $bookingdate_orig;
					$all_data ['bookingdate_error'] = 1;
					$valid_data = false;
					$moneyflow = CallServer::getInstance()->getMoneyflowById( $id );
					if ($moneyflow) {
						$temp_data = parent::map( $moneyflow );
						$checkdate = $temp_data ['bookingdate'];
					}
				}
				if ($all_data ['invoicedate'] === false) {
					add_error( 147, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['invoicedate'] = $invoicedate_orig;
					$all_data ['invoicedate_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					$all_data ['moneyflowid'] = $id;
					$moneyflow = parent::map( $all_data, rest\client\mapper\ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
					$ret = CallServer::getInstance()->updateMoneyflow( $moneyflow );
					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					}
				}
			default :
				if (! is_array( $all_data )) {
					$moneyflow = CallServer::getInstance()->getMoneyflowById( $id );
					if ($moneyflow) {
						$all_data = parent::map( $moneyflow );
						$capitalsource_data = parent::map( $moneyflow->getCapitalsource() );
					}
					if (! $checkdate) {
						$checkdate = $all_data ['bookingdate'];
					}
				}

				if (! $capitalsource_data) {
					$capitalsource = CallServer::getInstance()->getCapitalsourceById( $all_data ['mcs_capitalsourceid'] );
					if ($capitalsource) {
						$capitalsource_data = parent::map( $capitalsource );
					}
				}

				if ($capitalsource_data && strtotime( $checkdate ) >= strtotime( $capitalsource_data ['validfrom'] ) && strtotime( $checkdate ) <= strtotime( $capitalsource_data ['validtil'] )) {
					$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( $checkdate, $checkdate );
				} else {
					$capitalsourceArray = CallServer::getInstance()->getAllCapitalsources();
				}

				$capitalsource_values = $this->filterCapitalsource( $capitalsourceArray );

				$contractpartnerArray = CallServer::getInstance()->getAllContractpartner();
				if (is_array( $contractpartnerArray )) {
					$contractpartner_values = parent::mapArray( $contractpartnerArray );
				}

				$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				break;
		}

		if (empty( $all_data ['bookingdate_error'] ))
			$all_data ['bookingdate'] = convert_date_to_gui( $all_data ['bookingdate'], GUI_DATE_FORMAT );
		if (empty( $all_data ['invoicedate_error'] ))
			$all_data ['invoicedate'] = convert_date_to_gui( $all_data ['invoicedate'], GUI_DATE_FORMAT );

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'MONEYFLOWID', $id );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}

	function display_add_moneyflow($realaction, $all_data) {
		$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( date( 'Y-m-d' ), date( 'Y-m-d' ) );
		$capitalsource_values = $this->filterCapitalsource( $capitalsourceArray );

		$contractpartnerArray = CallServer::getInstance()->getAllContractpartner();
		if (is_array( $contractpartnerArray )) {
			$contractpartner_values = parent::mapArray( $contractpartnerArray );
		}

		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				$nothing_checked = true;
				foreach ( $all_data as $id => $value ) {
					if ($value ['checked'] == 1) {
						$bookingdate_orig = $value ['bookingdate'];
						$invoicedate_orig = $value ['invoicedate'];

						$nothing_checked = false;
						$all_data [$id] ['bookingdate'] = convert_date_to_db( $value ['bookingdate'], GUI_DATE_FORMAT );

						if (empty( $value ['mcs_capitalsourceid'] )) {
							add_error( 127 );
							$data_is_valid = false;
						}

						if (empty( $value ['mcp_contractpartnerid'] )) {
							add_error( 128 );
							$data_is_valid = false;
						}

						if (! empty( $value ['invoicedate'] )) {
							$all_data [$id] ['invoicedate'] = convert_date_to_db( $value ['invoicedate'], GUI_DATE_FORMAT );
							if ($all_data [$id] ['invoicedate'] === false) {
								add_error( 129, array (
										GUI_DATE_FORMAT
								) );
								$all_data [$id] ['invoicedate'] = $invoicedate_orig;
								$all_data [$id] ['invoicedate_error'] = 1;
							}
						}

						if ($all_data [$id] ['bookingdate'] === false) {
							add_error( 130, array (
									GUI_DATE_FORMAT
							) );
							$all_data [$id] ['bookingdate'] = $bookingdate_orig;
							$all_data [$id] ['bookingdate_error'] = 1;
							$data_is_valid = false;
						}

						if (! $this->coreCapitalSources->id_is_valid( $value ['mcs_capitalsourceid'], $all_data [$id] ['bookingdate'] )) {
							add_error( 181 );
							$all_data [$id] ['capitalsource_error'] = 1;
							$data_is_valid = false;
						}

						if (empty( $value ['comment'] )) {
							add_error( 131 );
							$data_is_valid = false;
						}

						if (preg_match( '/^[-0\.,]{1,}$/', $value ['amount'] )) {
							add_error( 200 );
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}

						if (! fix_amount( $all_data [$id] ['amount'] )) {
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}
					}
				}

				if ($nothing_checked) {
					add_error( 133 );
					$data_is_valid = false;
				}
				if ($data_is_valid) {
					foreach ( $all_data as $id => $value ) {
						if ($value ['checked'] == 1) {
							if (empty( $value ['invoicedate'] ))
								$value ['invoicedate'] = $value ['bookingdate'];
							$moneyflow = parent::map( $value, rest\client\mapper\ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
							CallServer::getInstance()->createMoneyflow( $moneyflow );
							if ($value ['predefmoneyflowid'] >= 0)
								$this->corePreDefMoneyFlows->set_last_used( $value ['predefmoneyflowid'], $value ['bookingdate'] );
						}
					}
				} else {
					break;
				}
			default :
				// clean the array before filling it.
				$all_data = array ();
				$date = convert_date_to_db( date( 'Y-m-d' ), GUI_DATE_FORMAT );
				$all_data_pre = $this->corePreDefMoneyFlows->get_valid_data();
				$numflows = $this->coreSettings->get_num_free_moneyflows( USERID );

				for($i = $numflows; $i > 0; $i --) {
					$all_data [$numflows - $i] = array (
							'predefmoneyflowid' => ($numflows - $i + 1) * - 1,
							'bookingdate' => $date
					);
				}

				if (is_array( $all_data_pre )) {
					$i = $numflows;
					foreach ( $all_data_pre as $key => $value ) {
						$last_used = convert_date_to_timestamp( $value ['last_used'], GUI_DATE_FORMAT );

						if (empty( $last_used ) || ! $this->corePreDefMoneyFlows->is_once_a_month( $value ['predefmoneyflowid'] ) || date( 'Y-m' ) != date( 'Y-m', $last_used )) {
							$all_data [$i] = $value;
							$all_data [$i] ['bookingdate'] = $date;
							$all_data [$i] ['amount'] = sprintf( '%.02f', $all_data_pre [$key] ['amount'] );
							$all_data [$i] ['capitalsourcecomment'] = $this->coreCapitalSources->get_comment( $all_data_pre [$key] ['mcs_capitalsourceid'] );
							$all_data [$i] ['contractpartnername'] = $this->coreContractPartners->get_name( $all_data_pre [$key] ['mcp_contractpartnerid'] );
							$i ++;
						}
					}
				}

				break;
		}

		foreach ( $all_data as $key => $value ) {
			if (empty( $all_data [$key] ['bookingdate_error'] ))
				$all_data [$key] ['bookingdate'] = convert_date_to_gui( $all_data [$key] ['bookingdate'], GUI_DATE_FORMAT );
			if (empty( $all_data [$key] ['invoicedate_error'] ))
				$all_data [$key] ['invoicedate'] = convert_date_to_gui( $all_data [$key] ['invoicedate'], GUI_DATE_FORMAT );
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_add_moneyflow.tpl' );
	}

	function display_delete_moneyflow($realaction, $id) {
		switch ($realaction) {
			case 'yes' :
				if (CallServer::getInstance()->deleteMoneyflow( $id )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				$moneyflow = CallServer::getInstance()->getMoneyflowById( $id );
				if ($moneyflow) {
					$all_data = parent::map( $moneyflow, rest\client\mapper\ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_moneyflow.tpl' );
	}
}
?>
