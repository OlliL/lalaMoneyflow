<?php

namespace rest\api\model\moneyflow;

use rest\api\model\transport\MoneyflowTransport;

class showEditMoneyflowResponse {
	public $moneyflowTransport;
	public $capitalsourceTransport;
	public $contractpartnerTransport;
	public $postingAccountTransport;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(MoneyflowTransport $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(array $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}

	public final function getPostingAccountTransport() {
		return $this->postingAccountTransport;
	}

	public final function setPostingAccountTransport(array $postingAccountTransport) {
		$this->postingAccountTransport = $postingAccountTransport;
	}
}

?>