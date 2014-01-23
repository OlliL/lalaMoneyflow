<?php

namespace rest\api\model\transport;

class MoneyflowTransport {
	public $id;
	public $userid;
	public $bookingdate;
	public $invoicedate;
	public $amount;
	public $capitalsourceid;
	public $capitalsourcecomment;
	public $contractpartnerid;
	public $contractpartnername;
	public $comment;
	public $private;
	public $postingaccountid;
	public $postingaccountname;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setBookingdate($bookingdate) {
		$this->bookingdate = $bookingdate;
	}

	public final function setInvoicedate($invoicedate) {
		$this->invoicedate = $invoicedate;
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

	public final function setPrivate($private) {
		$this->private = $private;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserid() {
		return $this->userid;
	}

	public final function getBookingdate() {
		return $this->bookingdate;
	}

	public final function getInvoicedate() {
		return $this->invoicedate;
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

	public final function getPrivate() {
		return $this->private;
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