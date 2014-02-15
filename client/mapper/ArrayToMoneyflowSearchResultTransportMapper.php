<?php

//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ArrayToMoneyflowSearchResultTransportMapper.php,v 1.1 2014/02/14 22:02:52 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\api\model\transport\MoneyflowSearchResultTransport;

class ArrayToMoneyflowSearchResultTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
	}

	public static function mapBToA(MoneyflowSearchResultTransport $b) {
		$a ['mur_userid'] = $b->getUserid();
		$a ['amount'] = $b->getAmount();
		$a ['comment'] = $b->getComment();
		if ($b->getYear())
			$a ['year'] = $b->getYear();
		if ($b->getMonth())
			$a ['month'] = $b->getMonth();
		if ($b->getContractpartnername())
			$a ['name'] = $b->getContractpartnername();

		return $a;
	}
}

?>