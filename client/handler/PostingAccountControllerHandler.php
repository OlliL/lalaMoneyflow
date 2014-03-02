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
// $Id: PostingAccountControllerHandler.php,v 1.6 2014/03/02 23:42:21 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\postingaccount\createPostingAccountRequest;
use api\model\postingaccount\updatePostingAccountRequest;

class PostingAccountControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
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
		$response = parent::getJson( 'showPostingAccountList', array (
				utf8_encode( $restriction ) 
		) );
		if (is_array( $response )) {
			$listPostingAccounts = JsonAutoMapper::mapAToB( $response, '\\api\\model\\postingaccount' );
			if (is_array( $listPostingAccounts->getPostingAccountTransport() )) {
				$result ['postingAccounts'] = parent::mapArray( $listPostingAccounts->getPostingAccountTransport() );
			} else {
				$result ['postingAccounts'] = array ();
			}
			$result ['initials'] = $listPostingAccounts->getInitials();
		}
		
		return $result;
	}

	public final function showEditPostingAccount($id) {
		$response = parent::getJson( 'showEditPostingAccount', array (
				$id 
		) );
		if (is_array( $response )) {
			$showEditPostingAccountResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\postingaccount' );
			$result = parent::map( $showEditPostingAccountResponse->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function showDeletePostingAccount($id) {
		$response = parent::getJson( 'showDeletePostingAccount', array (
				$id 
		) );
		if (is_array( $response )) {
			$showDeletePostingAccountResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\postingaccount' );
			$result = parent::map( $showDeletePostingAccountResponse->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function createPostingAccount(array $postingAccount) {
		$postingAccountTransport = parent::map( $postingAccount, ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		
		$request = new createPostingAccountRequest();
		$request->setPostingAccountTransport( $postingAccountTransport );
		return parent::postJson( 'createPostingAccount', parent::json_encode_response( $request ) );
	}

	public final function updatePostingAccount(array $postingAccount) {
		$postingAccountTransport = parent::map( $postingAccount, ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		
		$request = new updatePostingAccountRequest();
		$request->setPostingAccountTransport( $postingAccountTransport );
		return parent::putJson( 'updatePostingAccount', parent::json_encode_response( $request ) );
	}

	public final function deletePostingAccount($id) {
		return parent::deleteJson( 'deletePostingAccountById', array (
				$id 
		) );
	}
}

?>