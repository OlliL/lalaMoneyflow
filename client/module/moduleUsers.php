<?php
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
// $Id: moduleUsers.php,v 1.52 2014/03/01 20:46:42 olivleh1 Exp $
//
namespace client\module;

use client\handler\UserControllerHandler;
use base\ErrorCode;
use client\util\Environment;

class moduleUsers extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function is_logged_in() {
		$userId = Environment::getInstance()->getUserId();
		if (! $userId) {
			return 3;
		} else {
			define( USERID, $userId );

			if (Environment::getInstance()->getUserAttNew()) {
				return 2;
			} else {
				return 0;
			}
		}
	}

	public final function display_login_user($realaction, $name, $password, $request_uri) {
		switch ($realaction) {
			case 'login' :
				if (empty( $name )) {
					$this->add_error( ErrorCode::USERNAME_IS_MANDATORY );
				} elseif (empty( $password )) {
					$this->add_error( ErrorCode::PASSWORD_EMPTY );
				}

				Environment::getInstance()->setUserName( $name );
				Environment::getInstance()->setUserPassword( sha1( $password ) );
				$session = UserControllerHandler::getInstance()->getUserSettingsForStartup( $name );
				if ($session) {
					Environment::getInstance()->setUserId( $session ['mur_userid'] );
					Environment::getInstance()->setSettingDateFormat( $session ['dateformat'] );
					Environment::getInstance()->setSettingGuiLanguage( $session ['displayed_language'] );
					Environment::getInstance()->setUserAttNew( $session ['att_new'] );
					Environment::getInstance()->setUserPermAdmin( $session ['perm_admin'] );

					$loginok = 1;
				}
				break;
			default :
				break;
		}

		if ($loginok == 1) {
			return;
		} else {
			Environment::getInstance()->setSettingGuiLanguage( LOGIN_FORM_LANGUAGE );
			$this->template->assign( 'NAME', $name );
			$this->template->assign( 'STAY_LOGGED_IN', $stay_logged_in );
			$this->template->assign( 'ERRORS', $this->get_errors() );
			$this->template->assign( 'REQUEST_URI', $request_uri );
			$this->parse_header( 1 );
			return $this->fetch_template( 'display_login_user.tpl' );
		}
	}

	public final function logout() {
		// destroy the existing session and start a new one (important as the app expectes always a running session)
		session_destroy();
		session_start();
	}

	public final function is_admin() {
		$perm_admin = Environment::getInstance()->getUserPermAdmin();
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
		$close = 0;
		$access_relations = null;
		switch ($realaction) {
			case 'save' :
				$all_data ['userid'] = $userid;
				$access_relation ['validfrom_error'] = 0;
				$valid_data = true;
				if ($all_data ['password'] != $all_data ['password2']) {
					$this->add_error( ErrorCode::PASSWORD_NOT_MATCHING );
					$valid_data = false;
				} elseif ($userid == 0) {
					if (empty( $all_data ['password'] )) {
						$this->add_error( ErrorCode::PASSWORD_EMPTY );
						$valid_data = false;
					}
				}
				if ($userid > 0) {
					if (! $this->dateIsValid( $access_relation ['validfrom'] )) {
						$this->add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
								Environment::getInstance()->getSettingDateFormat()
						) );
						$access_relation ['validfrom_error'] = 1;
						$valid_data = false;
					}
				}

				if ($valid_data == true) {
					if ($userid == 0) {
						$access_relation ['validfrom'] = $this->convertDateToGui( date( 'Y-m-d' ) );
						$ret = UserControllerHandler::getInstance()->createUser( $all_data, $access_relation );
					} else {
						$access_relation ['id'] = $userid;
						$ret = UserControllerHandler::getInstance()->updateUser( $all_data, $access_relation );
					}

					if ($ret === true) {
						$close = 1;
					} else {
						$access_relations = $ret ['access_relations'];
						$groups = $ret ['groups'];
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							$this->add_error( $error );

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
					$access_relation ['validfrom_error'] = 0;
				} else {
					$showCreateUser = UserControllerHandler::getInstance()->showCreateUser( $userid );
					$groups = $showCreateUser ['groups'];
					$all_data_pre = array (
							'name' => '',
							'password' => '',
							'password2' => '',
							'perm_login' => 1,
							'perm_admin' => 0,
							'att_new' => 1,
							'ref_id' => ''
					);
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
				$access_relation ['validfrom'] = $this->convertDateToGui( date( 'Y-m-d', time() + 86400 ) );
			}
		} else {
			$access_relation = array (
					'ref_id' => ''
			);
		}

		$this->template->assign( 'CLOSE', $close );
		$this->template->assign( 'USERID', $userid );
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
