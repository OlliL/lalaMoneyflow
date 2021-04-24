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
namespace client\handler;

use api\model\etf\listEtfOverviewResponse;
use client\mapper\ArrayToEtfSummaryTransportMapper;
use base\Singleton;
use api\model\etf\listEtfFlowsResponse;
use client\mapper\ArrayToEtfFlowTransportMapper;
use client\mapper\ArrayToEtfTransportMapper;
use api\model\etf\calcEtfSaleRequest;
use api\model\etf\calcEtfSaleResponse;
use api\model\etf\showCreateEtfFlowResponse;
use api\model\transport\EtfFlowTransport;
use api\model\etf\createEtfFlowRequest;
use api\model\etf\updateEtfFlowRequest;
use api\model\etf\showEditEtfFlowResponse;
use api\model\etf\showDeleteEtfFlowResponse;

class EtfControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToEtfSummaryTransportMapper::getClass() );
		parent::addMapper( ArrayToEtfFlowTransportMapper::getClass() );
		parent::addMapper( ArrayToEtfTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'etf';
	}

	public final function listEtfOverview($year, $month) {
		$response = parent::getJson( 'listEtfOverview', array (
				$year,
				$month
		) );
		$result = null;
		if ($response instanceof listEtfOverviewResponse) {
			$result ['etfData'] = parent::mapArrayNullable( $response->getEtfSummaryTransport() );
		}
		return $result;
	}

	public final function listEtfFlows() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof listEtfFlowsResponse) {
			$result ['etfs'] = parent::mapArrayNullable( $response->getEtfTransport() );
			$result ['etfFlows'] = parent::mapArrayNullable( $response->getEtfFlowTransport() );
			$result ['calcEtfSaleIsin'] = $response->getCalcEtfSaleIsin();
			$result ['calcEtfSalePieces'] = $response->getCalcEtfSalePieces();
			$result ['calcEtfBidPrice'] = $response->getCalcEtfBidPrice();
			$result ['calcEtfAskPrice'] = $response->getCalcEtfAskPrice();
			$result ['calcEtfTransactionCosts'] = $response->getCalcEtfTransactionCosts();
		}
		return $result;
	}

	public final function calcEtfSale(array $all_data) {
		$request = new calcEtfSaleRequest();
		$request->setAskPrice( $all_data ['askPrice'] );
		$request->setBidPrice( $all_data ['bidPrice'] );
		$request->setTransactionCosts( $all_data ['transactionCosts'] );
		$request->setPieces( $all_data ['pieces'] );
		$request->setIsin( $all_data ['isin'] );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		$return = null;
		if ($response instanceof calcEtfSaleResponse) {
			$return = array ();
			$return ['isin'] = $response->getIsin();
			$return ['originalBuyPrice'] = $response->getOriginalBuyPrice();
			$return ['sellPrice'] = $response->getSellPrice();
			$return ['newBuyPrice'] = $response->getNewBuyPrice();
			$return ['profit'] = $response->getProfit();
			$return ['chargeable'] = $response->getChargeable();
			$return ['transactionCosts'] = $response->getTransactionCosts();
			$return ['rebuyLosses'] = $response->getRebuyLosses();
			$return ['overallCosts'] = $response->getOverallCosts();
			$return ['pieces'] = $response->getPieces();
			if (is_array( $response->getValidationItemTransport() )) {
				$return ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			}
		}

		return $return;
	}

	public final function showCreateEtfFlow() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showCreateEtfFlowResponse) {
			$result = parent::mapArray( $response->getEtfTransport() );
		}
		return $result;
	}

	public final function showEditEtfFlow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditEtfFlowResponse) {
			$result = array ();
			$result ['etfs'] = parent::mapArray( $response->getEtfTransport() );
			$result ['all_data'] = parent::map( $response->getEtfFlowTransport() );
		}
		return $result;
	}

	public final function showDeleteEtfFlow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteEtfFlowResponse) {
			$result = array ();
			$result ['etfs'] = parent::mapArray( $response->getEtfTransport() );
			$result ['all_data'] = parent::map( $response->getEtfFlowTransport() );
		}
		return $result;
	}

	public final function createEtfFlow(array $etfflow) {
		$etfflowTransport = parent::map( $etfflow, EtfFlowTransport::getClass() );

		$request = new createEtfFlowRequest();
		$request->setEtfFlowTransport( $etfflowTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateEtfFlow(array $etfflow) {
		$etfflowTransport = parent::map( $etfflow, EtfFlowTransport::getClass() );

		$request = new updateEtfFlowRequest();
		$request->setEtfFlowTransport( $etfflowTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteEtfFlow( $id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>