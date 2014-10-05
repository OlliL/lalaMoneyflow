<?php
//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ContractpartnerAccountControllerHandler.php,v 1.1 2014/10/05 14:12:53 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
use client\mapper\ArrayToContractpartnerAccountTransportMapper;
use api\model\transport\ContractpartnerAccountTransport;

class ContractpartnerAccountControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( ArrayToContractpartnerAccountTransportMapper::getClass() );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new ContractpartnerAccountControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'contractpartneraccount';
	}

	public final function showContractpartnerAccountList() {
		// $response = parent::getJson( __FUNCTION__, array (
		// $restriction
		// ) );
		// if ($response instanceof showContractpartnerListResponse) {
		// $result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
		// $result ['initials'] = $response->getInitials();
		// }

		// return $result;
	}

	public final function showEditContractpartnerAccount($id) {
		// $response = parent::getJson( __FUNCTION__, array (
		// $id
		// ) );
		// if ($response instanceof showEditContractpartnerResponse) {
		// $result = parent::map( $response->getContractpartnerTransport() );
		// }
		// return $result;
	}

	public final function showDeleteContractpartnerAccount($id) {
		// $response = parent::getJson( __FUNCTION__, array (
		// $id
		// ) );
		// if ($response instanceof showDeleteContractpartnerResponse) {
		// $result = parent::map( $response->getContractpartnerTransport() );
		// }
		// return $result;
	}

	public final function createContractpartnerAccount(array $contractpartner) {
		// $contractpartnerTransport = parent::map( $contractpartner, ContractpartnerTransport::getClass() );

		// $request = new createContractpartnerRequest();
		// $request->setContractpartnerTransport( $contractpartnerTransport );
		// return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateContractpartnerAccount(array $contractpartner) {
		// $contractpartnerTransport = parent::map( $contractpartner, ContractpartnerTransport::getClass() );

		// $request = new updateContractpartnerRequest();
		// $request->setContractpartnerTransport( $contractpartnerTransport );
		// return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteContractpartnerAccount($id) {
		// return parent::deleteJson( __FUNCTION__, array (
		// $id
		// ) );
	}
}

?>