<?php

//
// Copyright (c) 2021 Oliver Lehmann <lehmann@ans-netz.de>
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
namespace client\mapper;

use api\model\transport\ImportedMoneyflowReceiptTransport;

class ArrayToImportedMoneyflowReceiptTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new ImportedMoneyflowReceiptTransport();
		$b->setFilename( $a ['filename'] );
		$b->setMediaType( $a ['mediaType'] );
		$b->setReceipt( base64_encode( $a ['receipt'] ) );
		if (array_key_exists( 'id', $a ))
			$b->setId( $a ['id'] );

		return $b;
	}

	public static function mapBToA(ImportedMoneyflowReceiptTransport $b) {
		$a ['filename'] = $b->getFilename();
		$a ['mediaType'] = $b->getMediaType();
		$a ['receipt'] = base64_decode( $b->getReceipt() );
		$a ['id'] = $b->getId();

		return $a;
	}
}

?>