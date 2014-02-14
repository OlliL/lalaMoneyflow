<?php

//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: MoneyflowSearchResultTransport.php,v 1.1 2014/02/14 22:02:51 olivleh1 Exp $
//
namespace rest\api\model\transport;

class MoneyflowSearchResultTransport {
	public $userid;
	public $year;
	public $month;
	public $amount;
	public $contractpartnerid;
	public $contractpartnername;
	public $comment;

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setYear($year) {
		$this->year = $year;
	}

	public final function setMonth($month) {
		$this->month = $month;
	}

	public final function setAmount($amount) {
		$this->amount = $amount;
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

	public final function getUserid() {
		return $this->userid;
	}

	public final function getYear() {
		return $this->year;
	}

	public final function getMonth() {
		return $this->month;
	}

	public final function getAmount() {
		return $this->amount;
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
}

?>