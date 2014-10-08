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
// $Id: showEventListResponse.php,v 1.4 2014/10/08 19:58:23 olivleh1 Exp $
//
namespace api\model\event;

class showEventListResponse {
	public $monthlySettlementMissing;
	public $monthlySettlementMonth;
	public $monthlySettlementYear;
	public $monthlySettlementNumberOfAddableSettlements;
	public $numberOfImportedMoneyflows;

	public final function setMonthlySettlementMissing($monthlySettlementMissing) {
		$this->monthlySettlementMissing = $monthlySettlementMissing;
	}

	public final function setMonthlySettlementMonth($monthlySettlementMonth) {
		$this->monthlySettlementMonth = $monthlySettlementMonth;
	}

	public final function setMonthlySettlementYear($monthlySettlementYear) {
		$this->monthlySettlementYear = $monthlySettlementYear;
	}

	public final function isMonthlySettlementMissing() {
		return $this->monthlySettlementMissing;
	}

	public final function getMonthlySettlementMonth() {
		return $this->monthlySettlementMonth;
	}

	public final function getMonthlySettlementYear() {
		return $this->monthlySettlementYear;
	}

	public final function setMonthlySettlementNumberOfAddableSettlements($monthlySettlementNumberOfAddableSettlements) {
		$this->monthlySettlementNumberOfAddableSettlements = $monthlySettlementNumberOfAddableSettlements;
	}

	public final function getMonthlySettlementNumberOfAddableSettlements() {
		return $this->monthlySettlementNumberOfAddableSettlements;
	}

	public final function getNumberOfImportedMoneyflows() {
		return $this->numberOfImportedMoneyflows;
	}

	public final function setNumberOfImportedMoneyflows($numberOfImportedMoneyflows) {
		$this->numberOfImportedMoneyflows = $numberOfImportedMoneyflows;
	}
}

?>