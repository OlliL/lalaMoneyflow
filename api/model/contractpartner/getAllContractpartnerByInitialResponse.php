<?php

namespace rest\api\model\contractpartner;

class getAllContractpartnerByInitialResponse {
	public $contractpartnerTransport;

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(array $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}
}

?>