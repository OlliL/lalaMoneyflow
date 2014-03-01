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
// $Id: moduleMoneyFlows.php,v 1.83 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\MoneyflowControllerHandler;

class moduleMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_edit_moneyflow($realaction, $id, $all_data) {
		if (empty( $id ))
			return;

		$orig_amount = $all_data ['amount'];

		switch ($realaction) {
			case 'save' :
				$valid_data = true;
				$all_data ['moneyflowid'] = $id;

				if (! fix_amount( $all_data ['amount'] )) {
					$all_data ['amount_error'] = 1;
					$valid_data = false;
				}

				if (! dateIsValid( $all_data ['bookingdate'] )) {
					add_error( ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['bookingdate_error'] = 1;
					$valid_data = false;
				}
				if (! dateIsValid( $all_data ['invoicedate'] )) {
					add_error( ErrorCode::INVOICEDATE_IN_WRONG_FORMAT, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['invoicedate_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					$ret = MoneyflowControllerHandler::getInstance()->updateMoneyflow( $all_data );
					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
					} else {
						$capitalsource_values = $ret ['capitalsources'];
						$contractpartner_values = $ret ['contractpartner'];
						$postingaccount_values = $ret ['postingaccounts'];
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

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
				$capitalsource_values = $showEditMoneyflow ['capitalsources'];
				$contractpartner_values = $showEditMoneyflow ['contractpartner'];
				$postingaccount_values = $showEditMoneyflow ['postingaccounts'];
				if ($realaction != "save") {
					$all_data = $all_data_pre;
				}

				break;
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'MONEYFLOWID', $id );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_moneyflow.tpl' );
	}

	public final function display_add_moneyflow($realaction, $all_data) {
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
						$nothing_checked = false;
						if (! empty( $value ['invoicedate'] )) {
							if (! dateIsValid( $value ['invoicedate'] )) {
								add_error( ErrorCode::INVOICEDATE_IN_WRONG_FORMAT, array (
										GUI_DATE_FORMAT
								) );
								$all_data [$id] ['invoicedate_error'] = 1;
							}
						}

						if (! dateIsValid( $value ['bookingdate'] )) {
							add_error( ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT, array (
									GUI_DATE_FORMAT
							) );
							$all_data [$id] ['bookingdate_error'] = 1;
							$data_is_valid = false;
						}
						$add_data [] = array_merge( $value, array (
								'moneyflowid' => $id
						) );
					}
				}

				if ($nothing_checked) {
					add_error( ErrorCode::NOTHING_MARKED_TO_ADD );
					$data_is_valid = false;
				}

				if ($data_is_valid) {

					$createMoneyflows = MoneyflowControllerHandler::getInstance()->createMoneyflows( $add_data );
					$capitalsource_values = $createMoneyflows ['capitalsources'];

					$contractpartner_values = $createMoneyflows ['contractpartner'];
					$postingaccount_values = $createMoneyflows ['postingaccounts'];

					$numflows = $createMoneyflows ['num_free_moneyflows'];

					$result = $createMoneyflows ['result'];
					if ($result === true) {
						$all_data_pre = $createMoneyflows ['predefmoneyflows'];
					} else {
						$data_is_valid = false;
						foreach ( $createMoneyflows ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];
							$key = $validationResult ['key'];

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
								case ErrorCode::CONTRACTPARTNER_NO_LONGER_VALID :
									$all_data [$key] ['contractpartner_error'] = 1;
									$all_data [$key] ['bookingdate_error'] = 1;
							}
						}
					}
				} else {
					$addMoneyflow = MoneyflowControllerHandler::getInstance()->showAddMoneyflows();
					$capitalsource_values = $addMoneyflow ['capitalsources'];
					$contractpartner_values = $addMoneyflow ['contractpartner'];
					$postingaccount_values = $addMoneyflow ['postingaccounts'];
				}
			default :
				if ($realaction === 'save' && $data_is_valid == true || $realaction != 'save') {

					if ($realaction !== 'save') {
						$addMoneyflow = MoneyflowControllerHandler::getInstance()->showAddMoneyflows();
						$capitalsource_values = $addMoneyflow ['capitalsources'];

						$contractpartner_values = $addMoneyflow ['contractpartner'];
						$postingaccount_values = $addMoneyflow ['postingaccounts'];
						$all_data_pre = $addMoneyflow ['predefmoneyflows'];
						$numflows = $addMoneyflow ['num_free_moneyflows'];
					}

					// clean the array before filling it.
					$all_data = array ();
					$date = convert_date_to_gui( date( 'Y-m-d' ) );

					for($i = $numflows; $i > 0; $i --) {
						$all_data [$numflows - $i] = array (
								'predefmoneyflowid' => ($numflows - $i + 1) * - 1,
								'bookingdate' => $date
						);
					}

					if (is_array( $all_data_pre )) {
						$i = $numflows;
						foreach ( $all_data_pre as $value ) {
							$all_data [$i] = $value;
							$all_data [$i] ['bookingdate'] = $date;
							$i ++;
						}
					}
				}
				break;
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_add_moneyflow.tpl' );
	}

	public final function display_delete_moneyflow($realaction, $id) {
		switch ($realaction) {
			case 'yes' :
				if (MoneyflowControllerHandler::getInstance()->deleteMoneyflow( $id )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				$all_data = MoneyflowControllerHandler::getInstance()->showDeleteMoneyflow( $id );
				if ($all_data) {
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_moneyflow.tpl' );
	}
}
?>
