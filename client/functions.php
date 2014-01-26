<?php
//
// Copyright (c) 2006-2013 Oliver Lehmann <oliver@FreeBSD.org>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: functions.php,v 1.20 2014/01/26 12:12:01 olivleh1 Exp $
//
function add_error($id, $args = NULL) {
	global $ERRORS;
	if (is_array( $args )) {
		$ERRORS [] = array (
				'id' => $id,
				'arguments' => $args
		);
	} else {
		$ERRORS [] = array (
				'id' => $id
		);
	}
}

function convert_array_to_utf8($arr) {
	foreach ( $arr as $key => $value ) {
		if (is_array( $value )) {
			$arr [$key] = convert_array_to_utf8( $value );
		} else if (! mb_check_encoding( $value, 'UTF-8' )) {
			$arr [$key] = utf8_encode( $value );
		}
	}

	return $arr;
}

function check_date($year, $month, $day) {
	if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
		$maxday = 31;
	} elseif ($month == 2) {
		if (($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0) {
			$maxday = 29;
		} else {
			$maxday = 28;
		}
	} else {
		$maxday = 30;
	}

	if ($year < 1970 || $year > 2999 || $month < 1 || $month > 12 || $day < 1 || $day > $maxday) {
		return false;
	} else {
		return true;
	}
}

function convert_timestamp_to_db($timestamp) {
	if (empty( $timestamp ))
		return false;

	return date( 'Y-m-d', $timestamp );
}

function dateIsValid($date) {
	if (empty( $date ))
		return false;

	$patterns [0] = '/YYYY/';
	$patterns [1] = '/MM/';
	$patterns [2] = '/DD/';

	$replacements [0] = 'Y';
	$replacements [1] = 'm';
	$replacements [2] = 'd';

	$format = preg_replace( $patterns, $replacements, GUI_DATE_FORMAT );

	$dateTime = DateTime::createFromFormat( $format, $date );

	if ($dateTime && $dateTime->format( $format ) == $date) {
		return true;
	} else {
		return false;
	}
}

function convert_date_to_db($date, $dateformat = GUI_DATE_FORMAT) {
	if (empty( $date ))
		return false;

	$patterns [0] = '/YYYY/';
	$patterns [1] = '/MM/';
	$patterns [2] = '/DD/';

	$replacements [0] = '%Y';
	$replacements [1] = '%m';
	$replacements [2] = '%d';

	$strptime_format = preg_replace( $patterns, $replacements, $dateformat );

	$date_array = strptime( $date, $strptime_format );

	$retval = false;

	if (is_array( $date_array ) && check_date( ($date_array ['tm_year'] + 1900), ($date_array ['tm_mon'] + 1), $date_array ['tm_mday'] )) {
		$retval = sprintf( '%4d-%02d-%02d', ($date_array ['tm_year'] + 1900), ($date_array ['tm_mon'] + 1), $date_array ['tm_mday'] );
	}

	return $retval;
}

function convert_date_to_timestamp($date, $dateformat = GUI_DATE_FORMAT) {
	if (empty( $date ))
		return false;

	$patterns [0] = '/YYYY/';
	$patterns [1] = '/MM/';
	$patterns [2] = '/DD/';

	$replacements [0] = '%Y';
	$replacements [1] = '%m';
	$replacements [2] = '%d';

	$strptime_format = preg_replace( $patterns, $replacements, $dateformat );

	$date_array = strptime( $date, $strptime_format );

	$retval = false;

	if (is_array( $date_array ) && check_date( ($date_array ['tm_year'] + 1900), ($date_array ['tm_mon'] + 1), $date_array ['tm_mday'] )) {
		$retval = mktime( 0, 0, 0, $date_array ['tm_mon'] + 1, $date_array ['tm_mday'], $date_array ['tm_year'] + 1900 );
	}

	return $retval;
}

function convert_date_to_gui($date, $dateformat = GUI_DATE_FORMAT) {
	if (empty( $date ))
		return false;

	$date_array = strptime( $date, '%Y-%m-%d' );

	$patterns [0] = '/YYYY/';
	$patterns [1] = '/MM/';
	$patterns [2] = '/DD/';

	$replacements [0] = ($date_array ['tm_year'] + 1900);
	$replacements [1] = sprintf( '%02d', ($date_array ['tm_mon'] + 1) );
	$replacements [2] = sprintf( '%02d', $date_array ['tm_mday'] );

	return preg_replace( $patterns, $replacements, $dateformat );
}

function fix_amount(&$amount) {
	$return = true;

	if (preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount )) {
		$amount = str_replace( '.', '', $amount );
		$amount = str_replace( ',', '.', $amount );
	} elseif (preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount )) {
		$amount = str_replace( ',', '', $amount );
	} else {
		add_error( 132, array (
				$amount
		) );
		$return = false;
	}

	return $return;
}

?>
