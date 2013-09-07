<?php

namespace rest\api\model\session;

class doLogonResponse {
	public $userId;
	public $userName;
	public $sessionId;

	public final function setUserId($userId) {
		$this->userId = $userId;
	}

	public final function setUserName($userName) {
		$this->userName = $userName;
	}

	public final function setSessionId($sessionId) {
		$this->sessionId = $sessionId;
	}

	public final function getUserId() {
		return $this->userId;
	}

	public final function getUserName() {
		return $this->userName;
	}

	public final function getSessionId() {
		return $this->sessionId;
	}
}

?>