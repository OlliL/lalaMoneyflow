<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: JsonAutoMapper.php,v 1.10 2015/11/01 12:14:09 olivleh1 Exp $
//
namespace base;

class JsonAutoMapper {

	public static function mapAToB(array $a, $classPath = '\\model') {
		// the first element of the json is always the name of the model object
		// by convention
		foreach ( $a as $className => $json ) {
			$class = new \ReflectionClass( $classPath . '\\' . $className );
			// the given json object is a single objects
			if (array_values( $json ) !== $json) {
				$b = self::map( $json, $class, $classPath );
			} else {
				$b = array();
				// the given json object is a list of object;
				foreach ( $json as $obj ) {
					$b [] = self::map( $obj, $class, $classPath );
				}
			}
		}
		return $b;
	}

	private static function map(array $json, $class, $classPath) {
		$b = $class->newInstance();
		foreach ( $json as $key => $property ) {
			// when the element of the array is another associative array, the property
			// of the object is another object. Detect the object by checking the type
			// hinting of the responsive setter of the current object and then recall
			// map() to map this sub-object to the type-hinted object and finally set the
			// mapped result

			// Single Transport-Object in the Response-Object
			if (is_array( $property ) && array_values( $property ) !== $property) {
				$setter = 'set' . ucfirst( $key );

				$method = new \ReflectionParameter( array (
						$b,
						$setter
				), 0 );
				$newclass = new \ReflectionClass( $method->getClass()->name );
				$b->$setter( self::map( $property, $newclass, '\\api\\model\\transport' ) );
			} else if ($property != NULL || $property === 0 || $property === 0.0) {
				$setter = 'set' . ucfirst( $key );

				// A List ob Transport-Objects in the Response-Object
				if ($key != ( string ) ( int ) $key && is_array( $property ) && is_array( reset( $property ) )) {
					$b->$setter( self::mapAToB( array (
							ucfirst( $key ) => $property
					), '\\api\\model\\transport' ) );
				} else {
					// regular array (list), or scalar value - just call the setter
					$b->$setter( $property );
				}
			}
		}
		return $b;
	}
}

?>