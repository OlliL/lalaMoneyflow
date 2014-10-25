<?php
//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleImportedMoneyFlows.php,v 1.3 2014/10/25 15:17:58 olivleh1 Exp $
//
namespace client\module;

use client\handler\ImportedMoneyflowControllerHandler;

class moduleImportedMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_add_importedmoneyflow($realaction, $all_data) {
		$add_data = null;
		$delete_ids = null;

		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				$nothing_checked = true;
				foreach ( $all_data as $id => $value ) {
					if (array_key_exists( 'action', $value ) && ($value ['action'] == 1)) {

						if (! $this->fix_amount( $value ['amount'] )) {
							$all_data [$id] ['amount_error'] = 1;
							$data_is_valid = false;
						}
						$nothing_checked = false;
						if (! empty( $value ['invoicedate'] )) {
							if (! $this->dateIsValid( $value ['invoicedate'] )) {
								$this->add_error( ErrorCode::INVOICEDATE_IN_WRONG_FORMAT, array (
										Environment::getInstance()->getSettingDateFormat()
								) );
								$all_data [$id] ['invoicedate_error'] = 1;
							}
						}

						if (! $this->dateIsValid( $value ['bookingdate'] )) {
							$this->add_error( ErrorCode::BOOKINGDATE_IN_WRONG_FORMAT, array (
									Environment::getInstance()->getSettingDateFormat()
							) );
							$all_data [$id] ['bookingdate_error'] = 1;
							$data_is_valid = false;
						}
						$add_data [] = $value;
					} elseif (array_key_exists( 'action', $value ) && ($value ['action'] == 2)) {
						$delete_ids [] = $value ['importedmoneyflowid'];
						$nothing_checked = false;
					}
				}

				if ($nothing_checked) {
					$this->add_error( ErrorCode::NOTHING_MARKED_TO_ADD );
					$data_is_valid = false;
				}

				if ($data_is_valid) {

					if ($delete_ids && is_array( $delete_ids )) {
						foreach ( $delete_ids as $id ) {
							ImportedMoneyflowControllerHandler::getInstance()->deleteImportedMoneyflowById( $id );
						}
					}
					if (is_array( $add_data )) {
						$createMoneyflows = ImportedMoneyflowControllerHandler::getInstance()->addImportedMoneyflows( $add_data );

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
										$this->add_error( $error, array (
												$all_data [$key] ['amount']
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
					}
				}
			default :
				$addMoneyflow = ImportedMoneyflowControllerHandler::getInstance()->showAddImportedMoneyflows();
				$capitalsource_values = $addMoneyflow ['capitalsources'];
				$contractpartner_values = $addMoneyflow ['contractpartner'];
				$postingaccount_values = $addMoneyflow ['postingaccounts'];
				if ($realaction === 'save' && $data_is_valid == true || $realaction != 'save') {
					$all_data = $addMoneyflow ['importedmoneyflows'];
				}
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $this->sort_contractpartner( $contractpartner_values ) );
		$this->template->assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_add_importedmoneyflows.tpl' );
	}
}

?>
