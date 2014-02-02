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
// $Id: ArrayToMonthlySettlementTransportMapper.php,v 1.1 2014/02/02 19:09:59 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\api\model\transport\MonthlySettlementTransport;

class ArrayToMonthlySettlementTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new MonthlySettlementTransport();
		$b->setId( $a ['monthlysettlementid'] );
		$b->setAmount( $a ['amount'] );
		$b->setMovementCalculated( $a ['movement_calculated'] );
		$b->setYear( $a ['year'] );
		$b->setMonth( $a ['month'] );
		$b->setCapitalsourceid( $a ['mcs_capitalsourceid'] );
		return $b;
	}

	public static function mapBToA(MonthlySettlementTransport $b) {
		$a ['mur_userid'] = $b->getUserid();
		$a ['monthlysettlementid'] = $b->getId();
		$a ['amount'] = $b->getAmount();
		$a ['movement_calculated'] = $b->getMovementCalculated();
		$a ['year'] = $b->getYear();
		$a ['month'] = $b->getMonth();
		$a ['mcs_capitalsourceid'] = $b->getCapitalsourceid();
		$a ['capitalsourcecomment'] = $b->getCapitalsourcecomment();
		$a ['capitalsourcetype'] = $b->getCapitalsourcetype();
		$a ['capitalsourcestate'] = $b->getCapitalsourcestate();

		return $a;
	}
}

?>