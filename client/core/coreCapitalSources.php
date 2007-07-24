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
# $Id: coreCapitalSources.php,v 1.21 2007/07/24 18:22:06 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';

class coreCapitalSources extends core {

	function coreCapitalSources() {
		$this->core();
	}

	function count_all_data() {
		if ( $num = $this->select_col( 'SELECT count(*)
						  FROM capitalsources' ) ) {
			return $num;
		} else {
			return;
		}
	}
	
	function get_all_data() {
		return $this->select_rows( '	SELECT capitalsourceid
						      ,type
						      ,state
						      ,accountnumber
						      ,bankcode
						      ,comment
						      ,validtil
						      ,validfrom
						  FROM capitalsources
						 WHERE mur_userid = '.USERID.'
						 ORDER BY capitalsourceid' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT capitalsourceid
						      ,type
						      ,state
						      ,accountnumber
						      ,bankcode
						      ,comment
						      ,validtil
						      ,validfrom
						  FROM capitalsources
						 WHERE capitalsourceid = $id
						   AND mur_userid      = ".USERID."
						 LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( '	SELECT DISTINCT UPPER(SUBSTR(comment,1,1)) letters
						  FROM capitalsources
						 WHERE mur_userid = '.USERID.'
						 ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "	SELECT capitalsourceid
						      ,type
						      ,state
						      ,accountnumber
						      ,bankcode
						      ,comment
						      ,validtil
						      ,validfrom
						  FROM capitalsources
						 WHERE UPPER(comment) LIKE UPPER('$letter%')
						   AND mur_userid = ".USERID."
						 ORDER BY comment" );
	}

	function get_all_ids() {
		return $this->select_cols( '	SELECT capitalsourceid
						  FROM capitalsources
						 WHERE mur_userid = '.USERID.'
						 ORDER BY capitalsourceid' );
	}

	function get_valid_ids( $date='' ) {
		$date = $this->make_date($date);
		return $this->select_cols( "	SELECT capitalsourceid
						  FROM capitalsources
						 WHERE $date BETWEEN validfrom AND validtil
						   AND mur_userid = ".USERID."
						 ORDER BY capitalsourceid" );
	}

	function get_all_comments() {
		return $this->select_rows( '	SELECT capitalsourceid
						      ,comment
						  FROM capitalsources
						 WHERE mur_userid = '.USERID.'
						 ORDER BY capitalsourceid' );
	}

	function get_valid_comments( $date='' ) {
		$date = $this->make_date($date);
		$result=$this->select_rows( "	SELECT capitalsourceid
						       ,comment
						   FROM capitalsources
						  WHERE $date        BETWEEN validfrom AND validtil
						    AND mur_userid = ".USERID."
						  ORDER BY capitalsourceid" );
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
		return $this->select_col( "	SELECT comment
						  FROM capitalsources
						 WHERE capitalsourceid = $id
						   AND mur_userid      = ".USERID."
						 LIMIT 1" );
	}

	function get_type( $id ) {
		return $this->select_col( "	SELECT type
						  FROM capitalsources
						 WHERE capitalsourceid = $id
						   AND mur_userid      = ".USERID."
						 LIMIT 1" );
	}

	function get_state( $id ) {
		return $this->select_col( "	SELECT state
						  FROM capitalsources
						 WHERE capitalsourceid = $id
						   AND mur_userid      = ".USERID."
						 LIMIT 1" );
	}

	function id_is_valid( $id, $date='' ) {
		$date = $this->make_date($date);
		return $this->select_col( "	SELECT 1
						  FROM capitalsources
						 WHERE capitalsourceid = $id
						   AND $date             BETWEEN validfrom AND validtil
						   AND mur_userid      = ".USERID."
						 LIMIT 1" );
	}


	function delete_capitalsource( $id ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use( $id ) ) {
			add_error( 2 );
			return 0;
		} else {
			return $this->delete_row( "	DELETE FROM capitalsources
							 WHERE capitalsourceid = $id
							   AND mur_userid      = ".USERID."
							 LIMIT 1" );
		}
	}


	function update_capitalsource( $id, $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		$coreMoneyFlows = new coreMoneyFlows();
		if( $coreMoneyFlows->capitalsource_in_use_out_of_date( $id, $validfrom, $validtil ) ) {
			add_error( 3 );
			return 0;
		} else {
			$validtil  = $this->make_date($validtil);
			$validfrom = $this->make_date($validfrom);
			return $this->update_row( "	UPDATE capitalsources
							   SET type          = '$type'
							      ,state         = '$state'
							      ,accountnumber = '$accountnumber'
							      ,bankcode      = '$bankcode'
							      ,comment       = '$comment'
							      ,validfrom     = $validfrom
							      ,validtil      = $validtil
							 WHERE capitalsourceid = $id
							   AND mur_userid      = ".USERID." 
							 LIMIT 1" );
		}
	}

	function add_capitalsource( $type, $state, $accountnumber, $bankcode, $comment, $validfrom, $validtil ) {
		if( empty( $validtil ) )
			$validtil='2999-12-31';
		$validtil  = $this->make_date($validtil);
		$validfrom = $this->make_date($validfrom);

		return $this->insert_row( "	INSERT INTO capitalsources 
						      (mur_userid
						      ,type
						      ,state
						      ,accountnumber
						      ,bankcode
						      ,comment
						      ,validfrom
						      ,validtil
						      )
						       VALUES
						      (".USERID."
						      ,'$type'
						      ,'$state'
						      ,'$accountnumber'
						      ,'$bankcode'
						      ,'$comment'
						      ,$validfrom
						      ,$validtil
						      )" );
	}
}
