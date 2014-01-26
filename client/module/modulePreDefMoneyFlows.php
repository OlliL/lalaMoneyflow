<?php
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
// $Id: modulePreDefMoneyFlows.php,v 1.43 2014/01/26 00:34:08 olivleh1 Exp $
//
use rest\client\CallServer;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\ErrorCode;

require_once 'module/module.php';
require_once 'core/coreCurrencies.php';

class modulePreDefMoneyFlows extends module {

	function modulePreDefMoneyFlows() {
		parent::__construct();
		$this->coreCurrencies = new coreCurrencies();
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

	public final function display_list_predefmoneyflows($letter) {
		$maxRows = $this->coreTemplates->get_max_rows();
		$listPreDefMoneyflows = CallServer::getInstance()->showPreDefMoneyflowList( $maxRows, $letter );

		$all_index_letters = $listPreDefMoneyflows ['initials'];
		$all_data = $listPreDefMoneyflows ['predefmoneyflows'];

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );

		$this->parse_header();
		return $this->fetch_template( 'display_list_predefmoneyflows.tpl' );
	}

	function display_edit_predefmoneyflow($realaction, $predefmoneyflowid, $all_data) {
		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				$all_data ['predefmoneyflowid'] = $predefmoneyflowid;

				if ($data_is_valid) {

					if ($predefmoneyflowid == 0)
						$ret = CallServer::getInstance()->createPreDefMoneyflow( $all_data );
					else
						$ret = CallServer::getInstance()->updatePreDefMoneyflow( $all_data );

					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							switch ($error) {
								case ErrorCode::AMOUNT_IN_WRONG_FORMAT :
									add_error( $error, array (
											$all_data ['amount']
									) );
									break;
								default :
									add_error( $error );
							}

							switch ($error) {
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
					$capitalsourceArray = $ret ['capitalsources'];
					$contractpartner_values = $ret ['contractpartner'];
				}
				break;
			default :
				if ($predefmoneyflowid > 0) {
					$showEditPreDefMoneyflow = CallServer::getInstance()->showEditPreDefMoneyflow( $predefmoneyflowid );
					$all_data = $showEditPreDefMoneyflow ['predefmoneyflow'];
					$capitalsourceArray = $showEditPreDefMoneyflow ['capitalsources'];
					$contractpartner_values = $showEditPreDefMoneyflow ['contractpartner'];
				} else {
					$showCreatePreDefMoneyflow = CallServer::getInstance()->showCreatePreDefMoneyflow();
					$capitalsourceArray = $showCreatePreDefMoneyflow ['capitalsources'];
					$contractpartner_values = $showCreatePreDefMoneyflow ['contractpartner'];
				}
				break;
		}

		$capitalsource_values = $this->filterCapitalsource( $capitalsourceArray );

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'CONTRACTPARTNER_VALUES', $contractpartner_values );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_predefmoneyflow.tpl' );
	}

	function display_delete_predefmoneyflow($realaction, $predefmoneyflowid) {
		switch ($realaction) {
			case 'yes' :
				if (CallServer::getInstance()->deletePreDefMoneyflow( $predefmoneyflowid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				$all_data = CallServer::getInstance()->showDeletePreDefMoneyflow( $predefmoneyflowid );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_predefmoneyflow.tpl' );
	}
}
?>
