<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: PreDefMoneyflowControllerHandler.php,v 1.15 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\predefmoneyflow\updatePreDefMoneyflowRequest;
use api\model\predefmoneyflow\createPreDefMoneyflowRequest;
use api\model\predefmoneyflow\showPreDefMoneyflowListResponse;
use api\model\predefmoneyflow\showEditPreDefMoneyflowResponse;
use api\model\predefmoneyflow\showCreatePreDefMoneyflowResponse;
use api\model\predefmoneyflow\showDeletePreDefMoneyflowResponse;
use api\model\predefmoneyflow\createPreDefMoneyflowResponse;
use api\model\predefmoneyflow\updatePreDefMoneyflowResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToContractpartnerTransportMapper;
use client\mapper\ArrayToPostingAccountTransportMapper;
use client\mapper\ArrayToPreDefMoneyflowTransportMapper;
use api\model\transport\PreDefMoneyflowTransport;
use base\Singleton;
use api\model\validation\validationResponse;

class PreDefMoneyflowControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToPreDefMoneyflowTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'predefmoneyflow';
	}

	public final function showPreDefMoneyflowList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction
		) );
		$result = null;
		if ($response instanceof showPreDefMoneyflowListResponse) {
			$result ['predefmoneyflows'] = parent::mapArrayNullable( $response->getPreDefMoneyflowTransport() );
			$result ['initials'] = $response->getInitials();
		}

		return $result;
	}

	public final function showEditPreDefMoneyflow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditPreDefMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['predefmoneyflow'] = parent::map( $response->getPreDefMoneyflowTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}

		return $result;
	}

	public final function showCreatePreDefMoneyflow() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showCreatePreDefMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}

		return $result;
	}

	public final function showDeletePreDefMoneyflow($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeletePreDefMoneyflowResponse) {
			$result = parent::map( $response->getPreDefMoneyflowTransport() );
		}
		return $result;
	}

	public final function createPreDefMoneyflow(array $preDefMoneyflow) {
		$preDefMoneyflowTransport = parent::map( $preDefMoneyflow, PreDefMoneyflowTransport::getClass() );

		$request = new createPreDefMoneyflowRequest();
		$request->setPreDefMoneyflowTransport( $preDefMoneyflowTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updatePreDefMoneyflow(array $preDefMoneyflow) {
		$preDefMoneyflowTransport = parent::map( $preDefMoneyflow, PreDefMoneyflowTransport::getClass() );

		$request = new updatePreDefMoneyflowRequest();
		$request->setPreDefMoneyflowTransport( $preDefMoneyflowTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deletePreDefMoneyflow($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>