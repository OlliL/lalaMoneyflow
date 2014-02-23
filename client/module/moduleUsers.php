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
// $Id: moduleUsers.php,v 1.39 2014/02/23 12:14:35 olivleh1 Exp $
//

require_once 'module/module.php';
require_once 'core/coreSession.php';

class moduleUsers extends module {

	function moduleUsers() {
		parent::__construct();
		$this->coreSession = new coreSession();
	}

	function is_logged_in() {
		$this->coreSession->start();
		if (! $this->coreSession->getAttribute( 'users_id' )) {
			return 3;
		} else {
			define( USERID, $this->coreSession->getAttribute( 'users_id' ) );
			$user = $this->coreSession->getAttribute( 'user' );

			// Apache Restart or went cache empty -> sessionId not set;
			if (! $user ['name']) {
				$this->coreSession->destroy();
				return 1;
			}

			if (! $user ['perm_login']) {
				$this->coreSession->destroy();
				add_error( 138 );
				return 1;
			}
			if ($user ['att_new']) {
				return 2;
			} else {
				return 0;
			}
		}
	}

	function display_login_user($realaction, $name, $password, $stay_logged_in, $request_uri) {
		global $GUI_LANGUAGE;
		switch ($realaction) {
			case 'login' :
				if ($stay_logged_in == 'on') {
					session_set_cookie_params( 5184000 );
					session_cache_expire( 86400 );
				}
				$this->coreSession->restart();
				if (empty( $name )) {
					add_error( 139 );
				} elseif (empty( $password )) {
					add_error( 140 );
				}

				$session = SessionControllerHandler::getInstance()->doLogon( $name, sha1( $password ) );
				if ($session) {
					$this->coreSession->setAttribute( 'users_id', $session ['mur_userid'] );
					$this->coreSession->setAttribute( 'server_id', $session ['sessionid'] );
					$this->coreSession->setAttribute( 'date_format', $session ['dateformat'] );
					$this->coreSession->setAttribute( 'gui_language', $session ['displayed_language'] );

					$this->coreSession->start();
					$this->coreSession->setAttribute( 'user', UserControllerHandler::getInstance()->getUserById( $session ['mur_userid'] ) );

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
			$GUI_LANGUAGE = LOGIN_FORM_LANGUAGE;
			$this->template->assign( 'NAME', $name );
			$this->template->assign( 'STAY_LOGGED_IN', $stay_logged_in );
			$this->template->assign( 'ERRORS', $this->get_errors() );
			$this->template->assign( 'REQUEST_URI', $request_uri );
			$this->parse_header( 1 );
			return $this->fetch_template( 'display_login_user.tpl' );
		}
	}

	function logout() {
		$this->coreSession->start();
		$this->coreSession->destroy();
	}

	function is_admin() {
		$user = $this->coreSession->getAttribute( 'user' );
		return $user ['perm_admin'] == "1" ? true : false;
	}

	function display_list_users($letter) {
		$listGroups = UserControllerHandler::getInstance()->showUserList( $letter );
		$all_index_letters = $listGroups ['initials'];
		$all_data = $listGroups ['users'];

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_users.tpl' );
	}

	function display_edit_user($realaction, $userid, $all_data) {
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

				if ($valid_data == true) {
					if ($userid == 0) {
						$ret = UserControllerHandler::getInstance()->createUser( $all_data );
					} else {
						$ret = UserControllerHandler::getInstance()->updateUser( $all_data );
					}

					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
					} else {
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							add_error( $error );

							switch ($error) {
								case ErrorCode::NAME_MUST_NOT_BE_EMPTY :
								case ErrorCode::USER_WITH_SAME_NAME_ALREADY_EXISTS :
									$all_data ['name_error'] = 1;
									break;
							}
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					if ($userid > 0) {
						$all_data = UserControllerHandler::getInstance()->showEditUser( $userid );
					} else {
						$all_data ['perm_login'] = 1;
						$all_data ['att_new'] = 1;
					}
				}
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_user.tpl' );
	}

	function display_delete_user($realaction, $userid) {
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
