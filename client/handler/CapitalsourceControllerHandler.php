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
// $Id: CapitalsourceControllerHandler.php,v 1.7 2014/02/27 19:31:01 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\capitalsource\updateCapitalsourceRequest;
use rest\api\model\capitalsource\createCapitalsourceRequest;

class CapitalsourceControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new CapitalsourceControllerHandler();
		}
		return self::$instance;
	}

	public final function showCapitalsourceList($restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/showCapitalsourceList/' . utf8_encode($restriction) . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$listCapitalsources = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $listCapitalsources->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $listCapitalsources->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			$result ['initials'] = $listCapitalsources->getInitials();
		}

		return $result;
	}

	public final function showEditCapitalsource($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/showEditCapitalsource/' . $id . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showEditCapitalsourceResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			$result = parent::map( $showEditCapitalsourceResponse->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function showDeleteCapitalsource($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/showDeleteCapitalsource/' . $id . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showDeleteCapitalsourceResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			$result = parent::map( $showDeleteCapitalsourceResponse->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function createCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/createCapitalsource/' . parent::getSessionId();
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new createCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return parent::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/updateCapitalsource/' . parent::getSessionId();
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new updateCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return parent::putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteCapitalsource($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/deleteCapitalsourceById/' . $id . '/' . parent::getSessionId();
		return parent::deleteJson( $url );
	}
}

?>