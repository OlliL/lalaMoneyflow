<?php

namespace rest\base;

abstract class AbstractMapperSupport {
	private $mapper;

	public function __construct() {
		$this->mapper = array ();
	}

	protected function map($obj) {
		if ($obj) {
			$object = $this->mapper [get_class( $obj )];

			if ($object == NULL) {
				throw new BusinessException( 'Mapper for ' . get_class( $a ) . ' not defined in ' . get_class( $this ) . '!' );
			}

			$class = $object [0];
			$method = $object [1];
			$mapper = new $class();

			if (! is_object( $mapper )) {
				throw new BusinessException( 'Mapper for ' . get_class( $a ) . ' cannot be instantiated!' );
			}

			return $mapper->$method( $obj );
		}
	}

	protected function mapArray(array $aArray) {
		$result = array ();
		foreach ( $aArray as $a ) {
			$result [] = self::map( $a );
		}
		return $result;
	}

	protected function addMapper($class) {
		$a = new \ReflectionParameter( array (
				$class,
				'mapAToB'
		), 0 );

		$this->mapper [$a->getClass()->name] = array (
				$class,
				'mapAToB'
		);

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