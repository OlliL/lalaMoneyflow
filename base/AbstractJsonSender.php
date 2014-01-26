<?php

//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: AbstractJsonSender.php,v 1.6 2014/01/26 12:24:49 olivleh1 Exp $
//
namespace rest\base;

class AbstractJsonSender extends AbstractMapperSupport {

	protected function json_encode_response($response) {
		$class = get_class( $response );
		$classArray = explode( '\\', $class );
		return '{"' . array_pop( $classArray ) . '":' . json_encode( get_object_vars($response ) ) . '}';
	}

	protected function json_encode($obj) {
		if (is_array( $obj )) {
			$element = reset( $obj );
			if (is_object( $element )) {
				$class = get_class( reset( $obj ) );
			}
		} else {
			if (is_object( $obj )) {
				$class = get_class( $obj );
			}
		}
		if (is_string( $class )) {
			$classArray = explode( '\\', $class );

			if ((is_array( $obj ) && count( $obj ) > 0)) {
				return '{"' . array_pop( $classArray ) . '":' . json_encode( parent::mapArray( $obj ) ) . '}';
			} else if ($obj != NULL) {
				return '{"' . array_pop( $classArray ) . '":' . json_encode( parent::map( $obj ) ) . '}';
			}
		} else if (is_array( $obj )) {
			return '{"List":' . json_encode( $obj ) . '}';
		} else {
			return '{"Scalar":' . json_encode( $obj ) . '}';
		}
	}
}

?>