<?php

namespace rest\api\model\moneyflow;

class getAllMoneyflowsByDateRangeCapitalsourceIdResponse {
	public $moneyflowTransport;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(array $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}
}

?>