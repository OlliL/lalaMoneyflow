<?php

namespace rest\base;

abstract class AbstractMapperSupport {
	private $mapper;

	protected function map($obj, $arrayType = NULL) {
		if ($obj) {
			if (is_array( $obj )) {
				$object = $this->mapper [$arrayType];
			} else {
				$object = $this->mapper [get_class( $obj )];
			}

			if ($object == NULL) {
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

	protected function mapArray(array $aArray, $arrayType = NULL) {
		$result = array ();
		foreach ( $aArray as $a ) {
			$result [] = self::map( $a, $arrayType );
		}
		return $result;
	}

	protected function addMapper($class, $arrayType = NULL) {
		if ($arrayType) {
			/* if the source is an array which has to be mapped to an object: */
			$this->mapper [$arrayType] = array (
					$class,
					'mapAToB'
			);
		} else {
			/* if the source is an object which has to be mapped to an object: */
			$a = new \ReflectionParameter( array (
					$class,
					'mapAToB'
			), 0 );

			$this->mapper [$a->getClass()->name] = array (
					$class,
					'mapAToB'
			);
		}

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

?>