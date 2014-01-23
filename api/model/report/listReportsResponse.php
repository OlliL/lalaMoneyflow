<?php

namespace rest\api\model\report;

class listReportsResponse {
	public $moneyflowTransport;
	public $capitalsourceTransport;
	public $year;
	public $month;
	public $allYears;
	public $allMonth;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(array $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
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
}

?>