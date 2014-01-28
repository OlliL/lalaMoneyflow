<?php

namespace rest\api\model\transport;

class CompareDataTransport {
	public $formatId;
	public $capitalSourceId;
	public $startDate;
	public $endDate;
	public $fileContents;
	public $matchingIdPairs;
	public $wrongCapitalsourceIdPairs;
	public $onlyInFileIds;
	public $onlyInDatabaseIds;
	public $moneyflowTransport;
	// public $compareDatasetTransport;
	public final function setFormatId($formatId) {
		$this->formatId = $formatId;
	}

	public final function setCapitalSourceId($capitalSourceId) {
		$this->capitalSourceId = $capitalSourceId;
	}

	public final function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	public final function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	public final function setFileContents($fileContents) {
		$this->fileContents = $fileContents;
	}

	public final function getFormatId() {
		return $this->formatId;
	}

	public final function getCapitalSourceId() {
		return $this->capitalSourceId;
	}

	public final function getStartDate() {
		return $this->startDate;
	}

	public final function getEndDate() {
		return $this->endDate;
	}

	public final function getFileContents() {
		return $this->fileContents;
	}

	public final function setMatchingIdPairs(array $matchingIdPairs) {
		$this->matchingIdPairs = $matchingIdPairs;
	}

	public final function setWrongCapitalsourceIdPairs($wrongCapitalsourceIdPairs) {
		$this->wrongCapitalsourceIdPairs = $wrongCapitalsourceIdPairs;
	}

	public final function setOnlyInFileIds($onlyInFileIds) {
		$this->onlyInFileIds = $onlyInFileIds;
	}

	public final function setOnlyInDatabaseIds($onlyInDatabaseIds) {
		$this->onlyInDatabaseIds = $onlyInDatabaseIds;
	}

	public final function setMoneyflowTransport($moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getMatchingIdPairs() {
		return $this->matchingIdPairs;
	}

	public final function getWrongCapitalsourceIdPairs() {
		return $this->wrongCapitalsourceIdPairs;
	}

	public final function getOnlyInFileIds() {
		return $this->onlyInFileIds;
	}

	public final function getOnlyInDatabaseIds() {
		return $this->onlyInDatabaseIds;
	}

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}
}

?>