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
// $Id: ArrayToCompareDataDatasetTransportMapper.php,v 1.1 2014/02/01 21:03:25 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\api\model\transport\CompareDataDatasetTransport;

class ArrayToCompareDataDatasetTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new CompareDataDatasetTransport();
		$b->setAmount( $a ['amount'] );
		$bookingdate = parent::convertClientDateToTransport( $a ['bookingdate'] );
		if ($bookingdate)
			$b->setBookingDate( $bookingdate );
		$b->setComment( $a ['comment'] );
		$invoicedate = parent::convertClientDateToTransport( $a ['invoicedate'] );
		if ($invoicedate)
			$b->setInvoiceDate( $invoicedate );
		$b->setPartner( $a ['contractpartnername'] );
		return $b;
	}

	public static function mapBToA(CompareDataDatasetTransport $b) {
		$a ['bookingdate'] = parent::convertTransportDateToClient( $b->getBookingDate() );
		$a ['invoicedate'] = parent::convertTransportDateToClient( $b->getInvoiceDate() );
		$a ['amount'] = $b->getAmount();
		$a ['contractpartnername'] = $b->getPartner();
		$a ['comment'] = $b->getComment();
		return $a;
	}
}

?>