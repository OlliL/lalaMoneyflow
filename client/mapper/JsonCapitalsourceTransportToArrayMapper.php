<?php

namespace rest\client\mapper;

class JsonCapitalsourceTransportToArrayMapper extends AbstractArrayMapper  {

	public static function mapAToB(array $a) {
		$b['id'] =$a ['capitalsourceid'];
		$b['accountNumber'] = $a ['accountnumber'];
		$b['bankCode'] = $a ['bankcode'];
		$b['comment'] = $a ['comment'];
		$b['groupUse'] = $a ['att_group_use'];
		$b['state'] = $a ['state'];
		$b['type'] = $a ['type'];
		$b['userid'] = $a['mur_userid'];
		$b['validFrom'] = parent::convertClientDateToJson( $a ['validfrom'] );
		$b['validTil'] = parent::convertClientDateToJson( $a ['validtil'] );

		return $b;
	}

	public static function mapBToA(array $b) {
		$a ['capitalsourceid'] = $b['id'];
		$a ['accountnumber'] = $b['accountNumber'];
		$a ['bankcode'] = $b['bankCode'];
		$a ['comment'] = $b['comment'];
		$a ['att_group_use'] = $b['groupUse'];
		$a ['state'] = $b['state'];
		$a ['type'] = $b['type'];
		$a ['mur_userid'] = $b['userid'];
		$a ['validfrom'] = parent::convertJsonDateToClient( $b['validFrom'] );
		$a ['validtil'] = parent::convertJsonDateToClient( $b['validTil'] );

		return $a;
	}

}

?>