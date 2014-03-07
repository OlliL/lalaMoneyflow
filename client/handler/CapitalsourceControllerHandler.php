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
// $Id: CapitalsourceControllerHandler.php,v 1.11 2014/03/07 20:41:36 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\capitalsource\updateCapitalsourceRequest;
use api\model\capitalsource\createCapitalsourceRequest;
use api\model\capitalsource\showCapitalsourceListResponse;
use api\model\capitalsource\showEditCapitalsourceResponse;
use api\model\capitalsource\showDeleteCapitalsourceResponse;

class CapitalsourceControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new CapitalsourceControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'capitalsource';
	}

	public final function showCapitalsourceList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				utf8_encode( $restriction )
		) );
		if ($response instanceof showCapitalsourceListResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['initials'] = $response->getInitials();
		}

		return $result;
	}

	public final function showEditCapitalsource($id) {
		$response = parent::getJson( showEditCapitalsource, array (
				$id
		) );
		if ($response instanceof showEditCapitalsourceResponse) {
			$result = parent::map( $response->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function showDeleteCapitalsource($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		if ($response instanceof showDeleteCapitalsourceResponse) {
			$result = parent::map( $response->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function createCapitalsource(array $capitalsource) {
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new createCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateCapitalsource(array $capitalsource) {
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new updateCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteCapitalsourceById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>