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
// $Id: ImportedMoneyflowControllerHandler.php,v 1.3 2014/10/25 15:17:59 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
use client\mapper\ArrayToImportedMoneyflowTransportMapper;
use api\model\importedmoneyflow\showAddImportedMoneyflowsResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToContractpartnerTransportMapper;
use client\mapper\ArrayToPostingAccountTransportMapper;

class ImportedMoneyflowControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToContractpartnerTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToImportedMoneyflowTransportMapper::getClass() );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			$className = __CLASS__;
			self::$instance = new $className();
		}
		return self::$instance;
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
}

?>