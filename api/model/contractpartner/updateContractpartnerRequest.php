<?php

namespace rest\api\model\contractpartner;

use rest\api\model\transport\ContractpartnerTransport;

class updateContractpartnerRequest {
	public $contractpartnerTransport;

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(ContractpartnerTransport $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}
}

?>