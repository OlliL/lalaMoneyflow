<?php
//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ContractpartnerControllerHandler.php,v 1.7 2014/02/28 22:19:47 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\contractpartner\updateContractpartnerRequest;
use api\model\contractpartner\createContractpartnerRequest;

class ContractpartnerControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new ContractpartnerControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'contractpartner';
	}

	public final function showContractpartnerList($restriction) {
		$response = parent::getJson( 'showContractpartnerList', array (
				utf8_encode( $restriction )
		) );
		if (is_array( $response )) {
			$listContractpartner = JsonAutoMapper::mapAToB( $response, '\\api\\model\\contractpartner' );
			if (is_array( $listContractpartner->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $listContractpartner->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			$result ['initials'] = $listContractpartner->getInitials();
		}

		return $result;
	}

	public final function showEditContractpartner($id) {
		$response = parent::getJson( 'showEditContractpartner', array (
				$id
		) );
		if (is_array( $response )) {
			$showEditContractpartner = JsonAutoMapper::mapAToB( $response, '\\api\\model\\contractpartner' );
			$result = parent::map( $showEditContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function showDeleteContractpartner($id) {
		$response = parent::getJson( 'showDeleteContractpartner', array (
				$id
		) );
		if (is_array( $response )) {
			$showDeleteContractpartner = JsonAutoMapper::mapAToB( $response, '\\api\\model\\contractpartner' );
			$result = parent::map( $showDeleteContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function createContractpartner(array $contractpartner) {
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new createContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return parent::postJson( 'createContractpartner', parent::json_encode_response( $request ) );
	}

	public final function updateContractpartner(array $contractpartner) {
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new updateContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return parent::putJson( 'updateContractpartner', parent::json_encode_response( $request ) );
	}

	public final function deleteContractpartner($id) {
		return parent::deleteJson( 'deleteContractpartnerById', array (
				$id
		) );
	}
}

?>