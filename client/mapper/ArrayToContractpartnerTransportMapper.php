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
// $Id: ArrayToContractpartnerTransportMapper.php,v 1.9 2015/02/13 00:03:38 olivleh1 Exp $
//
namespace client\mapper;

use api\model\transport\ContractpartnerTransport;

class ArrayToContractpartnerTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new ContractpartnerTransport();
		if (array_key_exists( 'contractpartnerid', $a ))
			$b->setId( $a ['contractpartnerid'] );
		if (array_key_exists( "country", $a ))
			$b->setCountry( $a ['country'] );
		if (array_key_exists( "name", $a ))
			$b->setName( $a ['name'] );
		if (array_key_exists( "postcode", $a ))
			$b->setPostcode( $a ['postcode'] );
		if (array_key_exists( "street", $a ))
			$b->setStreet( $a ['street'] );
		if (array_key_exists( "town", $a ))
			$b->setTown( $a ['town'] );
		$validfrom = parent::convertClientDateToTransport( $a ['validfrom'] );
		if ($validfrom)
			$b->setValidFrom( $validfrom );

		$validtil = parent::convertClientDateToTransport( $a ['validtil'] );
		if ($validtil)
			$b->setValidTil( $validtil );
		if (array_key_exists( "moneyflow_comment", $a ))
			$b->setMoneyflowComment( $a ['moneyflow_comment'] );
		if (array_key_exists( "mpa_postingaccountid", $a ))
			$b->setPostingAccountId( $a ['mpa_postingaccountid'] );
		return $b;
	}

	public static function mapBToA(ContractpartnerTransport $b) {
		$a ['contractpartnerid'] = $b->getId();
		$a ['country'] = $b->getCountry();
		$a ['name'] = $b->getName();
		$a ['postcode'] = $b->getPostcode();
		$a ['street'] = $b->getStreet();
		$a ['town'] = $b->getTown();
		$a ['mur_userid'] = $b->getUserid();
		$a ['validfrom'] = parent::convertTransportDateToClient( $b->getValidFrom() );
		$a ['validtil'] = parent::convertTransportDateToClient( $b->getValidTil() );
		$a ['mpa_postingaccountid'] = $b->getPostingAccountId();
		$a ['mpa_postingaccountname'] = $b->getPostingAccountName();
		$a ['moneyflow_comment'] = $b->getMoneyflowComment();

		return $a;
	}
}

?>