<?php
//
// Copyright (c) 2014-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ContractpartnerAccountControllerHandler.php,v 1.5 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ArrayToContractpartnerAccountTransportMapper;
use api\model\transport\ContractpartnerAccountTransport;
use api\model\contractpartneraccount\createContractpartnerAccountRequest;
use api\model\contractpartneraccount\showContractpartnerAccountListResponse;
use api\model\contractpartneraccount\showDeleteContractpartnerAccountResponse;
use api\model\contractpartneraccount\showEditContractpartnerAccountResponse;
use api\model\contractpartneraccount\updateContractpartnerAccountRequest;
use base\Singleton;

class ContractpartnerAccountControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToContractpartnerAccountTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'contractpartneraccount';
	}

	public final function showContractpartnerAccountList($contractpartnerId) {
		$response = parent::getJson( __FUNCTION__, array (
				$contractpartnerId
		) );

		$result = null;
		if ($response instanceof showContractpartnerAccountListResponse) {
			$result ['contractpartneraccount'] = parent::mapArrayNullable( $response->getContractpartnerAccountTransport() );
			$result ['contractpartnername'] = $response->getContractpartnerName();
		}

		return $result;
	}

	public final function showEditContractpartnerAccount($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditContractpartnerAccountResponse) {
			$result = parent::map( $response->getContractpartnerAccountTransport() );
		}
		return $result;
	}

	public final function showDeleteContractpartnerAccount($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteContractpartnerAccountResponse) {
			$result = parent::map( $response->getContractpartnerAccountTransport() );
		}
		return $result;
	}

	public final function createContractpartnerAccount(array $contractpartnerAccount) {
		$contractpartnerAccountTransport = parent::map( $contractpartnerAccount, ContractpartnerAccountTransport::getClass() );

		$request = new createContractpartnerAccountRequest();
		$request->setContractpartnerAccountTransport( $contractpartnerAccountTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateContractpartnerAccount(array $contractpartner) {
		$contractpartnerAccountTransport = parent::map( $contractpartner, ContractpartnerAccountTransport::getClass() );

		$request = new updateContractpartnerAccountRequest();
		$request->setContractpartnerAccountTransport( $contractpartnerAccountTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteContractpartnerAccount($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>