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
// $Id: GroupControllerHandler.php,v 1.3 2014/02/27 20:02:14 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\group\updateGroupRequest;
use rest\api\model\group\createGroupRequest;

class GroupControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'rest\client\mapper\ArrayToGroupTransportMapper', ClientArrayMapperEnum::GROUP_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new GroupControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'group';
	}

	public final function showGroupList($restriction) {
		$response = parent::getJson( 'showGroupList', array (
				utf8_encode( $restriction )
		) );
		if (is_array( $response )) {
			$listGroups = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\group' );
			if (is_array( $listGroups->getGroupTransport() )) {
				$result ['groups'] = parent::mapArray( $listGroups->getGroupTransport() );
			} else {
				$result ['groups'] = array ();
			}
			$result ['initials'] = $listGroups->getInitials();
		}

		return $result;
	}

	public final function showEditGroup($id) {
		$response = parent::getJson( 'showEditGroup', array (
				$id
		) );
		if (is_array( $response )) {
			$showEditGroupResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\group' );
			$result = parent::map( $showEditGroupResponse->getGroupTransport() );
		}
		return $result;
	}

	public final function showDeleteGroup($id) {
		$response = parent::getJson( 'showDeleteGroup', array (
				$id
		) );
		if (is_array( $response )) {
			$showDeleteGroupResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\group' );
			$result = parent::map( $showDeleteGroupResponse->getGroupTransport() );
		}
		return $result;
	}

	public final function createGroup(array $group) {
		$groupTransport = parent::map( $group, ClientArrayMapperEnum::GROUP_TRANSPORT );

		$request = new createGroupRequest();
		$request->setGroupTransport( $groupTransport );
		return parent::postJson( 'createGroup', parent::json_encode_response( $request ) );
	}

	public final function updateGroup(array $group) {
		$groupTransport = parent::map( $group, ClientArrayMapperEnum::GROUP_TRANSPORT );

		$request = new updateGroupRequest();
		$request->setGroupTransport( $groupTransport );
		return parent::putJson( 'updateGroup', parent::json_encode_response( $request ) );
	}

	public final function deleteGroup($id) {
		return parent::deleteJson( 'deleteGroupById', array (
				$id
		) );
	}
}

?>