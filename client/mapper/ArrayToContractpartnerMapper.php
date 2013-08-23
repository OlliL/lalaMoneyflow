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
// $Id: ArrayToContractpartnerMapper.php,v 1.3 2013/08/23 20:36:36 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\model\Contractpartner;

class ArrayToContractpartnerMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new Contractpartner( $a ['contractpartnerid'] );
		$b->setCountry( utf8_encode($a ['country']) );
		$b->setName( utf8_encode($a ['name']) );
		$b->setPostcode( $a ['postcode'] );
		$b->setStreet( utf8_encode($a ['street']) );
		$b->setTown( utf8_encode($a ['town']) );

		return $b;
	}

	public static function mapBToA(Contractpartner $b) {
		$a ['contractpartnerid'] = $b->getId();
		$a ['country'] = $b->getCountry();
		$a ['name'] = $b->getName();
		$a ['postcode'] = $b->getPostcode();
		$a ['street'] = $b->getStreet();
		$a ['town'] = $b->getTown();
		$a ['mur_userid'] = $b->getUser()->getId();

		return $a;
	}
}

?>