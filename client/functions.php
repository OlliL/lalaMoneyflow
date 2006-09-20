<?php

/*
	$Id: functions.php,v 1.1 2006/09/20 18:37:41 olivleh1 Exp $
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

function fix_amount( $amount ) {
	return str_replace( ',', '.', $amount );
}

?>
