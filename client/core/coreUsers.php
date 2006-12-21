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
# $Id: coreUsers.php,v 1.3 2006/12/21 14:53:37 olivleh1 Exp $
#

require_once 'core/core.php';

class coreUsers extends core {

	function coreUsers() {
		$this->core();
	}

	function check_account( $name, $password ) {
		if ( $id=$this->select_col( "SELECT id FROM users WHERE name='$name' AND password='".sha1( $password )."'" ) ) {
			return $id;
		} else {
			return;
		}
	}
	
	function set_password( $password ) {
		return $this->update_row( "UPDATE users SET password='".sha1( $password )."' WHERE id=".USERID );
	}
	
	function check_permission( $id, $permission ) {
		switch( $permission ) {
			case 'login_allowed':	$perm_bit=1;  break;
			case 'is_admin':	$perm_bit=2;  break;
			default:		return false; break;
		}

		$result_perm = $this->select_col( "SELECT permissions&$perm_bit FROM users WHERE id=$id" );
		
		if( $result_perm == $perm_bit ) {
			return true;
		} else {
			return false;
		}
	}
}
