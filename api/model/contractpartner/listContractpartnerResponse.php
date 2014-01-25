<?php

namespace rest\api\model\contractpartner;

class listContractpartnerResponse {
	public $contractpartnerTransport;
	public $initials;

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(array $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}

	public final function getInitials() {
		return $this->initials;
	}

	public final function setInitials(array $initials) {
		$this->initials = $initials;
	}
}

?>