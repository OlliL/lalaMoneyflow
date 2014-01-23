<?php

namespace rest\api\model\transport;

class PreDefMoneyflowTransport {
	public $id;
	public $userid;
	public $amount;
	public $capitalsourceid;
	public $capitalsourcecomment;
	public $contractpartnerid;
	public $contractpartnername;
	public $comment;
	public $createdate;
	public $onceAMonth;
	public $lastUsed;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setAmount($amount) {
		$this->amount = $amount;
	}

	public final function setCapitalsourceid($capitalsourceid) {
		$this->capitalsourceid = $capitalsourceid;
	}

	public final function setCapitalsourcecomment($capitalsourcecomment) {
		$this->capitalsourcecomment = $capitalsourcecomment;
	}

	public final function setContractpartnerid($contractpartnerid) {
		$this->contractpartnerid = $contractpartnerid;
	}

	public final function setContractpartnername($contractpartnername) {
		$this->contractpartnername = $contractpartnername;
	}

	public final function setComment($comment) {
		$this->comment = $comment;
	}

	public final function setCreatedate($createdate) {
		$this->createdate = $createdate;
	}

	public final function setOnceAMonth($onceAMonth) {
		$this->onceAMonth = $onceAMonth;
	}

	public final function setLastUsed($lastUsed) {
		$this->lastUsed = $lastUsed;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserid() {
		return $this->userid;
	}

	public final function getAmount() {
		return $this->amount;
	}

	public final function getCapitalsourceid() {
		return $this->capitalsourceid;
	}

	public final function getCapitalsourcecomment() {
		return $this->capitalsourcecomment;
	}

	public final function getContractpartnerid() {
		return $this->contractpartnerid;
	}

	public final function getContractpartnername() {
		return $this->contractpartnername;
	}

	public final function getComment() {
		return $this->comment;
	}

	public final function getCreatedate() {
		return $this->createdate;
	}

	public final function getOnceAMonth() {
		return $this->onceAMonth;
	}

	public final function getLastUsed() {
		return $this->lastUsed;
	}
}

?>