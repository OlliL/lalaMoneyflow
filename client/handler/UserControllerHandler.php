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
// $Id: UserControllerHandler.php,v 1.17 2015/08/01 00:19:23 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
use api\model\user\createUserRequest;
use api\model\user\updateUserRequest;
use api\model\user\getUserSettingsForStartupResponse;
use api\model\user\showUserListResponse;
use api\model\user\showCreateUserResponse;
use api\model\user\showEditUserResponse;
use api\model\user\showDeleteUserResponse;
use api\model\user\createUserResponse;
use api\model\user\updateUserResponse;
use api\model\transport\ValidationItemTransport;
use client\mapper\ArrayToUserTransportMapper;
use api\model\transport\UserTransport;
use client\mapper\ArrayToGroupTransportMapper;
use client\mapper\ArrayToAccessRelationTransportMapper;
use api\model\transport\GroupTransport;
use api\model\transport\AccessRelationTransport;
use base\Singleton;

class UserControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToUserTransportMapper::getClass() );
		parent::addMapper( ArrayToGroupTransportMapper::getClass() );
		parent::addMapper( ArrayToAccessRelationTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'user';
	}

	public final function getUserSettingsForStartup($name) {
		$response = parent::getJson( __FUNCTION__, array (
				$name
		) );
		$result = null;
		if ($response instanceof getUserSettingsForStartupResponse) {
			$result = array (
					'mur_userid' => $response->getUserid(),
					'dateformat' => $response->getSettingDateFormat(),
					'displayed_language' => $response->getSettingDisplayedLanguage(),
					'att_new' => $response->getAttributeNew(),
					'perm_admin' => $response->getPermissionAdmin()
			);
		}
		return $result;
	}

	public final function showUserList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction
		) );
		$result = null;
		if ($response instanceof showUserListResponse) {
			$result ['users'] = parent::mapArrayNullable( $response->getUserTransport() );
			$result ['access_relations'] = parent::mapArrayNullable( $response->getAccessRelationTransport() );
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
			$result ['initials'] = $response->getInitials();
		}

		return $result;
	}

	public final function showCreateUser($id) {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showCreateUserResponse) {
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
		}
		return $result;
	}

	public final function showEditUser($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditUserResponse) {
			$result ['user'] = parent::map( $response->getUserTransport() );
			$result ['access_relations'] = parent::mapArrayNullable( $response->getAccessRelationTransport() );
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
		}
		return $result;
	}

	public final function showDeleteUser($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteUserResponse) {
			$result = parent::map( $response->getUserTransport() );
		}
		return $result;
	}

	public final function createUser(array $user, array $access_relation) {
		$userTransport = parent::map( $user, UserTransport::getClass() );
		$accessRelationTransport = parent::map( $access_relation, AccessRelationTransport::getClass() );

		$request = new createUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof createUserResponse) {
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}
		return $result;
	}

	public final function updateUser(array $user, array $access_relation) {
		$userTransport = parent::map( $user, UserTransport::getClass() );
		$accessRelationTransport = parent::map( $access_relation, AccessRelationTransport::getClass() );

		$request = new updateUserRequest();
		$request->setUserTransport( $userTransport );
		$request->setAccessRelationTransport( $accessRelationTransport );
		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof updateUserResponse) {
			$result ['access_relations'] = parent::mapArrayNullable( $response->getAccessRelationTransport() );
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}

		return $result;
	}

	public final function deleteUserById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>