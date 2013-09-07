<?php
use rest\client\CallServer;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\model\enum\ErrorCode;
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
// $Id: moduleMoneyFlows.php,v 1.63 2013/09/07 16:42:36 olivleh1 Exp $
//
require_once 'module/module.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreSettings.php';

class moduleMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerMapper', ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowMapper', ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_ARRAY_TYPE );

		// TODO: old shit
		$this->coreCurrencies = new coreCurrencies();
		$this->coreSettings = new coreSettings();
	}

	// TODO - duplicate code
	// filter only the capitalsources which are owned by the user or allowed for group use.
	private function filterCapitalsource($capitalsourceArray) {
		if (is_array( $capitalsourceArray )) {
			foreach ( $capitalsourceArray as $capitalsource ) {
				if ($capitalsource ['att_group_use'] == 1 || $capitalsource ['mur_userid'] == USERID)
					$capitalsource_values [] = $capitalsource;
			}
		}
		return $capitalsource_values;
	}

	function display_edit_moneyflow($realaction, $id, $all_data) {
		if (empty( $id ))
			return;

		$orig_amount = $all_data ['amount'];

		switch ($realaction) {
			case 'save' :
				$valid_data = true;
				$all_data ['moneyflowid'] = $id;

				if (! fix_amount( $all_data ['amount'] )) {
					$all_data [$id] ['amount_error'] = 1;
					$data_is_valid = false;
				}

				$moneyflow = parent::map( $all_data, ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );

				if ($moneyflow->getBookingDate() === NULL) {
					add_error( 130, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['bookingdate_error'] = 1;
					$valid_data = false;
					$origMoneyflow = CallServer::getInstance()->getMoneyflowById( $id );
					if ($origMoneyflow) {
						$temp_data = parent::map( $origMoneyflow );
						$checkdate = $temp_data ['bookingdate'];
					}
				}
				if ($moneyflow->getInvoiceDate() === NULL) {
					add_error( 129, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['invoicedate_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					$ret = CallServer::getInstance()->updateMoneyflow( $moneyflow );
					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						foreach ( $ret->getValidationResultItems() as $validationResult ) {
							$error = $validationResult->getError();

							switch ($error) {
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									add_error( $error, array (
											$orig_amount
									) );
									break;
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
									add_error( $error, array (
											GUI_DATE_FORMAT
									) );
									break;
								default :
									add_error( $error );
							}

							switch ($error) {
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
									$all_data ['bookingdate_error'] = 1;
									break;
								case ErrorCode::CAPITALSOURCE_USE_OUT_OF_VALIDITY :
									$all_data ['bookingdate_error'] = 1;
								case ErrorCode::CAPITALSOURCE_DOES_NOT_EXIST :
								case ErrorCode::CAPITALSOURCE_IS_NOT_SET :
								case ErrorCode::CAPITALSOURCE_USE_OUT_OF_VALIDITY :
									$all_data ['capitalsource_error'] = 1;
									break;
								case ErrorCode::CONTRACTPARTNER_DOES_NOT_EXIST :
								case ErrorCode::CONTRACTPARTNER_IS_NOT_SET :
									$all_data ['contractpartner_error'] = 1;
									break;
								case ErrorCode::AMOUNT_IS_ZERO :
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									$all_data ['amount_error'] = 1;
									break;
							}
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					$moneyflow = CallServer::getInstance()->getMoneyflowById( $id );
					if ($moneyflow) {
						$all_data = parent::map( $moneyflow );
						$capitalsource_data = parent::map( $moneyflow->getCapitalsource() );
					}
				}

				if (! $checkdate) {
					$checkdate = $all_data ['bookingdate'];
				}

				if (! $capitalsource_data) {
					$capitalsource_data = CallServer::getInstance()->getCapitalsourceById( $all_data ['mcs_capitalsourceid'] );
				}

				if ($capitalsource_data && strtotime( $checkdate ) >= strtotime( $capitalsource_data ['validfrom'] ) && strtotime( $checkdate ) <= strtotime( $capitalsource_data ['validtil'] )) {
					$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( convert_date_to_timestamp( $checkdate ), convert_date_to_timestamp( $checkdate ) );
				} else {
					$capitalsourceArray = CallServer::getInstance()->getAllCapitalsources();
				}

				$capitalsource_values = $this->filterCapitalsource( $capitalsourceArray );
				$contractpartner_values = CallServer::getInstance()->getAllContractpartner();
				$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
				$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'MONEYFLOWID', $id );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}

	function display_add_moneyflow($realaction, $all_data) {
		$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( time(), time() );
		$capitalsource_values = $this->filterCapitalsource( $capitalsourceArray );

		$contractpartner_values = CallServer::getInstance()->getAllContractpartner();

		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				$nothing_checked = true;
				foreach ( $all_data as $id => $value ) {
					if ($value ['checked'] == 1) {

						if (! fix_amount( $value ['amount'] )) {
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}
						$value ['moneyflowid'] = $id;
						$moneyflows [$id] = parent::map( $value, ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );

						$nothing_checked = false;
						if (! empty( $value ['invoicedate'] )) {
							if ($moneyflows [$id]->getInvoiceDate() === NULL) {
								add_error( 129, array (
										GUI_DATE_FORMAT
								) );
								$all_data [$id] ['invoicedate_error'] = 1;
							}
						}

						if ($moneyflows [$id]->getBookingDate() === NULL) {
							add_error( 130, array (
									GUI_DATE_FORMAT
							) );
							$all_data [$id] ['bookingdate_error'] = 1;
							$data_is_valid = false;
						}
					}
				}

				if ($nothing_checked) {
					add_error( 133 );
					$data_is_valid = false;
				}

				if ($data_is_valid) {

					$ret = CallServer::getInstance()->createMoneyflows( $moneyflows );

					if ($ret != true) {
						$data_is_valid = false;
						foreach ( $ret->getValidationResultItems() as $validationResult ) {
							$error = $validationResult->getError();
							$key = $validationResult->getKey();

							switch ($error) {
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									add_error( $error, array (
											$all_data [$key] ['amount']
									) );
									break;
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
									add_error( $error, array (
											GUI_DATE_FORMAT
									) );
									break;
								default :
									add_error( $error );
							}

							switch ($error) {
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
									$all_data [$key] ['bookingdate_error'] = 1;
									break;
								case ErrorCode::CAPITALSOURCE_USE_OUT_OF_VALIDITY :
									$all_data [$key] ['bookingdate_error'] = 1;
								case ErrorCode::CAPITALSOURCE_DOES_NOT_EXIST :
								case ErrorCode::CAPITALSOURCE_IS_NOT_SET :
								case ErrorCode::CAPITALSOURCE_USE_OUT_OF_VALIDITY :
									$all_data [$key] ['capitalsource_error'] = 1;
									break;
								case ErrorCode::CONTRACTPARTNER_DOES_NOT_EXIST :
								case ErrorCode::CONTRACTPARTNER_IS_NOT_SET :
									$all_data [$key] ['contractpartner_error'] = 1;
									break;
								case ErrorCode::AMOUNT_IS_ZERO :
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									$all_data [$key] ['amount_error'] = 1;
									break;
							}
						}
					}
				}

				if (! $data_is_valid)
					break;
			default :
				// clean the array before filling it.
				$all_data = array ();
				$date = convert_date_to_gui( date( 'Y-m-d' ) );

				$numflows = $this->coreSettings->get_num_free_moneyflows( USERID );

				$preDefMoneyflowsArray = CallServer::getInstance()->getAllPreDefMoneyflows();
				if (is_array( $preDefMoneyflowsArray )) {
					$all_data_pre = parent::mapArray( $preDefMoneyflowsArray );
				}

				for($i = $numflows; $i > 0; $i --) {
					$all_data [$numflows - $i] = array (
							'predefmoneyflowid' => ($numflows - $i + 1) * - 1,
							'bookingdate' => $date
					);
				}

				if (is_array( $all_data_pre )) {
					$i = $numflows;
					foreach ( $all_data_pre as $key => $value ) {
						$last_used = NULL;
						if (array_key_exists( 'last_used', $value ) && $value ['once_a_month'])
							$last_used = convert_date_to_timestamp( $value ['last_used'] );

						if (empty( $last_used ) || date( 'Y-m' ) != date( 'Y-m', $last_used )) {
							$all_data [$i] = $value;
							$all_data [$i] ['bookingdate'] = $date;
							$all_data [$i] ['amount'] = sprintf( '%.02f', $all_data_pre [$key] ['amount'] );
							$i ++;
						}
					}
				}
				break;
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
					$all_data = parent::map( $moneyflow, ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
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
