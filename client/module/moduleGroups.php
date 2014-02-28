<?php
use client\handler\GroupControllerHandler;
use base\ErrorCode;
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleGroups.php,v 1.9 2014/02/28 22:19:48 olivleh1 Exp $
//

require_once 'module/module.php';

class moduleGroups extends module {

	public final function moduleGroups() {
		parent::__construct();
	}

	public final function display_list_groups($letter) {
		$listGroups = GroupControllerHandler::getInstance()->showGroupList( $letter );
		$all_index_letters = $listGroups ['initials'];
		$all_data = $listGroups ['groups'];

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_groups.tpl' );
	}

	public final function display_edit_group($realaction, $groupid, $all_data) {
		switch ($realaction) {
			case 'save' :
				$all_data ['groupid'] = $groupid;
				if ($groupid == 0)
					$ret = GroupControllerHandler::getInstance()->createGroup( $all_data );
				else
					$ret = GroupControllerHandler::getInstance()->updateGroup( $all_data );

				if ($ret === true) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						add_error( $error );

						switch ($error) {
							case ErrorCode::NAME_MUST_NOT_BE_EMPTY :
							case ErrorCode::GROUP_WITH_SAME_NAME_ALREADY_EXISTS :
								$all_data ['name_error'] = 1;
								break;
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					if ($groupid > 0) {
						$all_data = GroupControllerHandler::getInstance()->showEditGroup( $groupid );
					}
				}
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_group.tpl' );
	}

	public final function display_delete_group($realaction, $groupid, $force) {
		switch ($realaction) {
			case 'yes' :
				if (GroupControllerHandler::getInstance()->deleteGroup( $groupid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($groupid > 0) {
					$all_data = GroupControllerHandler::getInstance()->showDeleteGroup( $groupid );
					if ($all_data) {
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_group.tpl' );
	}
}
?>
