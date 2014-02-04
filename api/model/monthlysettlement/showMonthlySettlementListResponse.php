<?php
//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: showMonthlySettlementListResponse.php,v 1.1 2014/02/04 20:43:58 olivleh1 Exp $
//
namespace rest\api\model\monthlysettlement;

class showMonthlySettlementListResponse {
	public $year;
	public $month;
	public $allYears;
	public $allMonth;
	public $monthlySettlementTransport;
	public $numberOfEditableSettlements;
	public $numberOfAddableSettlements;

	public final function getYear() {
		return $this->year;
	}

	public final function setYear($year) {
		$this->year = $year;
	}

	public final function getMonth() {
		return $this->month;
	}

	public final function setMonth($month) {
		$this->month = $month;
	}

	public final function getAllYears() {
		return $this->allYears;
	}

	public final function setAllYears(array $allYears) {
		$this->allYears = $allYears;
	}

	public final function getAllMonth() {
		return $this->allMonth;
	}

	public final function setAllMonth(array $allMonth) {
		$this->allMonth = $allMonth;
	}

	public final function getMonthlySettlementTransport() {
		return $this->monthlySettlementTransport;
	}

	public final function setMonthlySettlementTransport(array $monthlySettlementTransport) {
		$this->monthlySettlementTransport = $monthlySettlementTransport;
	}

	public final function getNumberOfEditableSettlements() {
		return $this->numberOfEditableSettlements;
	}

	public final function setNumberOfEditableSettlements($numberOfEditableSettlements) {
		$this->numberOfEditableSettlements = $numberOfEditableSettlements;
	}

	public final function getNumberOfAddableSettlements() {
		return $this->numberOfAddableSettlements;
	}

	public final function setNumberOfAddableSettlements($numberOfAddableSettlements) {
		$this->numberOfAddableSettlements = $numberOfAddableSettlements;
	}
}

?>