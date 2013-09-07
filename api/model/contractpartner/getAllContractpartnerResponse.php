<?php

namespace rest\api\model\contractpartner;

class getAllContractpartnerResponse {
	public $contractpartnerTransport;

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(array $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}
}

?>