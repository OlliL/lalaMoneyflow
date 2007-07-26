<?php

/*
	$Id: functions.php,v 1.7 2007/07/26 15:36:51 olivleh1 Exp $
*/


function add_error( $id, $args=NULL ) {
	global $ERRORS;
	if( is_array( $args ) ) {
		$ERRORS[] = array( 'id'        => $id,
		                   'arguments' => $args
		                 );
	} else {
		$ERRORS[] = array( 'id'        => $id );
	}
}

function is_date( $date ) {
	$foo=strptime( $date, '%Y-%m-%d');
	if( is_array( $foo ) && $date == sprintf( "%4d-%02d-%02d", ($foo['tm_year']+1900), ($foo['tm_mon']+1), $foo['tm_mday'] ) ) {
		return true;
	} else {
		return false;
	}
}

function fix_amount( &$amount ) {
	$return = true;
	
	if( preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( '.', '',  $amount );
		$amount = str_replace( ',', '.', $amount );
	} elseif( preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( ',', '', $amount );
	} else {
		add_error( 132, array( $amount ) );
		$return = false;
	}

	return $return;
}

?>
