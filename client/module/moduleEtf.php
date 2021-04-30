<?php

//
// Copyright (c) 2021 Oliver Lehmann <lehmann@ans-netz.de>
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
//
namespace client\module;

use client\handler\EtfControllerHandler;

class moduleEtf extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_etf_flows() {
		$all_data = array ();
		$etf_data = array ();
		$listEtfFlows = EtfControllerHandler::getInstance()->listEtfFlows();
		$etfs = $listEtfFlows ['etfs'];
		$etfFlows = $listEtfFlows ['etfFlows'];
		if (is_array( $etfs )) {
			foreach ( $etfs as $etf ) {
				$etf_data [$etf ['isin']] ['name'] = $etf ['name'];
				$etf_data [$etf ['isin']] ['chartUrl'] = $etf ['chartUrl'];
			}

			if (is_array( $etfFlows )) {
				foreach ( $etfFlows as $etfFlow ) {
					$all_data_entry = array ();

					$all_data_entry ['etfflowid'] = $etfFlow ['etfflowid'];
					$all_data_entry ['name'] = $etf_data [$etfFlow ['isin']] ['name'];
					$all_data_entry ['chartUrl'] = $etf_data [$etfFlow ['isin']] ['chartUrl'];
					$all_data_entry ['date'] = $etfFlow ['timestamp'];
					$all_data_entry ['amount'] = $etfFlow ['amount'];
					$all_data_entry ['price'] = $etfFlow ['price'];

					$all_data [] = $all_data_entry;
				}
			}
		}

		$this->template_assign( 'ETF_VALUES', $etfs );
		$this->template_assign( 'ALL_DATA', $all_data );

		$this->template_assign( 'CALC_ETF_SALE_ISIN', $listEtfFlows ['calcEtfSaleIsin'] );
		$this->template_assign( 'CALC_ETF_SALE_PIECES', $listEtfFlows ['calcEtfSalePieces'] );
		$this->template_assign( 'CALC_ETF_BID_PRICE', $listEtfFlows ['calcEtfBidPrice'] );
		$this->template_assign( 'CALC_ETF_ASK_PRICE', $listEtfFlows ['calcEtfAskPrice'] );
		$this->template_assign( 'CALC_ETF_TRANSACTIOM_COSTS', $listEtfFlows ['calcEtfTransactionCosts'] );

		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );

		$this->parse_header_without_embedded( 0, 'display_list_etf_flows_bs.tpl' );
		return $this->fetch_template( 'display_list_etf_flows_bs.tpl' );
	}

	public final function calc_etf_sale($all_data) {
		$ret = EtfControllerHandler::getInstance()->calcEtfSale( $all_data );
		return $this->handleReturnForAjax( $ret );
	}

	public final function display_edit_etf_flow($id, $all_data) {
		if ($id > 0) {
			$showEditEtfFlow = EtfControllerHandler::getInstance()->showEditEtfFlow( $id );
			$etfs = $showEditEtfFlow ['etfs'];
			$all_data = $showEditEtfFlow ['all_data'];
		} else {
			$etfs = EtfControllerHandler::getInstance()->showCreateEtfFlow();
			$all_data = array ();
		}

		$this->template_assign( 'ETF_VALUES', $etfs );
		$this->template_assign( 'ETFFLOWID', $id );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		$this->parse_header_without_embedded( 1, 'display_edit_etf_flow_bs.tpl' );
		return $this->fetch_template( 'display_edit_etf_flow_bs.tpl' );
	}

	public final function edit_etf_flow($id, $all_data) {
		$all_data ['etfflowid'] = $id;

		if ($id == 0)
			$ret = EtfControllerHandler::getInstance()->createEtfFlow( $all_data );
		else
			$ret = EtfControllerHandler::getInstance()->updateEtfFlow( $all_data );
		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_etf_flow($id) {
		$showDeleteEtfFlow = EtfControllerHandler::getInstance()->showDeleteEtfFlow( $id );
		$etfs = $showDeleteEtfFlow ['etfs'];
		$all_data = $showDeleteEtfFlow ['all_data'];

		$this->template_assign( 'ETFFLOWID', $id );
		$this->template_assign_raw( 'ETF_VALUES', json_encode( $etfs ) );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		$this->parse_header_without_embedded( 1, 'display_delete_etf_flow_bs.tpl' );
		return $this->fetch_template( 'display_delete_etf_flow_bs.tpl' );
	}

	public final function delete_etf_flow($id) {
		$ret = EtfControllerHandler::getInstance()->deleteEtfFlow( $id );
		return $this->handleReturnForAjax( $ret );
	}
}
?>
