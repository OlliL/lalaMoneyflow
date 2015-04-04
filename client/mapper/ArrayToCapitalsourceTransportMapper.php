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
// $Id: ArrayToCapitalsourceTransportMapper.php,v 1.9 2015/04/04 22:03:41 olivleh1 Exp $
//
namespace client\mapper;

use api\model\transport\CapitalsourceTransport;

class ArrayToCapitalsourceTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new CapitalsourceTransport();
		if (array_key_exists( 'capitalsourceid', $a ))
			$b->setId( $a ['capitalsourceid'] );
		$b->setAccountNumber( $a ['accountnumber'] );
		$b->setBankCode( $a ['bankcode'] );
		$b->setComment( $a ['comment'] );
		$b->setGroupUse( $a ['att_group_use'] );
		$b->setState( $a ['state'] );
		$b->setType( $a ['type'] );

		$validfrom = parent::convertClientDateToTransport( $a ['validfrom'] );
		if ($validfrom)
			$b->setValidFrom( $validfrom );

		$validtil = parent::convertClientDateToTransport( $a ['validtil'] );
		if ($validtil)
			$b->setValidTil( $validtil );

		$b->setImportAllowed( $a ['import_allowed'] );

		return $b;
	}

	public static function mapBToA(CapitalsourceTransport $b) {
		$a ['capitalsourceid'] = $b->getId();
		$a ['accountnumber'] = $b->getAccountNumber();
		$a ['bankcode'] = $b->getBankCode();
		$a ['comment'] = $b->getComment();
		$a ['att_group_use'] = $b->getGroupUse();
		$a ['state'] = $b->getState();
		$a ['type'] = $b->getType();
		$a ['validfrom'] = parent::convertTransportDateToClient( $b->getValidFrom() );
		$a ['validtil'] = parent::convertTransportDateToClient( $b->getValidTil() );
		$a ['mur_userid'] = $b->getUserid();
		$a ['import_allowed'] = $b->getImportAllowed();

		return $a;
	}
}

?>