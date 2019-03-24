<?php
//
// Copyright (c) 2006-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: moduleGroups.php,v 1.14 2015/02/13 00:03:37 olivleh1 Exp $
//
namespace client\module;

use client\handler\GroupControllerHandler;
use base\ErrorCode;

class moduleGroups extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_list_groups($letter) {
		$listGroups = GroupControllerHandler::getInstance()->showGroupList( $letter );
		$all_index_letters = $listGroups ['initials'];
		$all_data = $listGroups ['groups'];

		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template_assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_groups.tpl' );
	}

	public final function display_edit_group($realaction, $groupid, $all_data) {
		$close = 0;
		switch ($realaction) {
			case 'save' :
				$all_data ['groupid'] = $groupid;
				if ($groupid == 0)
					$ret = GroupControllerHandler::getInstance()->createGroup( $all_data );
				else
					$ret = GroupControllerHandler::getInstance()->updateGroup( $all_data );

				if ($ret === true) {
					$close = 1;
				} else {
					foreach ( $ret ['errors'] as $validationResult ) {
						$error = $validationResult ['error'];

						$this->add_error( $error );

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
					} else {
						$all_data ['name'] = '';
					}
				}
				break;
		}

		$this->template_assign( 'CLOSE', $close );
		$this->template_assign( 'GROUPID', $groupid );
		$this->template_assign( 'ALL_DATA', $all_data );
		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_group.tpl' );
	}

	public final function display_delete_group($realaction, $groupid) {
		switch ($realaction) {
			case 'yes' :
				if (GroupControllerHandler::getInstance()->deleteGroupById( $groupid )) {
					$this->template_assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($groupid > 0) {
					$all_data = GroupControllerHandler::getInstance()->showDeleteGroup( $groupid );
					if ($all_data) {
						$this->template_assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template_assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_group.tpl' );
	}
}
?>
