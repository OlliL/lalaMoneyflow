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
# $Id: core.php,v 1.15 2007/07/24 18:22:06 olivleh1 Exp $
#

require_once 'DB.php';

class core {

	function core() {
		$this->db = DB::connect( $GLOBALS['dsn'],1 );
#		$this->timer = new utilTimer();
		if( DB::isError( $this->db ) ) {
			die( $this->db->getMessage() );
		}
	}

	function query( $query ) {
#		$this->timer->mStart();
#		echo "<pre>".str_replace("	","",$query)."</pre>";
		$result=$this->db->query( $query );
#		$this->timer->mPrintTime();
		return $result;
	}

	function select_col( $query ) {
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		if( $reslink->numRows() <= 1 ) {
			list( $retval ) = $reslink->fetchrow();
			return $retval;
		} else {
			die( 'more than one results, but it should be unique!' );
		}
	}

	function select_cols( $query ) {
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		while ( list( $retval ) = $reslink->fetchrow() )
			$retvals[] = $retval;
		return $retvals;
	}

	function select_row( $query ) {
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		if( $reslink->numRows() <= 1 ) {
			$retval = $reslink->fetchrow( DB_FETCHMODE_ASSOC );
			return $retval;
		} else {
			die( 'more than one results, but it should be unique!' );
		}
	}

	function select_rows( $query ) {
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		while ( $val = $reslink->fetchrow( DB_FETCHMODE_ASSOC ) )
		$retval[]=$val;
		return $retval;
	}

	function generic_query( $query ) {
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		return true;
	}

	function delete_row( $query ) {
		return $this->generic_query( $query );
	}

	function insert_row( $query ) {
		$this->generic_query( $query );
		return $this->select_col( 'SELECT LAST_INSERT_ID()' );
	}

	function update_row( $query ) {
		return $this->generic_query( $query );
	}

	function real_get_enum_values( $table, $column ) {
		$definition=$this->select_row( "SHOW COLUMNS FROM $table LIKE '$column'" );

		$enum = str_replace( 'enum(', '', $definition['Type'] );
		$enum = ereg_replace( '\\)$', '', $enum );
		$enum = explode( '\',\'', substr( $enum, 1, -1 ) );

		return $enum;
	}
	
	function make_date( $date ) {
		if( empty($date) ) {
			$date='NOW()';
		} else {
			$date="STR_TO_DATE('$date',GET_FORMAT(DATE,'ISO'))";
		}
		
		return $date;
	}
}
