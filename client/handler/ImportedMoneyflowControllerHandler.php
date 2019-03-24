<?php
//
// Copyright (c) 2013-2019 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ImportedMoneyflowControllerHandler.php,v 1.7 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ArrayToImportedMoneyflowTransportMapper;
use api\model\importedmoneyflow\showAddImportedMoneyflowsResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToContractpartnerTransportMapper;
use client\mapper\ArrayToPostingAccountTransportMapper;
use api\model\transport\ImportedMoneyflowTransport;
use api\model\importedmoneyflow\importImportedMoneyflowRequest;
use base\Singleton;
use api\model\transport\MoneyflowSplitEntryTransport;
use client\mapper\ArrayToMoneyflowSplitEntryTransportMapper;

class ImportedMoneyflowControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToImportedMoneyflowTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowSplitEntryTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'importedmoneyflow';
	}

	public final function showAddImportedMoneyflows() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showAddImportedMoneyflowsResponse) {
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
			$result ['contractpartner'] = parent::mapArrayNullable( $response->getContractpartnerTransport() );
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['importedmoneyflows'] = parent::mapArrayNullable( $response->getImportedMoneyflowTransport() );
		}

		return $result;
	}

	public final function deleteImportedMoneyflowById($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}

	public final function importImportedMoneyflows($imported_moneyflow, $insert_moneyflowsplitentries) {
		$importedMoneyflowTransport = parent::map( $imported_moneyflow, ImportedMoneyflowTransport::getClass() );

		$request = new importImportedMoneyflowRequest();
		$request->setImportedMoneyflowTransport( $importedMoneyflowTransport );

		if (count( $insert_moneyflowsplitentries ) > 0) {
			error_log(print_r($insert_moneyflowsplitentries, true));
			$insertMoneyflowSplitEntryTransport = parent::mapArray( $insert_moneyflowsplitentries, MoneyflowSplitEntryTransport::getClass() );
			$request->setInsertMoneyflowSplitEntryTransport( $insertMoneyflowSplitEntryTransport );
		}

		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}
}

?>