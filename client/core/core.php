<?php

/*
	$Id: core.php,v 1.9 2006/09/20 18:37:41 olivleh1 Exp $
*/

require_once 'DB.php';

class core {

	function core() {
		$this->db = DB::connect( $GLOBALS['dsn'],1 );

		if( DB::isError( $this->db ) ) {
			die( $this->db->getMessage() );
		}
	}

	function query( $query ) {
#		echo "<pre>$query</pre>";
		return $this->db->query( $query );
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
}
