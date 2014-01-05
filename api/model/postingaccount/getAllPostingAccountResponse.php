<?php

namespace rest\api\model\postingaccount;

class getAllPostingAccountResponse {
	public $postingAccountTransport;

	public final function getPostingAccountTransport() {
		return $this->postingAccountTransport;
	}

	public final function setPostingAccountTransport(array $postingAccountTransport) {
		$this->postingAccountTransport = $postingAccountTransport;
	}
}

?>