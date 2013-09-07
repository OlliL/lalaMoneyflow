<?php

namespace rest\api\model\moneyflow;

class createMoneyflowsRequest {
	public $moneyflowTransport;
	public $usedPreDefMoneyflowIds;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(array $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getUsedPreDefMoneyflowIds() {
		return $this->usedPreDefMoneyflowIds;
	}

	public final function setUsedPreDefMoneyflowIds(array $usedPreDefMoneyflowIds) {
		$this->usedPreDefMoneyflowIds = $usedPreDefMoneyflowIds;
	}
}

?>