<?php

namespace rest\api\model\user;

use rest\api\model\user\transport\UserTransport;

class getUserByIdResponse {
	public $userTransport;

	public final function getUserTransport() {
		return $this->userTransport;
	}

	public final function setUserTransport(UserTransport $userTransport) {
		$this->userTransport = $userTransport;
	}
}

?>