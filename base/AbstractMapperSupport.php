<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: AbstractMapperSupport.php,v 1.17 2015/09/13 17:43:12 olivleh1 Exp $
//
namespace base;

abstract class AbstractMapperSupport {
	private $mapper;

	private final function getMapper($obj, $targetObject = null) {
		if ($obj) {
			$object = null;
			if (is_array( $obj ) && $targetObject !== null) {
				$object = $this->mapper ['array'] [$targetObject];
			} else {

				if (array_key_exists( get_class( $obj ), $this->mapper )) {
					$mapperArray = array_values( $this->mapper [get_class( $obj )] );
				} elseif (array_key_exists( get_parent_class( $obj ), $this->mapper )) {
					$mapperArray = array_values( $this->mapper [get_parent_class( $obj )] );
				} else {
					$mapperArray = null;
				}

				if ($mapperArray != null) {
					if (count( $mapperArray ) == 1)

						$object = $mapperArray [0];
					elseif (count( $mapperArray ) > 1 && $targetObject !== null)
						$object = $this->mapper [get_class( $obj )] [$targetObject];
				}
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

			return array (
					$mapper,
					$method
			);
		}
	}

	private final function executeMapper($mapper, $obj) {
		if ($mapper)
			return call_user_func( $mapper, $obj );
	}

	protected function map($obj, $targetObject = null) {
		$mapper = $this->getMapper( $obj, $targetObject );
		return $this->executeMapper( $mapper, $obj );
	}

	protected function mapArrayNullable($aArray, $targetObject = null) {
		if (! is_array( $aArray ))
			return array ();
		return $this->mapArray( $aArray, $targetObject );
	}

	protected function mapArray(array $aArray, $targetObject = null) {
		$result = array ();
		if (count( $aArray ) > 0) {
			$mapper = $this->getMapper( array_values( $aArray )[0], $targetObject );
			foreach ( $aArray as $a ) {
				$result [] = self::executeMapper( $mapper, $a );
			}
		}
		return $result;
	}

	protected function addMapper($class) {
		$a = new \ReflectionParameter( array (
				$class,
				'mapAToB'
		), 0 );
		$b = new \ReflectionParameter( array (
				$class,
				'mapBToA'
		), 0 );

		if ($a->isArray()) {
			$aName = 'array';
		} else {
			$aName = $a->getClass()->name;
		}
		if ($b->isArray()) {
			$bName = 'array';
		} else {
			$bName = $b->getClass()->name;
		}

		$this->mapper [$aName] [$bName] = array (
				$class,
				'mapAToB'
		);
		$this->mapper [$bName] [$aName] = array (
				$class,
				'mapBToA'
		);
	}
}

?>