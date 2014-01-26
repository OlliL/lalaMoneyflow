<?php

namespace rest\api\model\moneyflow;

use rest\api\model\transport\MoneyflowTransport;

class showDeleteMoneyflowResponse {
	public $moneyflowTransport;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(MoneyflowTransport $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}
}

?>