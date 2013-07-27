<?php
#-
# Copyright (c) 2005-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: core.php,v 1.24 2013/07/27 23:06:48 olivleh1 Exp $
#

require_once 'DB.php';

class core {

	function core() {
		$this->db = DB::connect( $GLOBALS['dsn'],1 );
		if( DB::isError( $this->db ) ) {
			die( $this->db->getMessage() );
		}
	}

	function query( $query ) {
		GLOBAL $money_debug;

		if( $money_debug === true ) {
			GLOBAL $sql_querytime;
			$query = str_replace("	","",$query);
			echo '<a href="explain.php?query='.urlencode($query).'" style="text-decoration:none"><pre>'.$query.'</pre></a>';
			$timer = new utilTimer();
			$timer->mStart();
		}
		$result=$this->db->query( $query );
		if( $money_debug === true ) {
			$querytime      = $timer->mGetTime();
			$sql_querytime += $querytime;
			$timer->mPrintTime( $querytime );
		}
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
		$retval  = false;
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
		$retval  = false;
		$reslink = $this->query( $query );
		if( DB::isError( $reslink ) )
			die( $reslink->getMessage() );
		while ( $val = $reslink->fetchrow( DB_FETCHMODE_ASSOC ) )
			$retval[] = $val;
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

	function exec_function( $function ) {
		return $this->select_col( 'SELECT '.$function.' FROM DUAL' );
	}

	function exec_procedure( $procedure ) {
		$ret = $this->query( 'CALL '.$procedure );

		$all_params_string = preg_replace( '/[^\(]*\([\s]*(.*)[\s]*\)/','$1', $procedure );
		$all_params_array  = preg_split( '/,[\s]*/', $all_params_string );

		foreach( $all_params_array as $key => $param ) {
			$pos = strpos( $param, '@' );
			if( $pos === 0 ) {
				$out_params_array[] = $param;
			}
		}

		if( is_array( $out_params_array ) ) {
			$out_params_string = implode( ',', $out_params_array );
			preg_replace( '/\s/', '', $out_params_string );
			$out_params = $this->select_row( "SELECT $out_params_string FROM DUAL" );
			if( is_array ( $out_params ) ) {
				$ret = $out_params;
			} else {
				$ret = array();
			}
		}
		
		return $ret;
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
