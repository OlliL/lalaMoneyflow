<?php

namespace rest\api\model\moneyflow;

class showAddMoneyflowsResponse {
	public $preDefMoneyflowTransport;
	public $capitalsourceTransport;
	public $contractpartnerTransport;
	public $postingAccountTransport;

	public final function getPreDefMoneyflowTransport() {
		return $this->preDefMoneyflowTransport;
	}

	public final function setPreDefMoneyflowTransport(array $preDefMoneyflowTransport) {
		$this->preDefMoneyflowTransport = $preDefMoneyflowTransport;
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