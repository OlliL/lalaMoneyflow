<?php

namespace rest\api\model\capitalsource\transport\mapper;

use rest\api\model\AbstractTransportMapper;
use rest\api\model\capitalsource\transport\CapitalsourceTransport;
use rest\model\Capitalsource;
use rest\model\User;

class CapitalsourceTransportToCapitalsourceMapper extends AbstractTransportMapper {

	public static function mapAToB(CapitalsourceTransport $a) {
		$b = new Capitalsource( $a->getId() );
		$b->setAccountNumber( $a->getAccountNumber() );
		$b->setBankCode( $a->getBankCode() );
		$b->setComment( $a->getComment() );
		$b->setState( $a->getState() );
		$b->setType( $a->getType() );
		$b->setGroupUse( $a->getGroupUse() );
		$b->setUser( new User( $a->getUserid() ) );

		$validFrom = parent::convertTransportDateToModel( $a->getValidFrom() );
		if ($validFrom != NULL)
			$b->setValidFrom( $validFrom );

		$validTil = parent::convertTransportDateToModel( $a->getValidTil() );
		if ($validTil != NULL)
			$b->setValidTil( $validTil );

		return $b;
	}

	public static function mapBToA(Capitalsource $b) {
		$a = new CapitalsourceTransport();
		$a->setId( $b->getId() );
		if ($b->getUser() instanceof User)
			$a->setUserid( $b->getUser()->getId() );
		$a->setType( $b->getType() );
		$a->setState( $b->getState() );
		$a->setAccountNumber( $b->getAccountNumber() );
		$a->setBankCode( $b->getBankCode() );
		$a->setComment( $b->getComment() );
		$a->setValidTil( parent::convertModelDateToTransport( $b->getValidTil() ) );
		$a->setValidFrom( parent::convertModelDateToTransport( $b->getValidFrom() ) );
		$a->setGroupUse( $b->getGroupUse() );
		return $a;
	}
}

?>