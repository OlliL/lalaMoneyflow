<?php

/*
	$Id: functions.php,v 1.4 2006/12/20 17:45:06 olivleh1 Exp $
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
	if( ! $foo=strptime( $date, '%Y-%m-%d') ){
	var_dump($foo);
		return false;
}	else
		return true;
}

function fix_amount( &$amount ) {
	$return = true;
	
	if( preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( '.', '',  $amount );
		$amount = str_replace( ',', '.', $amount );
	} elseif( preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( ',', '', $amount );
	} else {
		add_error( 14, array( $amount ) );
		$return = false;
	}

	return $return;
}

?>
