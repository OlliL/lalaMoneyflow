<?php

//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ArrayToPreDefMoneyflowTransportMapper.php,v 1.3 2014/01/26 12:24:49 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\api\model\transport\PreDefMoneyflowTransport;

class ArrayToPreDefMoneyflowTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new PreDefMoneyflowTransport();
		$b->setId( $a ['predefmoneyflowid'] );

		$createdate = parent::convertClientDateToTransport( $a ['createdate'] );
		if ($createdate)
			$b->setCreateDate( $createdate );

		$lastUsed = parent::convertClientDateToTransport( $a ['last_used'] );
		if ($lastUsed)
			$b->setLastUsed( $lastUsed );

		$b->setAmount( $a ['amount'] );
		$b->setCapitalsourceid( $a ['mcs_capitalsourceid'] );
		$b->setContractpartnerid( $a ['mcp_contractpartnerid'] );
		$b->setComment( $a ['comment'] );
		$b->setOnceAMonth( $a ['once_a_month'] );
		return $b;
	}

	public static function mapBToA(PreDefMoneyflowTransport $b) {
		$a ['mur_userid'] = $b->getUserid();
		$a ['predefmoneyflowid'] = $b->getId();
		$a ['createdate'] = parent::convertTransportDateToClient( $b->getCreatedate() );
		if ($b->getLastUsed()) {
			$a ['last_used'] = parent::convertTransportDateToClient( $b->getLastUsed() );
		}
		$a ['amount'] = $b->getAmount();
		$a ['mcs_capitalsourceid'] = $b->getCapitalsourceid();
		$a ['capitalsourcecomment'] = $b->getCapitalsourcecomment();
		$a ['mcp_contractpartnerid'] = $b->getContractpartnerid();
		$a ['contractpartnername'] = $b->getContractpartnername();
		$a ['comment'] = $b->getComment();
		$a ['once_a_month'] = $b->getOnceAMonth();

		return $a;
	}
}

?>