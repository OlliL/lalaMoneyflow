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
// $Id: AbstractHandler.php,v 1.6 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\handler;

use \Httpful\Request;
use \Httpful\Httpful;
use \Httpful\Mime;
use \Httpful\Handlers\JsonHandler;
use base\AbstractJsonSender;
use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use base\ErrorCode;
use base\RESTAuthorization;
use Httpful\Http;
use client\module\moduleUsers;
use client\core\coreSession;

abstract class AbstractHandler extends AbstractJsonSender {
	private $userName;
	private $userPassword;

	protected function __construct() {
		parent::addMapper( 'client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		Httpful::register( Mime::JSON, new JsonHandler( array (
				'decode_as_array' => true
		) ) );
		$coreSession = new coreSession();
		$this->userName = $coreSession->getAttribute( 'user_name' );
		$this->userPassword = $coreSession->getAttribute( 'user_password' );
	}

	abstract protected function getCategory();

	private final function handle_result($result) {
		if (! is_array( $result )) {
			echo '<font color="red"><u>Server Error occured</u><pre>' . $result . '</pre></font><br>';
			add_error( ErrorCode::ATTENTION );
			return false;
		} else if (array_key_exists( 'validationResponse', $result )) {
			$validationResponse = JsonAutoMapper::mapAToB( $result, '\\api\\model\\validation' );
			$validation ['is_valid'] = $validationResponse->getResult();
			$validation ['errors'] = parent::mapArray( $validationResponse->getValidationItemTransport(), ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
			return ($validation);
		} else if (array_key_exists( 'error', $result )) {
			if ($result ['error'] ['code'] < 0) {
				echo '<font color="red"><u>Server Error occured</u><pre>' . $result ['error'] ['message'] . '</pre></font><br>';
				add_error( ErrorCode::ATTENTION );
			}
			add_error( $result ['error'] ['code'] );
			switch ($result ['error'] ['code']) {
				case ErrorCode::LOGGED_OUT :
				case ErrorCode::CLIENT_CLOCK_OFF :
					// FIXME: omg what a hack
					$moduleUsers = new moduleUsers();
					$moduleUsers->logout();
					header( "Location: " . $_SERVER ['PHP_SELF'] );
					break;
			}
			return false;
		}
		return $result;
	}

	private final function getUrl($usecase, $parameter) {
		$url = URLPREFIX . SERVERPREFIX;
		$url .= $this->getCategory();
		$url .= '/';
		$url .= $usecase;
		if (is_array( $parameter ) && count( $parameter ) > 0) {
			$parameterStr = implode( '/', $parameter );
			$url .= '/';
			$url .= $parameterStr;
		}
		return $url;
	}

	private final function getHeaders($httpVerb, $url, $body = null) {
		switch ($httpVerb) {
			case Http::PUT :
			case Http::POST :
				$contentType = \Httpful\Mime::JSON;
				break;
			default :
				$contentType = null;
		}

		$dateStr = gmdate( 'D, d M Y H:i:s' ) . ' GMT';
		$authorization = RESTAuthorization::getRESTAuthorization( $this->userPassword, $httpVerb, $contentType, $url, $dateStr, $body, $this->userName );

		$headers = array (
				'Date' => $dateStr,
				'Authentication' => $authorization
		);
		return $headers;
	}

	protected final function getJson($usecase, $parameter = array()) {
		$url = $this->getUrl( $usecase, $parameter );
		$response = Request::get( $url )->withoutStrictSsl()->addHeaders( $this->getHeaders( Http::GET, $url ) )->send();
		if ($response->code == 204) {
			return false;
		} else {
			return self::handle_result( $response->body );
		}
	}

	// create
	protected final function postJson($usecase, $json, $parameter = array()) {
		$url = $this->getUrl( $usecase, $parameter );
		$response = Request::post( $url )->withoutStrictSsl()->sendsJson()->body( $json )->addHeaders( $this->getHeaders( Http::POST, $url, $json ) )->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}

	// update
	protected final function putJson($usecase, $json, $parameter = array()) {
		$url = $this->getUrl( $usecase, $parameter );
		$response = Request::put( $url )->withoutStrictSsl()->sendsJson()->body( $json )->addHeaders( $this->getHeaders( Http::PUT, $url, $json ) )->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}

	protected final function deleteJson($usecase, $parameter = array()) {
		$url = $this->getUrl( $usecase, $parameter );
		$response = Request::delete( $url )->withoutStrictSsl()->addHeaders( $this->getHeaders( HTTP::DELETE, $url ) )->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}
}
?>