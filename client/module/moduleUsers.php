<?php
#-
# Copyright (c) 2006 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: moduleUsers.php,v 1.16 2007/07/25 16:03:38 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreSession.php';
require_once 'core/coreUsers.php';

class moduleUsers extends module {

	function moduleUsers() {
		$this->module();
		$this->coreSession = new coreSession();
		$this->coreUsers = new coreUsers();
	}

	function is_logged_in() {
		$this->coreSession->start();
		if( !$this->coreSession->getAttribute( 'users_name' )
		 || !$this->coreSession->getAttribute( 'users_id' ) ) {
			return 3;
		} else {
			define( USERID, $this->coreSession->getAttribute( 'users_id' ));
			if( !$this->coreUsers->check_login_permission( USERID ) ) {
				$this->coreSession->destroy();
				add_error( 20 );
				return 1;
			} if( $this->coreUsers->check_new_attribute( USERID ) ) {
				return 2;
			} else {
				return 0;
			}
		}
	}

	function display_login_user( $realaction, $name, $password, $stay_logged_in, $request_uri ) {

		switch( $realaction ) {
			case 'login':
				if( $stay_logged_in == 'on' ) {
					session_set_cookie_params(5184000);
					session_cache_expire(86400);
				}
				$this->coreSession->restart();
				if( empty( $name ) ) {
					add_error( 21 );
				} elseif( empty( $password ) ) {
					add_error( 22 );
				} elseif( $id = $this->coreUsers->check_account( $name, $password ) ) {
					if( $this->coreUsers->check_login_permission( $id ) ) {
						$this->coreSession->setAttribute( 'users_name', $name );
						$this->coreSession->setAttribute( 'users_id',   $id );
						$loginok=1;
					} else {
						add_error( 20 );
					}
				} else {
					add_error( 16 );
				}
				break;
			default:
				break;
		}

		if( $loginok == 1 ) {
			return;
		} else {
			define( USERID, 0 );
			$this->template->assign( 'NAME',           $name );
			$this->template->assign( 'STAY_LOGGED_IN', $stay_logged_in );
			$this->template->assign( 'ERRORS',         $this->get_errors() );
			$this->template->assign( 'REQUEST_URI',    $request_uri );
			$this->parse_header( 1 );
			return $this->fetch_template( 'display_login_user.tpl' );
		}
	}

	function logout() {
		$this->coreSession->start();
		$this->coreSession->destroy();
	}


	function is_admin() {
		return $this->coreUsers->check_admin_permission( USERID );
	}

	function display_list_users( $letter ) {

		$all_index_letters = $this->coreUsers->get_all_index_letters();
		$num_users = $this->coreUsers->count_all_data();
		
		if( empty($letter) && $num_users < MAX_ROWS ) {
			$letter = 'all';
		}
		
		if( $letter == 'all') {
			$all_data=$this->coreUsers->get_all_data();
		} elseif( !empty( $letter ) ) {
			$all_data=$this->coreUsers->get_all_matched_data( $letter );
		} else {
			$all_data=array();
		}
		
		$this->template->assign( 'ALL_DATA',          $all_data          );
		$this->template->assign( 'COUNT_ALL_DATA',    count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_users.tpl' );
	}

	function display_edit_user( $realaction, $id, $all_data ) {

		switch( $realaction ) {
			case 'save':
				if( $all_data['password1'] != $all_data['password2'] ) {
					add_error( 19 );
				} elseif( $id == 0 ) {
					if( empty( $all_data['password1']) ) {
						add_error( 22 );
					} else {
						$ret=$this->coreUsers->add_user( $all_data['name'], $all_data['password1'], $all_data['perm_login'], $all_data['perm_admin'], $all_data['att_new'] );
					}
				} else {
					$ret=$this->coreUsers->update_user( $id, $all_data['name'], $all_data['perm_login'], $all_data['perm_admin'], $all_data['att_new'] );
					if( !empty( $all_data['password1'] ) && $ret ) {
						$ret=$this->coreUsers->set_password( $id, $all_data['password1'] );
					}
				}

				if( $ret ) {
					$this->template->assign( 'CLOSE',    1 );
				} else {
					$this->template->assign( 'ALL_DATA', $all_data );
				}				
				break;
			default:
				if( $id > 0 ) {
					$all_data=$this->coreUsers->get_id_data( $id );
				} else {
					$all_data['perm_login']=1;
					$all_data['att_new']=1;
				}
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_user.tpl' );
	}

	function display_delete_user( $realaction, $id, $force ) {

		switch( $realaction ) {
			case 'yes':
				if( empty($force) && $this->coreUsers->user_owns_data( $id ) ) {
					add_error( 33 );
					$this->template->assign( 'ASK', 1 );
				} else {
					if( $this->coreUsers->delete_user( $id ) ) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						add_error( 35 );
					}
				}
			default:
				$all_data=$this->coreUsers->get_id_data( $id );
				$this->template->assign( 'ALL_DATA', $all_data );
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_user.tpl' );
	}
}
?>
