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
# $Id: moduleUsers.php,v 1.1 2006/12/19 12:54:13 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreSession.php';
require_once 'core/coreUsers.php';

class moduleUser extends module {

	function moduleUser() {
		$this->module();
		$this->coreSession = new coreSession();
		$this->coreUsers = new coreUsers();
	}

	function is_logged_in() {
		$this->coreSession->start();
		if( !$this->coreSession->getAttribute( 'users_name' ) || !$this->coreSession->getAttribute( 'users_id' ) ) {
			return false;
		} else {
			define( USERID, $this->coreSession->getAttribute( 'users_id' ));
			return true;
		}
	}

	function display_login_user( $realaction, $name, $password ) {

		switch( $realaction ) {
			case 'login':
				if( $id=$this->coreUsers->check_account( $name, $password ) ) {
					$this->coreSession->setAttribute( 'users_name', $name );
					$this->coreSession->setAttribute( 'users_id',   $id );
					$loginok=1;
				} else {
					$this->template->assign( 'NAME',   $name );
					add_error( "username or password not OK" );
				}
				break;
			case 'logout':
				$this->coreSession->destroy();
			default:
				break;
		}

		if( $loginok==1 ) {
			return;
		} else {
			$this->template->assign( 'ERRORS',   get_errors() );
			$this->parse_header( 1 );
			return $this->template->fetch( './display_login_user.tpl' );
		}
	}
}
?>
