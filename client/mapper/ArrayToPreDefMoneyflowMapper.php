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
// $Id: ArrayToPreDefMoneyflowMapper.php,v 1.2 2013/09/02 18:10:04 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\model\Capitalsource;
use rest\model\Contractpartner;
use rest\model\PreDefMoneyflow;

class ArrayToPreDefMoneyflowMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new PreDefMoneyflow( $a ['predefmoneyflowid'] );

		$createdate = parent::convertClientDateToModel( $a ['createdate'] );
		if ($createdate)
			$b->setCreationDate( $createdate );

		$lastUsed = parent::convertClientDateToModel( $a ['last_used'] );
		if ($lastUsed)
			$b->setLastUsed( $lastUsed );

		$b->setAmount( $a ['amount'] );
		$b->setCapitalsource( new Capitalsource( $a ['mcs_capitalsourceid'] ) );
		$b->setContractpartner( new Contractpartner( $a ['mcp_contractpartnerid'] ) );
		$b->setComment( $a ['comment'] );
		$b->setOnceAMonth( $a ['once_a_month'] );
		return $b;
	}

	public static function mapBToA(PreDefMoneyflow $b) {
		$a ['mur_userid'] = $b->getUser()->getId();
		$a ['predefmoneyflowid'] = $b->getId();
		$a ['createdate'] = parent::convertModelDateToClient( $b->getCreationDate() );
		if ($b->getLastUsed()) {
			$a ['last_used'] = parent::convertModelDateToClient( $b->getLastUsed() );
		}
		$a ['amount'] = $b->getAmount();
		$a ['mcs_capitalsourceid'] = $b->getCapitalsource()->getId();
		$a ['capitalsourcecomment'] = $b->getCapitalsource()->getComment();
		$a ['mcp_contractpartnerid'] = $b->getContractpartner()->getId();
		$a ['contractpartnername'] = $b->getContractpartner()->getName();
		$a ['comment'] = $b->getComment();
		$a ['once_a_month'] = $b->getOnceAMonth();

		return $a;
	}
}

?>