<?php

namespace rest\api\model\contractpartner\transport\mapper;

use rest\api\model\AbstractTransportMapper;
use rest\api\model\contractpartner\transport\ContractpartnerTransport;
use rest\model\Contractpartner;
use rest\model\User;

class ContractpartnerTransportToContractpartnerMapper extends AbstractTransportMapper {

	public static function mapAToB(ContractpartnerTransport $a) {
		$b = new Contractpartner( $a->getId() );
		$b->setCountry( $a->getCountry() );
		$b->setName( $a->getName() );
		$b->setPostcode( $a->getPostcode() );
		$b->setStreet( $a->getStreet() );
		$b->setTown( $a->getTown() );
		$b->setUser( new User( $a->getUserid() ) );
		return $b;
	}

	public static function mapBToA(Contractpartner $b) {
		$a = new ContractpartnerTransport();
		$a->setId( $b->getId() );
		if ($b->getUser() instanceof User)
			$a->setUserid( $b->getUser()->getId() );
		$a->setCountry( $b->getCountry() );
		$a->setName( $b->getName() );
		$a->setPostcode( $b->getPostcode() );
		$a->setStreet( $b->getStreet() );
		$a->setTown( $b->getTown() );
		return $a;
	}
}

?>