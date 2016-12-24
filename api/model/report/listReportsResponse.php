<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: listReportsResponse.php,v 1.10 2016/12/24 12:09:26 olivleh1 Exp $
//
namespace api\model\report;

class listReportsResponse {
	public $moneyflowTransport;
	public $moneyflowSplitEntryTransport;
	public $year;
	public $month;
	public $allYears;
	public $allMonth;
	public $reportTurnoverCapitalsourceTransport;
	public $turnoverEndOfYearCalculated;
	public $amountBeginOfYear;
	public $nextMonthHasMoneyflows;
	public $previousMonthHasMoneyflows;
	public $previousMonth;
	public $previousYear;
	public $nextMonth;
	public $nextYear;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(array $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getMoneyflowSplitEntryTransport() {
		return $this->moneyflowSplitEntryTransport;
	}

	public final function setMoneyflowSplitEntryTransport(array $moneyflowSplitEntryTransport) {
		$this->moneyflowSplitEntryTransport = $moneyflowSplitEntryTransport;
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

	public final function setReportTurnoverCapitalsourceTransport(array $reportTurnoverCapitalsourceTransport) {
		$this->reportTurnoverCapitalsourceTransport = $reportTurnoverCapitalsourceTransport;
	}

	public final function getReportTurnoverCapitalsourceTransport() {
		return $this->reportTurnoverCapitalsourceTransport;
	}

	public final function setTurnoverEndOfYearCalculated($turnoverEndOfYearCalculated) {
		$this->turnoverEndOfYearCalculated = $turnoverEndOfYearCalculated;
	}

	public final function setAmountBeginOfYear($amountBeginOfYear) {
		$this->amountBeginOfYear = $amountBeginOfYear;
	}

	public final function getTurnoverEndOfYearCalculated() {
		return $this->turnoverEndOfYearCalculated;
	}

	public final function getAmountBeginOfYear() {
		return $this->amountBeginOfYear;
	}

	public final function setNextMonthHasMoneyflows($nextMonthHasMoneyflows) {
		$this->nextMonthHasMoneyflows = $nextMonthHasMoneyflows;
	}

	public final function setPreviousMonthHasMoneyflows($previousMonthHasMoneyflows) {
		$this->previousMonthHasMoneyflows = $previousMonthHasMoneyflows;
	}

	public final function getNextMonthHasMoneyflows() {
		return $this->nextMonthHasMoneyflows;
	}

	public final function getPreviousMonthHasMoneyflows() {
		return $this->previousMonthHasMoneyflows;
	}

	public final function setPreviousMonth($previousMonth) {
		$this->previousMonth = $previousMonth;
	}

	public final function setPreviousYear($previousYear) {
		$this->previousYear = $previousYear;
	}

	public final function setNextMonth($nextMonth) {
		$this->nextMonth = $nextMonth;
	}

	public final function setNextYear($nextYear) {
		$this->nextYear = $nextYear;
	}

	public final function getPreviousMonth() {
		return $this->previousMonth;
	}

	public final function getPreviousYear() {
		return $this->previousYear;
	}

	public final function getNextMonth() {
		return $this->nextMonth;
	}

	public final function getNextYear() {
		return $this->nextYear;
	}
}

?>