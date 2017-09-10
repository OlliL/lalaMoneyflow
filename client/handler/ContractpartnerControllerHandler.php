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
// $Id: ContractpartnerControllerHandler.php,v 1.18 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\contractpartner\updateContractpartnerRequest;
use api\model\contractpartner\createContractpartnerRequest;
use api\model\contractpartner\showContractpartnerListResponse;
use api\model\contractpartner\showEditContractpartnerResponse;
use api\model\contractpartner\showDeleteContractpartnerResponse;
use client\mapper\ArrayToContractpartnerTransportMapper;
use api\model\transport\ContractpartnerTransport;
use api\model\contractpartner\showCreateContractpartnerResponse;
use client\mapper\ArrayToPostingAccountTransportMapper;
use base\Singleton;
use api\model\contractpartner\createContractpartnerResponse;

class ContractpartnerControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'contractpartner';
	}

	public final function showContractpartnerList($restriction, $currently_valid) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction,
				"currentlyValid",
				$currently_valid
		) );
		$result = null;
		if ($response instanceof showContractpartnerListResponse) {
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['initials'] = $response->getInitials();
			$result ['currently_valid'] = $response->getCurrentlyValid();
		}

		return $result;
	}

	public final function showCreateContractpartner() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showCreateContractpartnerResponse) {
			$result = parent::mapArray( $response->getPostingAccountTransport() );
			;
		}
		return $result;
	}

	public final function showEditContractpartner($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditContractpartnerResponse) {
			$result ['contractpartner'] = parent::map( $response->getContractpartnerTransport() );
			$result ['postingAccounts'] = parent::mapArray( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function showDeleteContractpartner($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteContractpartnerResponse) {
			$result = parent::map( $response->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function createContractpartner(array $contractpartner) {
		$contractpartnerTransport = parent::map( $contractpartner, ContractpartnerTransport::getClass() );

		$request = new createContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		$response = parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof createContractpartnerResponse) {
			if($response->getContractPartnerId() != null) {
				$result ['contractpartnerid'] = $response->getContractPartnerId();
			} else {
				$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
				$result ['result'] = $response->getResult();
			}
		}

		return $result;
	}

	public final function updateContractpartner(array $contractpartner) {
		$contractpartnerTransport = parent::map( $contractpartner, ContractpartnerTransport::getClass() );

		$request = new updateContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;

		if ($response === true) {
			$result = true;
		} else if (is_array($response)) {
			$result = $response;
		}

		return $result;
	}

	public final function deleteContractpartner($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>