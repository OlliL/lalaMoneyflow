<?php

//
// Copyright (c) 2005-2016 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleMoneyFlows.php,v 1.96 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\MoneyflowControllerHandler;
use client\util\Environment;
use client\handler\MoneyflowReceiptControllerHandler;

class moduleMoneyFlows extends module {

	private $moduleContractPartners;

	public final function __construct() {
		parent::__construct();
		$this->moduleContractPartners = new moduleContractPartners();
	}

	public final function show_moneyflow_receipt($id) {
		if (empty( $id ))
			return;

		$receipt = MoneyflowReceiptControllerHandler::getInstance()->showMoneyflowReceipt( $id );

		switch ($receipt ['receipt_type']) {
			case 1 :
				header( 'Content-Type: image/jpeg' );
				break;
			case 2 :
				header( 'Content-Type: application/pdf' );
				break;
		}

		echo base64_decode( $receipt ['receipt'] );
	}

	public final function display_edit_moneyflow($realaction, $id, $all_data, $moneyflow_split_entries) {
		$close = 0;
		if (empty( $id ))
			return;

		$orig_amount = $all_data ['amount'];
		$delete_moneyflowsplitentryids = array ();
		$update_moneyflowsplitentrys = array ();
		$insert_moneyflowsplitentrys = array ();

		switch ($realaction) {
			case 'save' :
				$valid_data = true;
				$all_data ['moneyflowid'] = $id;

				if (! $this->fix_amount( $all_data ['amount'] )) {
					$all_data ['amount_error'] = 1;
					$valid_data = false;
				}

				if (! $this->dateIsValid( $all_data ['bookingdate'] )) {
					$this->add_error( ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT, array (
							Environment::getInstance()->getSettingDateFormat()
					) );
					$all_data ['bookingdate_error'] = 1;
					$valid_data = false;
				}
				if (! $this->dateIsValid( $all_data ['invoicedate'] )) {
					$this->add_error( ErrorCode::INVOICEDATE_IN_WRONG_FORMAT, array (
							Environment::getInstance()->getSettingDateFormat()
					) );
					$all_data ['invoicedate_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					if (count( $moneyflow_split_entries ) > 0) {
						$sum_amount = $all_data ['amount'];
						foreach ( $moneyflow_split_entries as $key => $value ) {
							if (array_key_exists( 'delete', $value ) && $value ['delete'] == '1') {
								if ($value ['moneyflowsplitentryid'] > 0) {
									$delete_moneyflowsplitentryids [] = $value ['moneyflowsplitentryid'];
								}
							} else {
								if ($value ['moneyflowsplitentryid'] > 0 || $value ['amount'] != '') {
									if (! $this->fix_amount( $value ['amount'] )) {
										$moneyflow_split_entries [$key] ['amount_error'] = 1;
										$valid_data = false;
									} else {
										$sum_amount = number_format( $sum_amount, 2 ) - number_format( $value ['amount'], 2 );
									}
								}

								if ($value ['moneyflowsplitentryid'] > 0) {
									$value ['moneyflowid'] = $all_data ['moneyflowid'];
									$update_moneyflowsplitentrys [] = $value;
								} elseif ($value ['amount'] != '') {
									$value ['moneyflowid'] = $all_data ['moneyflowid'];
									$insert_moneyflowsplitentrys [] = $value;
								}
							}
						}

						if ($sum_amount != 0 && (count( $insert_moneyflowsplitentrys ) > 0 || count( $update_moneyflowsplitentrys ) > 0)) {
							$this->add_error( ErrorCode::SPLIT_ENTRIES_AMOUNT_IS_NOT_EQUALS_MONEYFLOW_AMOUNT );
							$valid_data = false;
						}
					}
				}

				if ($valid_data === true) {

					$ret = MoneyflowControllerHandler::getInstance()->updateMoneyflow( $all_data, $delete_moneyflowsplitentryids, $update_moneyflowsplitentrys, $insert_moneyflowsplitentrys );
					if ($ret === true) {
						$close = 1;
						break;
						$this->template_assign( 'CLOSE', 1 );
					} else {
						$capitalsource_values = $ret ['capitalsources'];
						$contractpartner_values = $ret ['contractpartner'];
						$postingaccount_values = $ret ['postingaccounts'];
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							switch ($error) {
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									$this->add_error( $error, array (
											$orig_amount
									) );
									break;
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
									$this->add_error( $error, array (
											Environment::getInstance()->getSettingDateFormat()
									) );
									break;
								default :
									$this->add_error( $error );
							}

							switch ($error) {
								case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
								case ErrorCode::BOOKINGDATE_OUTSIDE_GROUP_ASSIGNMENT :
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
								case ErrorCode::CONTRACTPARTNER_NO_LONGER_VALID :
									$all_data ['contractpartner_error'] = 1;
									$all_data ['bookingdate_error'] = 1;
									break;
							}
						}
					}
					break;
				}
			default :
				$showEditMoneyflow = MoneyflowControllerHandler::getInstance()->showEditMoneyflow( $id );
				$all_data_pre = $showEditMoneyflow ['moneyflow'];
				$moneyflow_split_entries_pre = $showEditMoneyflow ['moneyflow_split_entries'];
				$capitalsource_values = $showEditMoneyflow ['capitalsources'];
				$contractpartner_values = $showEditMoneyflow ['contractpartner'];
				$postingaccount_values = $showEditMoneyflow ['postingaccounts'];
				if ($realaction != "save") {
					$all_data = $all_data_pre;
					$moneyflow_split_entries = $moneyflow_split_entries_pre;
				}

				break;
		}

		$i = count( $moneyflow_split_entries );
		while ( $i < 10 ) {
			$moneyflow_split_entries [$i] = array (
					'moneyflowsplitentryid' => $i * - 1
			);
			$i ++;
		}

		$this->template_assign( 'CLOSE', $close );
		if ($close === 0) {
			$this->template_assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
			$this->template_assign( 'CONTRACTPARTNER_VALUES', $this->sort_contractpartner( $contractpartner_values ) );
			$this->template_assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
			$this->template_assign( 'ALL_DATA', $all_data );
			$this->template_assign( 'MONEYFLOW_SPLIT_ENTRIES', $moneyflow_split_entries );
			$this->template_assign( 'MONEYFLOWID', $id );
			$this->template_assign( 'ERRORS', $this->get_errors() );
		}

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}

	public final function display_add_moneyflow($realaction, $all_data) {
		switch ($realaction) {
			case 'save' :
				$add_data = $all_data;
				$add_data ['moneyflowid'] = - 1;

				$createMoneyflows = MoneyflowControllerHandler::getInstance()->createMoneyflow( $add_data );
				$capitalsource_values = $createMoneyflows ['capitalsources'];
				$contractpartner_values = $createMoneyflows ['contractpartner'];
				$postingaccount_values = $createMoneyflows ['postingaccounts'];
				$preDefMoneyflows = $createMoneyflows ['predefmoneyflows'];

				$result = $createMoneyflows ['result'];
				if ($result === true) {
					$data_is_valid = true;
				} else {
					$data_is_valid = false;
					foreach ( $createMoneyflows ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						switch ($error) {
							case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
								$this->add_error( $error, array (
										$all_data ['amount']
								) );
								break;
							case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
								$this->add_error( $error, array (
										Environment::getInstance()->getSettingDateFormat()
								) );
								break;
							default :
								$this->add_error( $error );
						}

						switch ($error) {
							case ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT :
							case ErrorCode::BOOKINGDATE_OUTSIDE_GROUP_ASSIGNMENT :
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
							case ErrorCode::CONTRACTPARTNER_NO_LONGER_VALID :
								$all_data ['contractpartner_error'] = 1;
								$all_data ['bookingdate_error'] = 1;
						}
					}
				}
			default :
				if ($realaction === 'save' && $data_is_valid == true || $realaction != 'save') {

					if ($realaction !== 'save') {
						$addMoneyflow = MoneyflowControllerHandler::getInstance()->showAddMoneyflows();

						$capitalsource_values = $addMoneyflow ['capitalsources'];
						$contractpartner_values = $addMoneyflow ['contractpartner'];
						$postingaccount_values = $addMoneyflow ['postingaccounts'];
						$preDefMoneyflows = $addMoneyflow ['predefmoneyflows'];
					}

					// clean the array before filling it.
					$date = $this->convertDateToGui( date( 'Y-m-d' ) );

					$all_data = array (
							'predefmoneyflowid' => - 1,
							'bookingdate' => $date
					);
				}
				break;
		}

		$this->template_assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template_assign( 'CONTRACTPARTNER_VALUES', $this->sort_contractpartner( $contractpartner_values ) );
		$this->template_assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
		$this->template_assign( 'ERRORS', $this->get_errors() );
		$this->template_assign( 'TODAY', $this->convertDateToGui( date( 'Y-m-d' ) ) );

		$this->template_assign_raw( 'JSON_PREDEFMONEYFLOWS', json_encode( $preDefMoneyflows ) );
		$this->template_assign_raw( 'JSON_CONTRACTPARTNER', json_encode( $this->sort_contractpartner( $contractpartner_values ) ) );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );


		$embeddedEditContractpartner = $this->moduleContractPartners->display_edit_contractpartner(null,true);
		$this->parse_header( 0, 1, 'display_add_moneyflow_bs.tpl', array("EMBEDDED_ADD_CONTRACTPARTNER" => $embeddedEditContractpartner) );
		return $this->fetch_template( 'display_add_moneyflow_bs.tpl' );
	}

	public final function display_delete_moneyflow($realaction, $id) {
		switch ($realaction) {
			case 'yes' :
				if (MoneyflowControllerHandler::getInstance()->deleteMoneyflowById( $id )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				$all_data = MoneyflowControllerHandler::getInstance()->showDeleteMoneyflow( $id );
				if ($all_data) {
					$this->template_assign( 'ALL_DATA', $all_data );
				}
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_moneyflow.tpl' );
	}
}
?>
