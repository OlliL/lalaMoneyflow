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
// $Id: AbstractMapperSupport.php,v 1.11 2014/03/07 20:41:36 olivleh1 Exp $
//
namespace base;

abstract class AbstractMapperSupport {
	private $mapper;

	protected function map($obj, $arrayType = null) {
		if ($obj) {
			$object = null;
			if ($arrayType) {
				$object = $this->mapper [$arrayType];
			} elseif (array_key_exists( get_class( $obj ), $this->mapper )) {
				$object = $this->mapper [get_class( $obj )];
			}

			if ($object == null) {
				throw new \Exception( 'Mapper for ' . get_class( $obj ) . ' not defined in ' . get_class( $this ) . '!' );
			}

			$class = $object [0];
			$method = $object [1];
			$mapper = new $class();

			if (! is_object( $mapper )) {
				throw new \Exception( 'Mapper for ' . get_class( $obj ) . ' cannot be instantiated!' );
			}

			return $mapper->$method( $obj );
		}
	}

	protected function mapArrayNullable($aArray, $arrayType = null) {
		if (! is_array( $aArray ))
			return array();
		return $this->mapArray( $aArray, $arrayType );
	}

	protected function mapArray(array $aArray, $arrayType = null) {
		$result = array ();
		foreach ( $aArray as $a ) {
			$result [] = self::map( $a, $arrayType );
		}
		return $result;
	}

	protected function addMapper($class, $arrayTypeA = null, $arrayTypeB = null) {
		if ($arrayTypeA) {
			/* if the source is an array which has to be mapped */
			$this->mapper [$arrayTypeA] = array (
					$class,
					'mapAToB'
			);
		} else {
			/* if the source is an object */
			$a = new \ReflectionParameter( array (
					$class,
					'mapAToB'
			), 0 );

			$this->mapper [$a->getClass()->name] = array (
					$class,
					'mapAToB'
			);
		}

		if ($arrayTypeB) {
			/* if the target is an array which has to be mapped */
			$this->mapper [$arrayTypeB] = array (
					$class,
					'mapBToA'
			);
		} else {
			/* if the target is an object */
			$b = new \ReflectionParameter( array (
					$class,
					'mapBToA'
			), 0 );
			$this->mapper [$b->getClass()->name] = array (
					$class,
					'mapBToA'
			);
		}
	}
}

?>