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
// $Id: CapitalsourceControllerHandler.php,v 1.20 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\capitalsource\updateCapitalsourceRequest;
use api\model\capitalsource\createCapitalsourceRequest;
use api\model\capitalsource\showCapitalsourceListResponse;
use api\model\capitalsource\showEditCapitalsourceResponse;
use api\model\capitalsource\showDeleteCapitalsourceResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use api\model\transport\CapitalsourceTransport;
use base\Singleton;

class CapitalsourceControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'capitalsource';
	}

	public final function showCapitalsourceList($restriction, $currently_valid) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction,
				"currentlyValid",
				$currently_valid
		) );
		$result = null;
		if ($response instanceof showCapitalsourceListResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['initials'] = $response->getInitials();
			$result ['currently_valid'] = $response->getCurrentlyValid();
		}

		return $result;
	}

	public final function showEditCapitalsource($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditCapitalsourceResponse) {
			$result = parent::map( $response->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function showDeleteCapitalsource($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteCapitalsourceResponse) {
			$result = parent::map( $response->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function createCapitalsource(array $capitalsource) {
		$capitalsourceTransport = parent::map( $capitalsource, CapitalsourceTransport::getClass() );

		$request = new createCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateCapitalsource(array $capitalsource) {
		$capitalsourceTransport = parent::map( $capitalsource, CapitalsourceTransport::getClass() );

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