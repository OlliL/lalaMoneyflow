<?php
//
// Copyright (c) 2017-2021 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: MoneyflowReceiptControllerHandler.php,v 1.1 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client\handler;

use base\Singleton;
use api\model\moneyflowreceipt\showMoneyflowReceiptResponse;

class MoneyflowReceiptControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
	}

	protected final function getCategory() {
		return 'moneyflowreceipt';
	}

	public final function showMoneyflowReceipt($id) {
		$response = parent::getJson( __FUNCTION__, array (
				$id
		) );
		$result = null;
		if ($response instanceof showMoneyflowReceiptResponse) {
			$result['receipt'] = $response->getReceipt();
			$result['receipt_type'] = $response->getReceiptType();
		}

		return $result;
	}

	public final function deleteMoneyflowReceipt($id) {
		return parent::deleteJson( __FUNCTION__, array (
				$id
		) );
	}

}

?>