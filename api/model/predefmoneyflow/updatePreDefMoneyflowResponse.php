<?php

namespace rest\api\model\predefmoneyflow;

use rest\api\model\transport\PreDefMoneyflowTransport;
use rest\api\model\validation\validationResponse;

class updatePreDefMoneyflowResponse extends validationResponse {
	public $capitalsourceTransport;
	public $contractpartnerTransport;
	public $postingAccountTransport;

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