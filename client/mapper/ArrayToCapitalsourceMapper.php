<?php

//
// Copyright (c) 2013 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ArrayToCapitalsourceMapper.php,v 1.6 2013/09/06 19:33:37 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\model\Capitalsource;

class ArrayToCapitalsourceMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new Capitalsource( $a ['capitalsourceid'] );
		$b->setAccountNumber( $a ['accountnumber'] );
		$b->setBankCode( $a ['bankcode'] );
		$b->setComment( $a ['comment'] );
		$b->setGroupUse( $a ['att_group_use'] );
		$b->setState( $a ['state'] );
		$b->setType( $a ['type'] );

		$validfrom = parent::convertClientDateToModel( $a ['validfrom'] );
		if ($validfrom)
			$b->setValidFrom( $validfrom );

		$validtil = parent::convertClientDateToModel( $a ['validtil'] );
		if ($validtil)
			$b->setValidTil( $validtil );

		return $b;
	}

	public static function mapBToA(Capitalsource $b) {
		$a ['capitalsourceid'] = $b->getId();
		$a ['accountnumber'] = $b->getAccountNumber();
		$a ['bankcode'] = $b->getBankCode();
		$a ['comment'] = $b->getComment();
		$a ['att_group_use'] = $b->getGroupUse();
		$a ['state'] = $b->getState();
		$a ['type'] = $b->getType();
		$a ['validfrom'] = parent::convertModelDateToClient( $b->getValidFrom() );
		$a ['validtil'] = parent::convertModelDateToClient( $b->getValidTil() );
		$a ['mur_userid'] = $b->getUser()->getId();

		return $a;
	}
}

?>