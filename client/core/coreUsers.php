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
# $Id: coreUsers.php,v 1.4 2006/12/21 23:09:25 olivleh1 Exp $
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
	
	function set_password( $id, $password ) {
		return $this->update_row( "UPDATE users SET password='".sha1( $password )."' WHERE id=$id" );
	}
	

	function check_login_permission( $id ) {
		if( $this->select_col( "SELECT perm_login FROM users WHERE id=$id" ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function check_admin_permission( $id ) {
		if( $this->select_col( "SELECT perm_admin FROM users WHERE id=$id" ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function check_new_attribute( $id ) {
		if( $this->select_col( "SELECT att_new FROM users WHERE id=$id" ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT id,name,perm_login,perm_admin,att_new FROM users WHERE id!=0 ORDER BY id' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,name,perm_login,perm_admin,att_new FROM users WHERE id=$id AND id!=0 LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( 'SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters FROM users WHERE id!=0 ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT id,name,perm_login,perm_admin,att_new FROM users WHERE UPPER(name) LIKE UPPER('$letter%') AND id!=0 ORDER BY name" );
	}

	function delete_user( $id ) {
		return $this->delete_row( "DELETE FROM users WHERE id=$id LIMIT 1" );
	}

	function add_user( $name, $password, $perm_login, $perm_admin, $att_new ) {
		return $this->insert_row( "INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('$name','".sha1( $password )."',$perm_login,$perm_admin,$att_new);" );
	}

	function update_user( $id, $name, $password1, $perm_login, $perm_admin, $att_new ) {
		return $this->update_row( "UPDATE users SET name='$name',perm_login=$perm_login,perm_admin=$perm_admin,att_new=$att_new WHERE id=$id;" );
	}
}
