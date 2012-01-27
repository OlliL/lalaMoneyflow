<?php
#-
# Copyright (c) 2012 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreGroups.php,v 1.1 2012/01/27 20:03:52 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreGroups extends core {

	function coreGroups() {
		$this->core();
	}

	function count_all_data() {
		if ( $num=$this->select_col( 'SELECT count(*) FROM groups' ) ) {
			return $num;
		} else {
			return;
		}
	}

	function get_all_data() {
		return $this->select_rows( '	SELECT groupid
						      ,name
						  FROM groups
						 ORDER BY groupid' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT groupid
						      ,name
						  FROM groups
						 WHERE groupid  = $id
						 LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( '	SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters
						  FROM groups
						 WHERE groupid != 0
						 ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "	SELECT groupid
						      ,name
						  FROM groups
						 WHERE UPPER(name) LIKE UPPER('$letter%')
						   AND groupid	!= 0
						 ORDER BY name" );
	}

	function group_is_assigned( $id ) {

		$ret = $this->select_col( " 	SELECT COUNT(*)
						  FROM user_groups
						 WHERE mgr_groupid = $id
						 LIMIT 1" );
						 
		if( $ret == 0 ) {
			return false;
		} else {
			return true;
		}
	}

	function delete_group( $id ) {
	
		$ret = $this->delete_row( "	DELETE
						  FROM user_groups
						 WHERE mgr_groupid = $id" );

		$ret = $this->delete_row( "	DELETE
						  FROM groups
						 WHERE groupid = $id
						 LIMIT 1" );
		return true;

	}

	function add_group( $name ) {
		$groupid = $this->insert_row( "	INSERT INTO groups 
						      (name
						      )
						       VALUES
						      ('$name'
						      );" );
		return $groupid;
	}

	function update_group( $id, $name ) {
		return $this->update_row( "	UPDATE groups
						   SET name       = '$name'
						 WHERE groupid = $id;" );
	}
}
