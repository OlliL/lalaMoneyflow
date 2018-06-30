<?php
//
// Copyright (c) 2009-2018 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleEvents.php,v 1.20 2015/02/13 00:03:37 olivleh1 Exp $
//
namespace client\module;

use client\handler\EventControllerHandler;
use client\util\Environment;

class moduleEvents extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function check_events() {
		if (! Environment::getInstance()->getEventsShown()) {
			Environment::getInstance()->setEventsShown( true );
			$events = EventControllerHandler::getInstance()->showEventList();
			if ($events ['mms_missing'] === true && $events ['numberOfAddableSettlements'] > 0) {
				$this->template_assign( 'MONTH', $events ['month'] );
				$this->template_assign( 'YEAR', $events ['year'] );
				$this->template_assign( 'NUM_ADDABLE_SETTLEMENTS', $events ['numberOfAddableSettlements'] );

				$this->parse_header( 1 );
				$this->parse_header( 0, 1, 'display_event_monthlysettlement.tpl' );
				return $this->fetch_template( 'display_event_monthlysettlement.tpl' );
			}
			if ($events ['numberOfImportedMoneyflows'] > 0) {
				$this->parse_header( 0, 1, 'display_event_imported_moneyflows.tpl' );
				return $this->fetch_template( 'display_event_imported_moneyflows.tpl' );
			}
		}
		return null;
	}
}

