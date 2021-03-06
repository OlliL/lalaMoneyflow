<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: PreDefMoneyflowTransport.php,v 1.6 2015/02/13 00:03:39 olivleh1 Exp $
//
namespace api\model\transport;

class PreDefMoneyflowTransport extends AbstractTransport {
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
	public $postingaccountid;
	public $postingaccountname;

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

	public final function setPostingaccountid($postingaccountid) {
		$this->postingaccountid = $postingaccountid;
	}

	public final function setPostingaccountname($postingaccountname) {
		$this->postingaccountname = $postingaccountname;
	}

	public final function getPostingaccountid() {
		return $this->postingaccountid;
	}

	public final function getPostingaccountname() {
		return $this->postingaccountname;
	}
}

?>