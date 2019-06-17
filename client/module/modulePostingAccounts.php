<?php

//
// Copyright (c) 2006-2019 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: modulePostingAccounts.php,v 1.8 2015/02/13 00:03:37 olivleh1 Exp $
//
namespace client\module;

use client\handler\PostingAccountControllerHandler;

class modulePostingAccounts extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_postingAccounts($letter) {
		$listPostingAccounts = PostingAccountControllerHandler::getInstance()->showPostingAccountList( $letter );
		$all_index_letters = $listPostingAccounts ['initials'];
		$all_data = $listPostingAccounts ['postingAccounts'];

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_postingaccounts.tpl' );
	}

	public final function display_edit_postingAccount($postingaccountid) {
		if ($postingaccountid > 0) {
			$all_data = PostingAccountControllerHandler::getInstance()->showEditPostingAccount( $postingaccountid );
		} else {
			$all_data = array ();
		}

		$this->template_assign( 'POSTINGACCOUNTID', $postingaccountid );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', json_encode( $all_data ) );

		$this->parse_header_without_embedded( 1, 'display_edit_postingaccount.tpl' );
		return $this->fetch_template( 'display_edit_postingaccount_bs.tpl' );
	}

	public final function edit_postingAccount($postingaccountid, $all_data) {
		$all_data ['postingaccountid'] = $postingaccountid;

		if ($postingaccountid == 0)
			$ret = PostingAccountControllerHandler::getInstance()->createPostingAccount( $all_data );
		else
			$ret = PostingAccountControllerHandler::getInstance()->updatePostingAccount( $all_data );

		return $this->handleReturnForAjax( $ret );
	}

	public final function display_delete_postingAccount($realaction, $postingaccountid) {
		switch ($realaction) {
			case 'yes' :
				if (PostingAccountControllerHandler::getInstance()->deletePostingAccountById( $postingaccountid )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($postingaccountid > 0) {
					$all_data = PostingAccountControllerHandler::getInstance()->showDeletePostingAccount( $postingaccountid );
					if ($all_data) {
						$this->template_assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_postingaccount.tpl' );
	}
}
?>
