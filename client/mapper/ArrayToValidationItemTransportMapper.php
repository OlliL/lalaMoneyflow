<?php

namespace rest\client\mapper;

use rest\api\model\validation\transport\ValidationItemTransport;

class ArrayToValidationItemTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(ValidationItemTransport $a) {
		$b ['key'] = $a->getKey();
		$b ['error'] = $a->getError();
		return $b;
	}

	public static function mapBToA(array $b) {
		$a = new ValidationItemTransport();
		$a->setKey( $b ['key'] );
		$a->setError( $b ['error'] );
		return $a;
	}
}

?>