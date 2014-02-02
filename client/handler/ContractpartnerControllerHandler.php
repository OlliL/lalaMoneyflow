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
// $Id: ContractpartnerControllerHandler.php,v 1.2 2014/02/02 00:28:19 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\contractpartner\updateContractpartnerRequest;
use rest\api\model\contractpartner\createContractpartnerRequest;

class ContractpartnerControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new ContractpartnerControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}
	public final function showContractpartnerList($maxRows, $restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showContractpartnerList/' . $maxRows . '/' . utf8_encode($restriction) . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$listContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
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
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showEditContractpartner/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showEditContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $showEditContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function showDeleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showDeleteContractpartner/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showDeleteContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $showDeleteContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllContractpartner() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/getAllContractpartner/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$getAllContractpartnerResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			if (is_array( $getAllContractpartnerResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $getAllContractpartnerResponse->getContractpartnerTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function createContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/createContractpartner/' . self::$callServer->getSessionId();
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new createContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return self::$callServer->postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/updateContractpartner/' . self::$callServer->getSessionId();
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new updateContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return self::$callServer->putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/deleteContractpartnerById/' . $id . '/' . self::$callServer->getSessionId();
		return self::$callServer->deleteJson( $url );
	}
}

?>