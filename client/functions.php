<?php

//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: functions.php,v 1.24 2014/02/28 22:19:47 olivleh1 Exp $
//
use base\ErrorCode;
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
		add_error( ErrorCode::AMOUNT_IN_WRONG_FORMAT, array (
				$amount
		) );
		$return = false;
	}

	return $return;
}

?>
