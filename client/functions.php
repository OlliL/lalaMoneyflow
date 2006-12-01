<?php

/*
	$Id: functions.php,v 1.2 2006/12/01 15:37:56 olivleh1 Exp $
*/

function get_errors() {
	global $ERRORS;
	return $ERRORS;
}

function add_error( $error ) {
	global $ERRORS;
	$ERRORS[]=$error;
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
	
	if( preg_match( '/^-{0,1}[0-9]+([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( '.', '',  $amount );
		$amount = str_replace( ',', '.', $amount );
	} elseif( preg_match( '/^-{0,1}[0-9]+([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount ) ) {
		$amount = str_replace( ',', '', $amount );
	} else {
		add_error( "amount $amount is not in a readable format" );
		$return = false;
	}

	return $return;
}

?>
