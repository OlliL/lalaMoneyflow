<?php

namespace rest\api\model\predefmoneyflow;

class getAllPreDefMoneyflowInitialsResponse {
	public $initials;

	public final function getInitials() {
		return $this->initials;
	}

	public final function setInitials(array $initials) {
		$this->initials = $initials;
	}
}

?>