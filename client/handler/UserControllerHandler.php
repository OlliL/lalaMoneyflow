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
// $Id: UserControllerHandler.php,v 1.2 2014/02/23 12:14:34 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\user\createUserRequest;
use rest\api\model\user\updateUserRequest;

class UserControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToUserTransportMapper', ClientArrayMapperEnum::USER_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new UserControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function getUserById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/getUserById/' . $id . '/' . self::$callServer->getSessionId();
		$result = self::$callServer->getJson( $url );
		if (is_array( $result )) {
			$getUserByIdResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\user' );
			$result = parent::map( $getUserByIdResponse->getUserTransport() );
		}
		return $result;
	}

	public final function showUserList($restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showUserList/' . utf8_encode( $restriction ) . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$listUsers = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			if (is_array( $listUsers->getUserTransport() )) {
				$result ['users'] = parent::mapArray( $listUsers->getUserTransport() );
			} else {
				$result ['users'] = array ();
			}
			$result ['initials'] = $listUsers->getInitials();
		}

		return $result;
	}

	public final function showEditUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showEditUser/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showEditUserResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			$result = parent::map( $showEditUserResponse->getUserTransport() );
		}
		return $result;
	}

	public final function showDeleteUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showDeleteUser/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showDeleteUserResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			$result = parent::map( $showDeleteUserResponse->getUserTransport() );
		}
		return $result;
	}

	public final function createUser(array $user) {
		$url = URLPREFIX . SERVERPREFIX . 'user/createUser/' . self::$callServer->getSessionId();
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );

		$request = new createUserRequest();
		$request->setUserTransport( $userTransport );
		return self::$callServer->postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateUser(array $user) {
		$url = URLPREFIX . SERVERPREFIX . 'user/updateUser/' . self::$callServer->getSessionId();
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );

		$request = new updateUserRequest();
		$request->setUserTransport( $userTransport );
		return self::$callServer->putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/deleteUserById/' . $id . '/' . self::$callServer->getSessionId();
		return self::$callServer->deleteJson( $url );
	}
}

?>