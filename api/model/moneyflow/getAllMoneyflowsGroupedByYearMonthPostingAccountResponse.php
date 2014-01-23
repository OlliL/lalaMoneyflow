<?php

namespace rest\api\model\moneyflow;

class getAllMoneyflowsGroupedByYearMonthPostingAccountResponse {
	public $postingAccountAmountTransport;

	public final function getPostingAccountAmountTransport() {
		return $this->postingAccountAmountTransport;
	}

	public final function setPostingAccountAmountTransport(array $postingAccountAmountTransport) {
		$this->postingAccountAmountTransport = $postingAccountAmountTransport;
	}
}

?>