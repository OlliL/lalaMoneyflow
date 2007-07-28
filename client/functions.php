<?php

/*
	$Id: functions.php,v 1.9 2007/07/28 19:41:10 olivleh1 Exp $
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

function check_date( $year, $month, $day ) {
	if( $month == 1 ||
	    $month == 3 ||
	    $month == 5 ||
	    $month == 7 ||
	    $month == 8 ||
	    $month == 10 ||
	    $month == 12 ) {
		$maxday = 31;
	} elseif( $month == 2 ) {
		if( ($year %   4 === 0  &&
		     $year % 100 !== 0) ||
		     $year % 400 === 0 ) {
			$maxday = 29;
		} else {
			$mayday = 28;
		}
	} else {
		$maxday = 30;
	}

	if( $year  < 1970 || $year  > 2999 ||
	    $month <    1 || $month >   12 ||
	    $day   <    1 || $day   > $maxday ) {
		return false;
	} else {
		return true;
	}
}		

function convert_date_to_db( $date, $dateformat ) {

	if( empty( $date ) )
		return false;

	$patterns[0] = '/YYYY/';
	$patterns[1] = '/MM/';
	$patterns[2] = '/DD/';
	
	$replacements[0] = '%Y';
	$replacements[1] = '%m';
	$replacements[2] = '%d';
	
	$strptime_format = preg_replace( $patterns, $replacements, $dateformat );

	$date_array = strptime( $date, $strptime_format);

	$retval = false;

	if( is_array( $date_array ) && check_date( ( $date_array['tm_year']+1900 ), ( $date_array['tm_mon'] + 1 ), $date_array['tm_mday'] ) ) {
		$retval = sprintf( '%4d-%02d-%02d', ( $date_array['tm_year']+1900 ), ( $date_array['tm_mon'] + 1 ), $date_array['tm_mday'] );
	}

	return $retval;
}

function convert_date_to_gui( $date, $dateformat ) {

	if( empty( $date ) )
		return false;

	$date_array = strptime( $date, '%Y-%m-%d');

	$patterns[0] = '/YYYY/';
	$patterns[1] = '/MM/';
	$patterns[2] = '/DD/';
	
	$replacements[0] = ( $date_array['tm_year']+1900 );
	$replacements[1] = sprintf( '%02d', ( $date_array['tm_mon'] + 1 ));
	$replacements[2] = sprintf( '%02d', $date_array['tm_mday'] );
	
	return preg_replace( $patterns, $replacements, $dateformat );
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
