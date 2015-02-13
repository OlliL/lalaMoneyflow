<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: PreDefMoneyflowControllerHandler.php,v 1.13 2015/02/13 00:03:38 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
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

class PreDefMoneyflowControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToPreDefMoneyflowTransportMapper::getClass() );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new PreDefMoneyflowControllerHandler();
		}
		return self::$instance;
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
		$response = parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof createPreDefMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}
		return $result;
	}

	public final function updatePreDefMoneyflow(array $preDefMoneyflow) {
		$preDefMoneyflowTransport = parent::map( $preDefMoneyflow, PreDefMoneyflowTransport::getClass() );

		$request = new updatePreDefMoneyflowRequest();
		$request->setPreDefMoneyflowTransport( $preDefMoneyflowTransport );
		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof updatePreDefMoneyflowResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}
		return $result;
	}

	public final function deletePreDefMoneyflow($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>