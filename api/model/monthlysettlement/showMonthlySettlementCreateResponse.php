<?php
//
// Copyright (c) 2014-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: showMonthlySettlementCreateResponse.php,v 1.4 2015/08/07 23:00:53 olivleh1 Exp $
//
namespace api\model\monthlysettlement;

class showMonthlySettlementCreateResponse {
	public $year;
	public $month;
	public $editMode;
	public $monthlySettlementTransport;
	public $importedMonthlySettlementTransport;

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

	public final function getMonthlySettlementTransport() {
		return $this->monthlySettlementTransport;
	}

	public final function setMonthlySettlementTransport(array $monthlySettlementTransport) {
		$this->monthlySettlementTransport = $monthlySettlementTransport;
	}

	public final function getImportedMonthlySettlementTransport() {
		return $this->importedMonthlySettlementTransport;
	}

	public final function setImportedMonthlySettlementTransport(array $importedMonthlySettlementTransport) {
		$this->importedMonthlySettlementTransport = $importedMonthlySettlementTransport;
	}

	public final function getEditMode() {
		return $this->editMode;
	}

	public final function setEditMode($editMode) {
		$this->editMode = $editMode;
	}
}

?>