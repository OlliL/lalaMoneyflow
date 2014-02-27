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
// $Id: UserControllerHandler.php,v 1.6 2014/02/27 19:31:01 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\user\createUserRequest;
use rest\api\model\user\updateUserRequest;

class UserControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToUserTransportMapper', ClientArrayMapperEnum::USER_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToGroupTransportMapper', ClientArrayMapperEnum::GROUP_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToAccessRelationTransportMapper', ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new UserControllerHandler();
		}
		return self::$instance;
	}

	public final function showUserList($restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showUserList/' . utf8_encode( $restriction ) . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$listUsers = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			if (is_array( $listUsers->getUserTransport() )) {
				$result ['users'] = parent::mapArray( $listUsers->getUserTransport() );
			} else {
				$result ['users'] = array ();
			}
			if (is_array( $listUsers->getAccessRelationTransport() )) {
				$result ['access_relations'] = parent::mapArray( $listUsers->getAccessRelationTransport() );
			} else {
				$result ['access_relations'] = array ();
			}
			if (is_array( $listUsers->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $listUsers->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
			$result ['initials'] = $listUsers->getInitials();
		}

		return $result;
	}

	public final function showCreateUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showCreateUser/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showCreateUserResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			if (is_array( $showCreateUserResponse->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $showCreateUserResponse->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
		}
		return $result;
	}

	public final function showEditUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showEditUser/' . $id . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showEditUserResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			$result ['user'] = parent::map( $showEditUserResponse->getUserTransport() );
			if (is_array( $showEditUserResponse->getAccessRelationTransport() )) {
				$result ['access_relations'] = parent::mapArray( $showEditUserResponse->getAccessRelationTransport() );
			} else {
				$result ['access_relations'] = array ();
			}
			if (is_array( $showEditUserResponse->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $showEditUserResponse->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
		}
		return $result;
	}

	public final function showDeleteUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/showDeleteUser/' . $id . '/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showDeleteUserResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			$result = parent::map( $showDeleteUserResponse->getUserTransport() );
		}
		return $result;
	}

	public final function createUser(array $user, array $access_relation) {
		$url = URLPREFIX . SERVERPREFIX . 'user/createUser/' . parent::getSessionId();
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );
		$accessRelationTransport = parent::map( $access_relation, ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );

		$request = new createUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::postJson( $url, parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$createUser = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			if (is_array( $createUser->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $createUser->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
			if (is_array( $createUser->getValidationItemTransport() )) {
				$result ['errors'] = $response ['createUserResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] == $createUser->getResult();
		}
		return $result;
	}

	public final function updateUser(array $user, array $access_relation) {
		$url = URLPREFIX . SERVERPREFIX . 'user/updateUser/' . parent::getSessionId();
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );
		$accessRelationTransport = parent::map( $access_relation, ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );

		$request = new updateUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::putJson( $url, parent::json_encode_response( $request ) );
		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$updateUser = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\user' );
			if (is_array( $updateUser->getAccessRelationTransport() )) {
				$result ['access_relations'] = parent::mapArray( $updateUser->getAccessRelationTransport() );
			} else {
				$result ['access_relations'] = array ();
			}
			if (is_array( $updateUser->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $updateUser->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
			if (is_array( $updateUser->getValidationItemTransport() )) {
				$result ['errors'] = $response ['updateUserResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] == $updateUser->getResult();
		}
		return $result;
	}

	public final function deleteUser($id) {
		$url = URLPREFIX . SERVERPREFIX . 'user/deleteUserById/' . $id . '/' . parent::getSessionId();
		return parent::deleteJson( $url );
	}
}

?>