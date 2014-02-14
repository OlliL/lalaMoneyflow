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
// $Id: ArrayToMoneyflowSearchParamsTransportMapper.php,v 1.1 2014/02/14 22:02:52 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\api\model\transport\MoneyflowSearchParamsTransport;

class ArrayToMoneyflowSearchParamsTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new MoneyflowSearchParamsTransport();
		$b->setStartDate( parent::convertClientDateToTransport( $a ['startdate'] ) );
		$b->setEndDate( parent::convertClientDateToTransport( $a ['enddate'] ) );
		$b->setSearchString( $a ['pattern'] );
		$b->setFeatureEqual( $a ['equal'] );
		$b->setFeatureRegexp( $a ['regexp'] );
		$b->setFeatureCaseSensitive( $a ['casesensitive'] );
		$b->setFeatureOnlyMinusAmounts( $a ['minus'] );
		$b->setContractpartnerId( $a ['mcp_contractpartnerid'] );
		$b->setGroupBy1( $a ['grouping1'] );
		$b->setGroupBy2( $a ['grouping2'] );

		return $b;
	}

	public static function mapBToA(MoneyflowSearchParamsTransport $b) {
	}
}

?>