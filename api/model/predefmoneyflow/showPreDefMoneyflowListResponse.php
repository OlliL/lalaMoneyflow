<?php

namespace rest\api\model\predefmoneyflow;

class showPreDefMoneyflowListResponse {
	public $preDefMoneyflowTransport;
	public $initials;

	public final function getPreDefMoneyflowTransport() {
		return $this->preDefMoneyflowTransport;
	}

	public final function setPreDefMoneyflowTransport(array $preDefMoneyflowTransport) {
		$this->preDefMoneyflowTransport = $preDefMoneyflowTransport;
	}

	public final function getInitials() {
		return $this->initials;
	}

	public final function setInitials(array $initials) {
		$this->initials = $initials;
	}
}

?>