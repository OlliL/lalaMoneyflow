<?php

namespace rest\api\model\predefmoneyflow;

class getAllPreDefMoneyflowsResponse {
	public $preDefMoneyflowTransport;

	public final function getPreDefMoneyflowTransport() {
		return $this->preDefMoneyflowTransport;
	}

	public final function setPreDefMoneyflowTransport(array $preDefMoneyflowTransport) {
		$this->preDefMoneyflowTransport = $preDefMoneyflowTransport;
	}
}

?>