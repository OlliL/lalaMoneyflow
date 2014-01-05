<?php

namespace rest\api\model\postingaccount\transport;

class PostingAccountTransport {
	public $id;
	public $userid;
	public $name;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setName($name) {
		$this->name = $name;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserid() {
		return $this->userid;
	}

	public final function getName() {
		return $this->name;
	}
}

?>