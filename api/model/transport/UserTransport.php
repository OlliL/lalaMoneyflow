<?php

namespace rest\api\model\transport;

class UserTransport {
	public $id;
	public $userName;
	public $userPassword;
	public $userIsAdmin;
	public $userIsNew;
	public $userCanLogin;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserName($userName) {
		$this->userName = $userName;
	}

	public final function setUserPassword($userPassword) {
		$this->userPassword = $userPassword;
	}

	public final function setUserIsAdmin($userIsAdmin) {
		$this->userIsAdmin = $userIsAdmin;
	}

	public final function setUserIsNew($userIsNew) {
		$this->userIsNew = $userIsNew;
	}

	public final function setUserCanLogin($userCanLogin) {
		$this->userCanLogin = $userCanLogin;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserName() {
		return $this->userName;
	}

	public final function getUserPassword() {
		return $this->userPassword;
	}

	public final function getUserIsAdmin() {
		return $this->userIsAdmin;
	}

	public final function getUserIsNew() {
		return $this->userIsNew;
	}

	public final function getUserCanLogin() {
		return $this->userCanLogin;
	}
}

?>