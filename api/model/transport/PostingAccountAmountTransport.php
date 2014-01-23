<?php

namespace rest\api\model\transport;

class PostingAccountAmountTransport {
	public $date;
	public $amount;
	public $postingaccountid;
	public $postingaccountname;

	public final function setDate($date) {
		$this->date = $date;
	}

	public final function setAmount($amount) {
		$this->amount = $amount;
	}

	public final function getDate() {
		return $this->date;
	}

	public final function getAmount() {
		return $this->amount;
	}

	public function setPostingaccountid($postingaccountid) {
		$this->postingaccountid = $postingaccountid;
	}

	public function setPostingaccountname($postingaccountname) {
		$this->postingaccountname = $postingaccountname;
	}

	public function getPostingaccountid() {
		return $this->postingaccountid;
	}

	public function getPostingaccountname() {
		return $this->postingaccountname;
	}
}

?>