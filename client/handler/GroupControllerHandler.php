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
// $Id: GroupControllerHandler.php,v 1.12 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\group\updateGroupRequest;
use api\model\group\createGroupRequest;
use api\model\group\showGroupListResponse;
use api\model\group\showEditGroupResponse;
use api\model\group\showDeleteGroupResponse;
use client\mapper\ArrayToGroupTransportMapper;
use api\model\transport\GroupTransport;
use base\Singleton;

class GroupControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToGroupTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'group';
	}

	public final function showGroupList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction
		) );
		$result = null;
		if ($response instanceof showGroupListResponse) {
			$result ['groups'] = parent::mapArrayNullable( $response->getGroupTransport() );
			$result ['initials'] = $response->getInitials();
		}

		return $result;
	}

	public final function showEditGroup($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditGroupResponse) {
			$result = parent::map( $response->getGroupTransport() );
		}
		return $result;
	}

	public final function showDeleteGroup($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeleteGroupResponse) {
			$result = parent::map( $response->getGroupTransport() );
		}
		return $result;
	}

	public final function createGroup(array $group) {
		$groupTransport = parent::map( $group, GroupTransport::getClass() );

		$request = new createGroupRequest();
		$request->setGroupTransport( $groupTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function updateGroup(array $group) {
		$groupTransport = parent::map( $group, GroupTransport::getClass() );

		$request = new updateGroupRequest();
		$request->setGroupTransport( $groupTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteGroupById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>