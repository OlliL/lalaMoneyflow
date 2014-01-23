<?php

namespace rest\api\model\predefmoneyflow;

use rest\api\model\transport\PreDefMoneyflowTransport;

class createPreDefMoneyflowRequest {
	public $preDefMoneyflowTransport;

	public final function getPreDefMoneyflowTransport() {
		return $this->preDefMoneyflowTransport;
	}

	public final function setPreDefMoneyflowTransport(PreDefMoneyflowTransport $preDefMoneyflowTransport) {
		$this->preDefMoneyflowTransport = $preDefMoneyflowTransport;
	}
}

?>