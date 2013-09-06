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
// $Id: modulePreDefMoneyFlows.php,v 1.33 2013/09/06 19:33:37 olivleh1 Exp $
//
use rest\client\CallServer;
use rest\client\mapper\ClientArrayMapperEnum;

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreCurrencies.php';
require_once 'core/corePreDefMoneyFlows.php';

class modulePreDefMoneyFlows extends module {

	function modulePreDefMoneyFlows() {
		parent::__construct();
		$this->coreCapitalSources = new coreCapitalSources();
		$this->coreContractPartners = new coreContractPartners();
		$this->coreCurrencies = new coreCurrencies();
		$this->corePreDefMoneyFlows = new corePreDefMoneyFlows();
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerMapper', ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_ARRAY_TYPE );
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
		$all_index_letters = CallServer::getInstance()->getAllPreDefMoneyflowInitials();

		if (! $letter) {
			$num_flows = CallServer::getInstance()->getAllPreDefMoneyflowCount();
			if ($num_flows < $this->coreTemplates->get_max_rows()) {
				$letter = 'all';
			}
		}

		if ($letter == 'all') {
			$preDefMoneyflowsArray = CallServer::getInstance()->getAllPreDefMoneyflows();
		} elseif (! empty( $letter )) {
			$preDefMoneyflowsArray = CallServer::getInstance()->getAllPreDefMoneyflowsByInitial( $letter );
		} else {
			$preDefMoneyflowsArray = array ();
		}
		if (is_array( $preDefMoneyflowsArray )) {
			$all_data = parent::mapArray( $preDefMoneyflowsArray );
			$this->template->assign( 'ALL_DATA', $all_data );
		}

		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );
		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );

		$this->parse_header();
		return $this->fetch_template( 'display_list_predefmoneyflows.tpl' );
	}

	function display_edit_predefmoneyflow($realaction, $id, $all_data) {
		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;

				if (empty( $all_data ['mcs_capitalsourceid'] )) {
					add_error( 127 );
					$data_is_valid = false;
				}
				;

				if (empty( $all_data ['mcp_contractpartnerid'] )) {
					add_error( 128 );
					$data_is_valid = false;
				}
				;

				if ($data_is_valid) {
					if ($id == 0)
						$ret = $this->corePreDefMoneyFlows->add_predefmoneyflow( $all_data ['amount'], $all_data ['mcs_capitalsourceid'], $all_data ['mcp_contractpartnerid'], $all_data ['comment'], $all_data ['once_a_month'] );
					else
						$ret = $this->corePreDefMoneyFlows->update_predefmoneyflow( $id, $all_data ['amount'], $all_data ['mcs_capitalsourceid'], $all_data ['mcp_contractpartnerid'], $all_data ['comment'], $all_data ['once_a_month'] );

					if ($ret === true || $ret > 0) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					}
				}
			default :
				if ($id > 0) {
					$all_data = $this->corePreDefMoneyFlows->get_id_data( $id );
					$this->template->assign( 'ALL_DATA', $all_data );
					$capitalsourceid = $this->corePreDefMoneyFlows->get_capitalsourceid( $id );

					$capitalsource = CallServer::getInstance()->getCapitalsourceById( $capitalsourceid );
					if ($capitalsource) {
						$today = new \DateTime();
						$today->setTime( 0, 0, 0 );

						$validFrom = new \DateTime();
						$validFrom->setTimestamp( convert_date_to_timestamp( $capitalsource ['validfrom'] ) );

						$validTil = new \DateTime();
						$validTil->setTimestamp( convert_date_to_timestamp( $capitalsource ['validtil'] ) );

						if ($today < $validFrom || $today > $validTil) {
							$capitalsourceArray = CallServer::getInstance()->getAllCapitalsources();
						} else {
							$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( time(), time() );
						}
					}
					$this->template->assign( 'PREDEFMONEYFLOWID', $id );
				} else {
					$capitalsourceArray = CallServer::getInstance()->getAllCapitalsourcesByDateRange( time(), time() );
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

		$this->template->assign( 'CURRENCY', $this->coreCurrencies->get_displayed_currency() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_predefmoneyflow.tpl' );
	}

	function display_delete_predefmoneyflow($realaction, $id) {
		switch ($realaction) {
			case 'yes' :
				if ($this->corePreDefMoneyFlows->delete_predefmoneyflow( $id )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}

			default :
				$all_data = $this->corePreDefMoneyFlows->get_id_data( $id );
				$all_data ['capitalsource_comment'] = $this->coreCapitalSources->get_comment( $all_data ['mcs_capitalsourceid'] );
				$all_data ['contractpartner_name'] = $this->coreContractPartners->get_name( $all_data ['mcp_contractpartnerid'] );
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
