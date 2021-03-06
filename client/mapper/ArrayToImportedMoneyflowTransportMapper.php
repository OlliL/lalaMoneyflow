<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ArrayToImportedMoneyflowTransportMapper.php,v 1.4 2015/02/13 00:03:38 olivleh1 Exp $
//
namespace client\mapper;

use api\model\transport\ImportedMoneyflowTransport;

class ArrayToImportedMoneyflowTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new ImportedMoneyflowTransport();
		$b->setId( $a ['importedmoneyflowid'] );

		$bookingdate = parent::convertClientDateToTransport( $a ['bookingdate'] );
		if ($bookingdate)
			$b->setBookingDate( $bookingdate );

		$invoicedate = parent::convertClientDateToTransport( $a ['invoicedate'] );
		if ($invoicedate)
			$b->setInvoiceDate( $invoicedate );

		$b->setAmount( $a ['amount'] );
		$b->setCapitalsourceid( $a ['mcs_capitalsourceid'] );
		$b->setContractpartnerid( $a ['mcp_contractpartnerid'] );
		$b->setComment( $a ['comment'] );
		$b->setPostingaccountid( $a ['mpa_postingaccountid'] );
		if (array_key_exists( 'private', $a ))
			$b->setPrivate( $a ['private'] );

		$b->setAccountNumber( $a ['accountnumber'] );
		$b->setBankCode( $a ['bankcode'] );
		$b->setName( $a ['name'] );
		$b->setUsage( $a ['usage'] );

		return $b;
	}

	public static function mapBToA(ImportedMoneyflowTransport $b) {
		$a ['mur_userid'] = $b->getUserid();
		$a ['importedmoneyflowid'] = $b->getId();
		$a ['bookingdate'] = parent::convertTransportDateToClient( $b->getBookingDate() );
		$a ['invoicedate'] = parent::convertTransportDateToClient( $b->getInvoiceDate() );
		$a ['amount'] = $b->getAmount();
		$a ['mcs_capitalsourceid'] = $b->getCapitalsourceid();
		$a ['capitalsourcecomment'] = $b->getCapitalsourcecomment();
		$a ['mcp_contractpartnerid'] = $b->getContractpartnerid();
		$a ['contractpartnername'] = $b->getContractpartnername();
		$a ['comment'] = $b->getComment();
		$a ['mpa_postingaccountid'] = $b->getPostingaccountid();
		$a ['postingaccountname'] = $b->getPostingaccountname();
		$a ['private'] = $b->getPrivate();

		$a ['accountnumber'] = $b->getAccountNumber();
		$a ['bankcode'] = $b->getBankCode();
		$a ['name'] = $b->getName();
		$a ['usage'] = $b->getUsage();

		return $a;
	}
}

?>