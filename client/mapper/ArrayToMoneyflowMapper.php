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
// $Id: ArrayToMoneyflowMapper.php,v 1.4 2013/08/25 01:03:32 olivleh1 Exp $
//
namespace rest\client\mapper;

use rest\model\Moneyflow;
use rest\model\Capitalsource;
use rest\model\Contractpartner;

class ArrayToMoneyflowMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new Moneyflow( $a ['moneyflowid'] );

		$bookingdate = parent::convertClientDateToModel( $a ['bookingdate'] );
		if ($bookingdate)
			$b->setBookingDate( $bookingdate );

		$invoicedate = parent::convertClientDateToModel( $a ['invoicedate'] );
		if ($invoicedate)
			$b->setInvoiceDate( $invoicedate );

		$b->setAmount( $a ['amount'] );
		$b->setCapitalsource( new Capitalsource( $a ['mcs_capitalsourceid'] ) );
		$b->setContractpartner( new Contractpartner( $a ['mcp_contractpartnerid'] ) );
		$b->setComment( $a ['comment'] );
		$b->setPrivate( $a ['private'] );
		return $b;
	}

	public static function mapBToA(Moneyflow $b) {
		$a ['mur_userid'] = $b->getUser()->getId();
		$a ['moneyflowid'] = $b->getId();
		$a ['bookingdate'] = parent::convertModelDateToClient( $b->getBookingDate() );
		$a ['invoicedate'] = parent::convertModelDateToClient( $b->getInvoiceDate() );
		$a ['amount'] = $b->getAmount();
		$a ['mcs_capitalsourceid'] = $b->getCapitalsource()->getId();
		$a ['capitalsourcecomment'] = $b->getCapitalsource()->getComment();
		$a ['mcp_contractpartnerid'] = $b->getContractpartner()->getId();
		$a ['contractpartnername'] = $b->getContractpartner()->getName();
		$a ['comment'] = $b->getComment();
		$a ['private'] = $b->getPrivate();

		return $a;
	}
}

?>