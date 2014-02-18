<?php
use rest\client\handler\SessionControllerHandler;
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleUsers.php,v 1.36 2014/02/18 19:21:41 olivleh1 Exp $
//

require_once 'module/module.php';
require_once 'core/coreSession.php';
require_once 'core/coreUsers.php';
require_once 'core/coreSettings.php';

class moduleUsers extends module {

	function moduleUsers() {
		parent::__construct();
		$this->coreSession = new coreSession();
		$this->coreUsers = new coreUsers();
		$this->coreSettings = new coreSettings();
	}

	// uses REST Service
	function is_logged_in() {
		$this->coreSession->start();
		if (! $this->coreSession->getAttribute( 'users_id' )) {
			return 3;
		} else {
			define( USERID, $this->coreSession->getAttribute( 'users_id' ) );
			$user = LoggedOnUser::getInstance()->getUser();

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

	// uses REST Service
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
			$GUI_LANGUAGE = $this->coreSettings->get_displayed_language( USERID );
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
		$user = LoggedOnUser::getInstance()->getUser();
		return $user ['perm_admin'] == "1" ? true : false;
	}

	function display_list_users($letter) {
		$all_index_letters = $this->coreUsers->get_all_index_letters();
		$num_users = $this->coreUsers->count_all_data();

		if (empty( $letter ) && $num_users < $this->coreSettings->get_max_rows( USERID )) {
			$letter = 'all';
		}

		if ($letter == 'all') {
			$all_data = $this->coreUsers->get_all_data();
		} elseif (! empty( $letter )) {
			$all_data = $this->coreUsers->get_all_matched_data( $letter );
		} else {
			$all_data = array ();
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_users.tpl' );
	}

	function display_edit_user($realaction, $id, $all_data) {
		switch ($realaction) {
			case 'save' :
				if ($all_data ['password1'] != $all_data ['password2']) {
					add_error( 137 );
				} elseif ($id == 0) {
					if (empty( $all_data ['password1'] )) {
						add_error( 140 );
					} else {
						$ret = $this->coreUsers->add_user( $all_data ['name'], $all_data ['password1'], $all_data ['perm_login'], $all_data ['perm_admin'], $all_data ['att_new'] );
						if ($ret > 0) {
							$coreSettings = new coreSettings();
							if (! $this->coreSettings->init_settings( $ret )) {
								$this->coreUsers->delete_user( $ret );
								$ret = false;
							}
						}
					}
				} else {
					$ret = $this->coreUsers->update_user( $id, $all_data ['name'], $all_data ['perm_login'], $all_data ['perm_admin'], $all_data ['att_new'] );
					if (! empty( $all_data ['password1'] ) && $ret) {
						$ret = $this->coreUsers->set_password( $id, $all_data ['password1'] );
					}
				}

				if ($ret) {
					$this->template->assign( 'CLOSE', 1 );
				} else {
					$this->template->assign( 'ALL_DATA', $all_data );
				}
				break;
			default :
				if ($id > 0) {
					$all_data = $this->coreUsers->get_id_data( $id );
				} else {
					$all_data ['perm_login'] = 1;
					$all_data ['att_new'] = 1;
				}
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_user.tpl' );
	}

	function display_delete_user($realaction, $id, $force) {
		switch ($realaction) {
			case 'yes' :
				if (empty( $force ) && $this->coreUsers->user_owns_data( $id )) {
					add_error( 151 );
					$this->template->assign( 'ASK', 1 );
				} else {
					if ($this->coreUsers->delete_user( $id )) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						add_error( 153 );
					}
				}
			default :
				$all_data = $this->coreUsers->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_user.tpl' );
	}
}
?>
