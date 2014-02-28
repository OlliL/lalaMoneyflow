<?php
use rest\client\handler\SessionControllerHandler;
use rest\client\handler\UserControllerHandler;
use rest\base\ErrorCode;
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
// $Id: moduleUsers.php,v 1.45 2014/02/28 17:04:59 olivleh1 Exp $
//

require_once 'module/module.php';
require_once 'core/coreSession.php';

class moduleUsers extends module {

	public final function moduleUsers() {
		parent::__construct();
		$this->coreSession = new coreSession();
	}

	public final function is_logged_in() {
		$this->coreSession->start();
		if (! $this->coreSession->getAttribute( 'users_id' )) {
			return 3;
		} else {
			define( USERID, $this->coreSession->getAttribute( 'users_id' ) );

			if ($this->coreSession->getAttribute( 'att_new' )) {
				return 2;
			} else {
				return 0;
			}
		}
	}

	public final function display_login_user($realaction, $name, $password, $stay_logged_in, $request_uri) {
		switch ($realaction) {
			case 'login' :
				if ($stay_logged_in == 'on') {
					session_set_cookie_params( 5184000 );
					session_cache_expire( 86400 );
				}
				$this->coreSession->restart();
				if (empty( $name )) {
					add_error( ErrorCode::USERNAME_IS_MANDATORY );
				} elseif (empty( $password )) {
					add_error( ErrorCode::PASSWORD_EMPTY );
				}

				$session = SessionControllerHandler::getInstance()->doLogon( $name, sha1( $password ) );
				if ($session) {
					$this->coreSession->setAttribute( 'user_name', $name );
					$this->coreSession->setAttribute( 'user_password', sha1($password ));
					$this->coreSession->setAttribute( 'users_id', $session ['mur_userid'] );
					$this->coreSession->setAttribute( 'date_format', $session ['dateformat'] );
					$this->coreSession->setAttribute( 'gui_language', $session ['displayed_language'] );
					$this->coreSession->setAttribute( 'att_new', $session ['att_new'] );
					$this->coreSession->setAttribute( 'perm_admin', $session ['perm_admin'] );

					$this->coreSession->start();
					$loginok = 1;
				}
				break;
			default :
				break;
		}

		if ($loginok == 1) {
			return;
		} else {
			define( USERID, 0 );
			parent::setGuiLanguage(LOGIN_FORM_LANGUAGE);
			$this->template->assign( 'NAME', $name );
			$this->template->assign( 'STAY_LOGGED_IN', $stay_logged_in );
			$this->template->assign( 'ERRORS', $this->get_errors() );
			$this->template->assign( 'REQUEST_URI', $request_uri );
			$this->parse_header( 1 );
			return $this->fetch_template( 'display_login_user.tpl' );
		}
	}

	public final function logout() {
		$this->coreSession->start();
		$this->coreSession->destroy();
	}

	public final function is_admin() {
		$perm_admin = $this->coreSession->getAttribute( 'perm_admin' );
		return $perm_admin ? true : false;
	}

	public final function display_list_users($letter) {
		$listUsers = UserControllerHandler::getInstance()->showUserList( $letter );
		$all_index_letters = $listUsers ['initials'];
		$all_data = $listUsers ['users'];
		$access_relations = $listUsers ['access_relations'];
		$groups = $listUsers ['groups'];

		foreach ( $all_data as $key => $user ) {
			$userid = $user ['userid'];
			$groupid = null;
			foreach ( $access_relations as $relation ) {
				if ($relation ['id'] == $userid) {
					$groupid = $relation ['ref_id'];
					break;
				}
			}
			if ($groupid) {
				foreach ( $groups as $group ) {
					if ($group ['groupid'] == $groupid) {
						$all_data [$key] ['group'] = $group ['name'];
						break;
					}
				}
			}
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_users.tpl' );
	}

	public final function display_edit_user($realaction, $userid, $all_data, $access_relation) {
		switch ($realaction) {
			case 'save' :
				$all_data ['userid'] = $userid;
				$valid_data = true;
				if ($all_data ['password'] != $all_data ['password2']) {
					add_error( ErrorCode::PASSWORD_NOT_MATCHING );
					$valid_data = false;
				} elseif ($userid == 0) {
					if (empty( $all_data ['password'] )) {
						add_error( ErrorCode::PASSWORD_EMPTY );
						$valid_data = false;
					}
				}
				if ($userid > 0) {
					if (! dateIsValid( $access_relation ['validfrom'] )) {
						add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
								GUI_DATE_FORMAT
						) );
						$access_relation ['validfrom_error'] = 1;
						$valid_data = false;
					}
				}

				if ($valid_data == true) {
					if ($userid == 0) {
						$access_relation ['validfrom'] = convert_date_to_gui( date( 'Y-m-d' ) );
						$ret = UserControllerHandler::getInstance()->createUser( $all_data, $access_relation );
					} else {
						$access_relation ['id'] = $userid;
						$ret = UserControllerHandler::getInstance()->updateUser( $all_data, $access_relation );
					}

					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
					} else {
						$access_relations = $ret ['access_relations'];
						$groups = $ret ['groups'];
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							add_error( $error );

							switch ($error) {
								case ErrorCode::NAME_MUST_NOT_BE_EMPTY :
								case ErrorCode::USER_WITH_SAME_NAME_ALREADY_EXISTS :
									$all_data ['name_error'] = 1;
									break;
								case ErrorCode::VALIDFROM_EARLIER_THAN_TOMORROW :
									$access_relation ['validfrom_error'] = 1;
									break;
							}
						}
					}
					break;
				}
			default :
				if ($userid > 0) {
					$showEditUser = UserControllerHandler::getInstance()->showEditUser( $userid );
					$all_data_pre = $showEditUser ['user'];
					$access_relations = $showEditUser ['access_relations'];
					$groups = $showEditUser ['groups'];
				} else {
					$showCreateUser = UserControllerHandler::getInstance()->showCreateUser( $userid );
					$groups = $showCreateUser ['groups'];
					$all_data_pre ['perm_login'] = 1;
					$all_data_pre ['att_new'] = 1;
				}
				if (! is_array( $all_data )) {
					$all_data = $all_data_pre;
				}
				break;
		}

		if (is_array( $groups ) && is_array( $access_relations )) {
			foreach ( $groups as $group ) {
				$groupById [$group ['groupid']] = $group ['name'];
			}

			foreach ( $access_relations as $key => $relation ) {
				$access_relations [$key] ['name'] = $groupById [$relation ['ref_id']];
				$sort [$key] = $relation ['validfrom_sort'];
			}

			if (is_array( $sort ))
				array_multisort( $sort, SORT_DESC, $access_relations );

			if (! is_array( $access_relation ) || ! array_key_exists( 'ref_id', $access_relation )) {
				$access_relation ['ref_id'] = $access_relations [0] ['ref_id'];
				$access_relation ['validfrom'] = convert_date_to_gui( date( 'Y-m-d', time() + 86400 ) );
			}
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ACCESS_RELATION', $access_relation );
		$this->template->assign( 'ACCESS_RELATIONS', $access_relations );
		$this->template->assign( 'GROUPS', $groups );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_user.tpl' );
	}

	public final function display_delete_user($realaction, $userid) {
		switch ($realaction) {
			case 'yes' :
				if (UserControllerHandler::getInstance()->deleteUser( $userid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($userid > 0) {
					$all_data = UserControllerHandler::getInstance()->showDeleteUser( $userid );
					if ($all_data) {
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_user.tpl' );
	}
}
?>
