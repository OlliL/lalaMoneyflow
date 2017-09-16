<?php

//
// Copyright (c) 2013-2016 Oliver Lehmann <oliver@laladev.org>
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
// $Id: MoneyflowControllerHandler.php,v 1.16 2016/12/26 21:03:25 olivleh1 Exp $
//
namespace client\handler;

use api\model\moneyflow\updateMoneyflowRequest;
use api\model\moneyflow\createMoneyflowRequest;
use api\model\moneyflow\searchMoneyflowsRequest;
use api\model\moneyflow\showAddMoneyflowsResponse;
use api\model\moneyflow\showEditMoneyflowResponse;
use api\model\moneyflow\showDeleteMoneyflowResponse;
use api\model\moneyflow\updateMoneyflowResponse;
use api\model\moneyflow\showSearchMoneyflowFormResponse;
use api\model\moneyflow\searchMoneyflowsResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToContractpartnerTransportMapper;
use client\mapper\ArrayToMoneyflowTransportMapper;
use client\mapper\ArrayToPreDefMoneyflowTransportMapper;
use client\mapper\ArrayToPostingAccountTransportMapper;
use client\mapper\ArrayToMoneyflowSearchParamsTransportMapper;
use client\mapper\ArrayToMoneyflowSearchResultTransportMapper;
use api\model\transport\MoneyflowTransport;
use api\model\transport\MoneyflowSearchParamsTransport;
use base\Singleton;
use client\mapper\ArrayToMoneyflowSplitEntryTransportMapper;
use api\model\transport\MoneyflowSplitEntryTransport;
use api\model\validation\validationResponse;

class MoneyflowControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowTransportMapper::getClass() );
		parent::addMapper( ArrayToPreDefMoneyflowTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowSearchParamsTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowSearchResultTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowSplitEntryTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'moneyflow';
	}

	public final function showAddMoneyflows() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showAddMoneyflowsResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['predefmoneyflows'] = parent::mapArrayNullable( $response->getPreDefMoneyflowTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['num_free_moneyflows'] = $response->getSettingNumberOfFreeMoneyflows();
		}

		return $result;
	}

	public final function showEditMoneyflow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			if ($response->getMoneyflowTransport()) {
				$result ['moneyflow'] = parent::map( $response->getMoneyflowTransport() );
			} else {
				$result ['moneyflow'] = array ();
			}
			$result ['moneyflow_split_entries'] = parent::mapArrayNullable( $response->getMoneyflowSplitEntryTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}

		return $result;
	}

	public final function showDeleteMoneyflow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteMoneyflowResponse) {
			$result = parent::map( $response->getMoneyflowTransport() );
		}
		return $result;
	}

	public final function createMoneyflow($moneyflow, $insert_moneyflowsplitentries) {
		$preDefMoneyflowId = null;
		$saveAsPreDefMoneyflow = null;

		if ($moneyflow ['predefmoneyflowid'] > 0) {
			$preDefMoneyflowId = $moneyflow ['predefmoneyflowid'];
		}
		if (array_key_exists( 'save_as_predefmoneyflow', $moneyflow ) && $moneyflow ['save_as_predefmoneyflow'] > 0) {
			$saveAsPreDefMoneyflow = $moneyflow ['save_as_predefmoneyflow'];
		}

		$moneyflowTransport = parent::map( $moneyflow, MoneyflowTransport::getClass() );

		$request = new createMoneyflowRequest();

		if (count( $insert_moneyflowsplitentries ) > 0) {
			$insertMoneyflowSplitEntryTransport = parent::mapArray( $insert_moneyflowsplitentries, MoneyflowSplitEntryTransport::getClass() );
			$request->setInsertMoneyflowSplitEntryTransport( $insertMoneyflowSplitEntryTransport );
		}

		$request->setMoneyflowTransport( $moneyflowTransport );
		$request->setUsedPreDefMoneyflowId( $preDefMoneyflowId );
		$request->setSaveAsPreDefMoneyflow( $saveAsPreDefMoneyflow );

		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateMoneyflow(array $moneyflow, array $delete_moneyflowsplitentryids, array $update_moneyflowsplitentrys, array $insert_moneyflowsplitentrys) {
		$moneyflowTransport = parent::map( $moneyflow, MoneyflowTransport::getClass() );

		$request = new updateMoneyflowRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		if (count( $delete_moneyflowsplitentryids ) > 0) {
			$request->setDeleteMoneyflowSplitEntryIds( $delete_moneyflowsplitentryids );
		}
		if (count( $update_moneyflowsplitentrys ) > 0) {
			$updateMoneyflowSplitEntryTransport = parent::mapArray( $update_moneyflowsplitentrys, MoneyflowSplitEntryTransport::getClass() );
			$request->setUpdateMoneyflowSplitEntryTransport( $updateMoneyflowSplitEntryTransport );
		}
		if (count( $insert_moneyflowsplitentrys ) > 0) {
			$insertMoneyflowSplitEntryTransport = parent::mapArray( $insert_moneyflowsplitentrys, MoneyflowSplitEntryTransport::getClass() );
			$request->setInsertMoneyflowSplitEntryTransport( $insertMoneyflowSplitEntryTransport );
		}

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof updateMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}
		return $result;
	}

	public final function deleteMoneyflowById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}

	public final function showSearchMoneyflowForm() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showSearchMoneyflowFormResponse) {
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function searchMoneyflows(array $params) {
		$searchParamsTransport = parent::map( $params, MoneyflowSearchParamsTransport::getClass() );

		$request = new searchMoneyflowsRequest();
		$request->setMoneyflowSearchParamsTransport( $searchParamsTransport );
		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response instanceof searchMoneyflowsResponse) {
			$result ['search_results'] = parent::mapArrayNullable( $response->getMoneyflowSearchResultTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}
		return $result;
	}
}

?>