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
// $Id: EventControllerHandler.php,v 1.12 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\event\showEventListResponse;
use base\Singleton;

class EventControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
	}

	protected final function getCategory() {
		return 'event';
	}

	public final function showEventList() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showEventListResponse) {
			$result ['mms_missing'] = $response->isMonthlySettlementMissing();
			$result ['month'] = $response->getMonthlySettlementMonth();
			$result ['year'] = $response->getMonthlySettlementYear();
			$result ['numberOfAddableSettlements'] = $response->getMonthlySettlementNumberOfAddableSettlements();
			$result ['numberOfImportedMoneyflows'] = $response->getNumberOfImportedMoneyflows();
		}
		return $result;
	}
}

?>