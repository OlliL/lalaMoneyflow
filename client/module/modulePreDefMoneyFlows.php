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
// $Id: modulePreDefMoneyFlows.php,v 1.60 2015/09/11 07:57:15 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\PreDefMoneyflowControllerHandler;

class modulePreDefMoneyFlows extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_predefmoneyflows($letter) {
		$listPreDefMoneyflows = PreDefMoneyflowControllerHandler::getInstance()->showPreDefMoneyflowList( $letter );

		$all_index_letters = $listPreDefMoneyflows ['initials'];
		$all_data = $listPreDefMoneyflows ['predefmoneyflows'];

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'LETTER', $letter );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header_bootstraped( 0, 'display_list_predefmoneyflows_bs.tpl' );
		return $this->fetch_template( 'display_list_predefmoneyflows_bs.tpl' );
	}

	public final function display_edit_predefmoneyflow($realaction, $predefmoneyflowid, $all_data) {
		$close = 0;
		switch ($realaction) {
			case 'save' :
				$all_data ['predefmoneyflowid'] = $predefmoneyflowid;
				$all_data ['amount_error'] = 0;
				$all_data ['capitalsource_error'] = 0;
				$all_data ['contractpartner_error'] = 0;
				if (! $this->fix_amount( $all_data ['amount'] )) {
					$this->add_error( ErrorCode::AMOUNT_IN_WRONG_FORMAT, array (
							$all_data ['amount']
					) );
					$all_data ['amount_error'] = 1;
					break;
				}

				if ($predefmoneyflowid == 0)
					$ret = PreDefMoneyflowControllerHandler::getInstance()->createPreDefMoneyflow( $all_data );
				else
					$ret = PreDefMoneyflowControllerHandler::getInstance()->updatePreDefMoneyflow( $all_data );

				if ($ret === true) {
					$close = 1;
					break;
				} else {
					$capitalsource_values = $ret ['capitalsources'];
					$contractpartner_values = $ret ['contractpartner'];
					$postingaccount_values = $ret ['postingaccounts'];
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						switch ($error) {
							case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
								$this->add_error( $error, array (
										$all_data ['amount']
								) );
								break;
							default :
								$this->add_error( $error );
						}

						switch ($error) {
							case ErrorCode::CAPITALSOURCE_DOES_NOT_EXIST :
							case ErrorCode::CAPITALSOURCE_IS_NOT_SET :
							case ErrorCode::CAPITALSOURCE_USE_OUT_OF_VALIDITY :
								$all_data ['capitalsource_error'] = 1;
								break;
							case ErrorCode::CONTRACTPARTNER_DOES_NOT_EXIST :
							case ErrorCode::CONTRACTPARTNER_IS_NOT_SET :
							case ErrorCode::CONTRACTPARTNER_NO_LONGER_VALID :
								$all_data ['contractpartner_error'] = 1;
								break;
							case ErrorCode::AMOUNT_IS_ZERO :
							case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
								$all_data ['amount_error'] = 1;
								break;
						}
					}
				}
				break;
			default :
				if ($predefmoneyflowid > 0) {
					$showEditPreDefMoneyflow = PreDefMoneyflowControllerHandler::getInstance()->showEditPreDefMoneyflow( $predefmoneyflowid );
					$all_data = $showEditPreDefMoneyflow ['predefmoneyflow'];
					$capitalsource_values = $showEditPreDefMoneyflow ['capitalsources'];
					$contractpartner_values = $showEditPreDefMoneyflow ['contractpartner'];
					$postingaccount_values = $showEditPreDefMoneyflow ['postingaccounts'];
				} else {
					$showCreatePreDefMoneyflow = PreDefMoneyflowControllerHandler::getInstance()->showCreatePreDefMoneyflow();
					$capitalsource_values = $showCreatePreDefMoneyflow ['capitalsources'];
					$contractpartner_values = $showCreatePreDefMoneyflow ['contractpartner'];
					$postingaccount_values = $showCreatePreDefMoneyflow ['postingaccounts'];
					$all_data = array (
							'amount' => null,
							'mcp_contractpartnerid' => '',
							'comment' => '',
							'mcs_capitalsourceid' => '',
							'once_a_month' => '',
							'amount_error' => 0,
							'contractpartner_error' => 0,
							'capitalsource_error' => 0
					);
				}
				break;
		}
		$this->template_assign( 'CLOSE', $close );
		if ($close === 0) {
			$this->template_assign( 'ALL_DATA', $all_data );
			$this->template_assign( 'PREDEFMONEYFLOWID', $predefmoneyflowid );
			$this->template_assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
			$this->template_assign( 'CONTRACTPARTNER_VALUES', $this->sort_contractpartner( $contractpartner_values ) );
			$this->template_assign( 'POSTINGACCOUNT_VALUES', $postingaccount_values );
			$this->template_assign( 'ERRORS', $this->get_errors() );
		}

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_predefmoneyflow.tpl' );
	}

	public final function display_delete_predefmoneyflow($realaction, $predefmoneyflowid) {
		switch ($realaction) {
			case 'yes' :
				if (PreDefMoneyflowControllerHandler::getInstance()->deletePreDefMoneyflow( $predefmoneyflowid )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				$all_data = PreDefMoneyflowControllerHandler::getInstance()->showDeletePreDefMoneyflow( $predefmoneyflowid );
				$this->template_assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_predefmoneyflow.tpl' );
	}
}
?>
