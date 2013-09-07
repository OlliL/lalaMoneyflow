<?php

namespace rest\api\model\moneyflow;

use rest\api\model\moneyflow\transport\MoneyflowTransport;

class getMoneyflowByIdResponse {
	public $moneyflowTransport;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(MoneyflowTransport $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}
}

?>