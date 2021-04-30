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
namespace client\handler;

use base\Singleton;
use client\mapper\ArrayToImportedMoneyflowReceiptTransportMapper;
use api\model\transport\ImportedMoneyflowReceiptTransport;
use api\model\importedmoneyflowreceipt\createImportedMoneyflowReceiptsRequest;
use api\model\importedmoneyflowreceipt\showImportImportedMoneyflowReceiptsResponse;

class ImportedMoneyflowReceiptControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToImportedMoneyflowReceiptTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'importedmoneyflowreceipt';
	}

	public final function createImportedMoneyflowReceipts(array $files) {
		$importedMoneyflowReceiptTransports = parent::mapArray( $files, ImportedMoneyflowReceiptTransport::getClass() );

		$request = new createImportedMoneyflowReceiptsRequest();
		$request->setImportedMoneyflowReceiptTransport( $importedMoneyflowReceiptTransports );

		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function showImportImportedMoneyflowReceipts() {
		$response = parent::getJson( __FUNCTION__ );

		$result = null;
		if ($response instanceof showImportImportedMoneyflowReceiptsResponse) {
			$result ['importedmoneyflowreceipts'] = parent::mapArrayNullable( $response->getImportedMoneyflowReceiptTransport() );
		}

		return $result;
	}

	public final function deleteImportedMoneyflowReceiptById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}

	public final function importImportedMoneyflowReceipt($id, $moneyflowid) {
		return parent::postJson( __FUNCTION__, null, array (
				$id,
				$moneyflowid
		) );
	}
}

?>