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
// $Id: UserControllerHandler.php,v 1.10 2014/03/01 17:30:21 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\user\createUserRequest;
use api\model\user\updateUserRequest;

class UserControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToUserTransportMapper', ClientArrayMapperEnum::USER_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToGroupTransportMapper', ClientArrayMapperEnum::GROUP_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToAccessRelationTransportMapper', ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new UserControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'user';
	}

	public final function getUserSettingsForStartup($name) {
		$response = parent::getJson( 'getUserSettingsForStartup', array (
				$name
		) );
		if (is_array( $response )) {
			$getUserSettingsForStartup = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
			$result = array (
					'mur_userid' => $getUserSettingsForStartup->getUserid(),
					'dateformat' => $getUserSettingsForStartup->getSettingDateFormat(),
					'displayed_language' => $getUserSettingsForStartup->getSettingDisplayedLanguage(),
					'att_new' => $getUserSettingsForStartup->getAttributeNew(),
					'perm_admin' => $getUserSettingsForStartup->getPermissionAdmin()
			);
		}
		return $result;
	}
	public final function showUserList($restriction) {
		$response = parent::getJson( 'showUserList', array (
				utf8_encode( $restriction )
		) );
		if (is_array( $response )) {
			$listUsers = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
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
		$response = parent::getJson( 'showCreateUser' );
		if (is_array( $response )) {
			$showCreateUserResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
			if (is_array( $showCreateUserResponse->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $showCreateUserResponse->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
		}
		return $result;
	}

	public final function showEditUser($id) {
		$response = parent::getJson( 'showEditUser', array (
				$id
		) );
		if (is_array( $response )) {
			$showEditUserResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
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
		$response = parent::getJson( 'showDeleteUser', array (
				$id
		) );
		if (is_array( $response )) {
			$showDeleteUserResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
			$result = parent::map( $showDeleteUserResponse->getUserTransport() );
		}
		return $result;
	}

	public final function createUser(array $user, array $access_relation) {
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );
		$accessRelationTransport = parent::map( $access_relation, ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );

		$request = new createUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::postJson( 'createUser', parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$createUser = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
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
			$result ['result'] = $createUser->getResult();
		}
		return $result;
	}

	public final function updateUser(array $user, array $access_relation) {
		$userTransport = parent::map( $user, ClientArrayMapperEnum::USER_TRANSPORT );
		$accessRelationTransport = parent::map( $access_relation, ClientArrayMapperEnum::ACCESS_RELATION_TRANSPORT );

		$request = new updateUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::putJson( 'updateUser', parent::json_encode_response( $request ) );
		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$updateUser = JsonAutoMapper::mapAToB( $response, '\\api\\model\\user' );
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
			$result ['result'] = $updateUser->getResult();
		}
		return $result;
	}

	public final function deleteUser($id) {
		return parent::deleteJson( 'deleteUserById', array (
				$id
		) );
	}
}

?>