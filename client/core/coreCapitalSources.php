<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCapitalSources.php,v 1.15 2007/01/13 08:14:31 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';

class coreCapitalSources extends core {

	function coreCapitalSources() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT id,type,state,accountnumber,bankcode,comment,validtil,validfrom FROM capitalsources WHERE userid='.USERID.' ORDER BY id' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,type,state,accountnumber,bankcode,comment,validtil,validfrom FROM capitalsources WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( 'SELECT DISTINCT UPPER(SUBSTR(comment,1,1)) letters FROM capitalsources WHERE userid='.USERID.' ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT id,type,state,accountnumber,bankcode,comment,validtil,validfrom FROM capitalsources WHERE UPPER(comment) LIKE UPPER('$letter%') AND userid=".USERID." ORDER BY comment" );
	}

	function get_all_ids() {
		return $this->select_cols( 'SELECT id FROM capitalsources WHERE userid='.USERID.' ORDER BY id' );
	}

	function get_valid_ids( $validfrom, $validtil ) {
		return $this->select_cols( "SELECT id FROM capitalsources WHERE validfrom <= '$validfrom' and validtil >= '$validtil' AND userid=".USERID." ORDER BY id" );
	}

	function get_all_comments() {
		return $this->select_rows( 'SELECT id,comment FROM capitalsources WHERE userid='.USERID.' ORDER BY id' );
	}

	function get_valid_comments( $validfrom, $validtil ) {
		$result=$this->select_rows( "SELECT id,comment FROM capitalsources WHERE validfrom <= '$validfrom' and validtil >= '$validtil' AND userid=".USERID." ORDER BY id" );
		if( is_array( $result ) ) {
			return $result;
		} else {
			add_error( 1 );
			return;
		}
	}

	function get_enum_type() {
		return $this->real_get_enum_values( 'capitalsources', 'type' );
	}

	function get_enum_state() {
		return $this->real_get_enum_values( 'capitalsources', 'state' );
	}

	function get_comment( $id ) {
		return $this->select_col( "SELECT comment FROM capitalsources WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}

	function get_type( $id ) {
		return $this->select_col( "SELECT type FROM capitalsources WHERE id=$id AND userid=".USERID."  LIMIT 1" );
	}

	function get_state( $id ) {
		return $this->select_col( "SELECT state FROM capitalsources WHERE id=$id AND userid=".USERID."  LIMIT 1" );
	}

	function id_is_valid( $id, $date ) {
		return $this->select_col( "SELECT 1 from capitalsources WHERE id=$id AND validfrom <= '$date' AND validtil >= '$date' AND userid=".USERID." LIMIT 1" );
	}


	function delete_capitalsource( $id ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use( $id ) ) {
			add_error( 2 );
			return 0;
		} else {
			return $this->delete_row( "DELETE FROM capitalsources WHERE id=$id AND userid=".USERID." LIMIT 1" );
		}
	}


	function update_capitalsource( $id, $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) ) {
			add_error( 3 );
			return 0;
		} else {
			return $this->update_row( "UPDATE capitalsources set type='$type',state='$state',accountnumber='$accountnumber',bankcode='$bankcode',comment='$comment',validfrom='$validfrom',validtil='$validtil' WHERE id=$id AND userid=".USERID." " );
		}
	}

	function add_capitalsource( $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		if( empty( $validfrom ) )
			$validfrom='default';

		if( empty( $validtil ) )
			$validtil='default';

		return $this->insert_row( "INSERT INTO capitalsources (userid,type,state,accountnumber,bankcode,comment,validfrom,validtil) VALUES (".USERID.",'$type','$state','$accountnumber','$bankcode','$comment','$validfrom','$validtil')" );
	}
}
