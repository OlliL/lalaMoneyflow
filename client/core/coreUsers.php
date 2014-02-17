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
// $Id: coreUsers.php,v 1.20 2014/02/17 20:54:27 olivleh1 Exp $
//
require_once 'core/core.php';

class coreUsers extends core {

	function coreUsers() {
		parent::__construct();
	}

	function set_password( $id, $password ) {
		LoggedOnUser::getInstance()->destroyInstance($id);
		return $this->update_row( "	UPDATE users
						   SET password = '".sha1( $password )."'
						      ,att_new  = 0
						 WHERE userid = $id" );
	}



	function count_all_data() {
		if ( $num=$this->select_col( 'SELECT count(*) FROM users' ) ) {
			return $num;
		} else {
			return;
		}
	}

	function get_all_data() {
		return $this->select_rows( '	SELECT userid
						      ,name
						      ,perm_login
						      ,perm_admin
						      ,att_new
						  FROM users
						 WHERE userid != 0
						 ORDER BY userid' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT userid
						      ,name
						      ,perm_login
						      ,perm_admin
						      ,att_new
						  FROM users
						 WHERE userid  = $id
						   AND userid != 0
						 LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( '	SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters
						  FROM users
						 WHERE userid != 0
						 ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "	SELECT userid
						      ,name
						      ,perm_login
						      ,perm_admin
						      ,att_new
						  FROM users
						 WHERE UPPER(name) LIKE UPPER('$letter%')
						   AND userid	!= 0
						 ORDER BY name" );
	}

	function user_owns_data( $id ) {

		$ret = $this->exec_function( "user_owns_data( $id )" );

		if( $ret == 0 ) {
			return false;
		} else {
			return true;
		}
	}

	function delete_user( $id ) {

		$ret = $this->exec_procedure( "user_delete( $id, @po_ret )" );

		if( $ret['@po_ret'] == 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function add_user( $name, $password, $perm_login, $perm_admin, $att_new ) {
		$userid = $this->insert_row( "	INSERT INTO users
						      (name
						      ,password
						      ,perm_login
						      ,perm_admin
						      ,att_new
						      )
						       VALUES
						      ('$name'
						      ,'".sha1( $password )."'
						      ,$perm_login
						      ,$perm_admin
						      ,$att_new
						      );" );
		return $userid;
	}

	function update_user( $id, $name, $perm_login, $perm_admin, $att_new ) {
		LoggedOnUser::getInstance()->destroyInstance($id);
		return $this->update_row( "	UPDATE users
						   SET name       = '$name'
						      ,perm_login = $perm_login
						      ,perm_admin = $perm_admin
						      ,att_new    = $att_new
						 WHERE userid = $id;" );
	}
}
