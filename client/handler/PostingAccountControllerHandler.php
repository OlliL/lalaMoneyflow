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
// $Id: PostingAccountControllerHandler.php,v 1.11 2014/03/13 21:36:42 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
use api\model\postingaccount\createPostingAccountRequest;
use api\model\postingaccount\updatePostingAccountRequest;
use api\model\postingaccount\plotPostingAccountsResponse;
use api\model\postingaccount\showPostingAccountListResponse;
use api\model\postingaccount\showEditPostingAccountResponse;
use api\model\postingaccount\showDeletePostingAccountResponse;
use client\mapper\ArrayToPostingAccountTransportMapper;
use client\mapper\ArrayToPostingAccountAmountTransportMapper;
use api\model\transport\PostingAccountTransport;

class PostingAccountControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountAmountTransportMapper::getClass() );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new PostingAccountControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'postingaccount';
	}

	public final function showPostingAccountList($restriction) {
		$response = parent::getJson( __FUNCTION__, array (
				$restriction
		) );
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
		if ($response instanceof showEditPostingAccountResponse) {
			$result = parent::map( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function showDeletePostingAccount($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		if ($response instanceof showDeletePostingAccountResponse) {
			$result = parent::map( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function createPostingAccount(array $postingAccount) {
		$postingAccountTransport = parent::map( $postingAccount, PostingAccountTransport::getClass() );

		$request = new createPostingAccountRequest();
		$request->setPostingAccountTransport( $postingAccountTransport );
		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
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