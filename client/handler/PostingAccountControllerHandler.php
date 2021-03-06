<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: PostingAccountControllerHandler.php,v 1.16 2016/08/26 22:32:05 olivleh1 Exp $
//
namespace client\handler;

use api\model\postingaccount\createPostingAccountRequest;
use api\model\postingaccount\updatePostingAccountRequest;
use api\model\postingaccount\showPostingAccountListResponse;
use api\model\postingaccount\showEditPostingAccountResponse;
use api\model\postingaccount\showDeletePostingAccountResponse;
use client\mapper\ArrayToPostingAccountTransportMapper;
use client\mapper\ArrayToPostingAccountAmountTransportMapper;
use api\model\transport\PostingAccountTransport;
use base\Singleton;
use api\model\postingaccount\createPostingAccountResponse;

class PostingAccountControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountAmountTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'postingaccount';
	}

	public final function showPostingAccountList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction
		) );
		$result = null;
		if ($response instanceof showPostingAccountListResponse) {
			$result ['postingAccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['initials'] = $response->getInitials();
		}

		return $result;
	}

	public final function showEditPostingAccount($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showEditPostingAccountResponse) {
			$result = parent::map( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function showDeletePostingAccount($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showDeletePostingAccountResponse) {
			$result = parent::map( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function createPostingAccount(array $postingAccount) {
		$postingAccountTransport = parent::map( $postingAccount, PostingAccountTransport::getClass() );

		$request = new createPostingAccountRequest();
		$request->setPostingAccountTransport( $postingAccountTransport );

		$response = parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );

		$result = null;
		if ($response === true) {
			$result = true;
		} else if ($response instanceof createPostingAccountResponse) {
			if($response->getPostingAccountId() != null) {
				$result ['postingaccountid'] = $response->getPostingAccountId();
			} else {
				$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
				$result ['result'] = $response->getResult();
			}
		}

		return $result;
	}


	public final function updatePostingAccount(array $postingAccount) {
		$postingAccountTransport = parent::map( $postingAccount, PostingAccountTransport::getClass() );

		$request = new updatePostingAccountRequest();
		$request->setPostingAccountTransport( $postingAccountTransport );
		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deletePostingAccountById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}
}

?>
