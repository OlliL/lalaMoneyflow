<?php

//
// Copyright (c) 2013-2016 Oliver Lehmann <oliver@laladev.org>
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
// $Id: MonthlySettlementTransport.php,v 1.7 2016/08/26 22:32:05 olivleh1 Exp $
//
namespace api\model\transport;

class MonthlySettlementTransport extends AbstractTransport {
	public $id;
	public $userid;
	public $year;
	public $month;
	public $amount;
	public $capitalsourceid;
	public $capitalsourcecomment;
	public $capitalsourcegroupuse;
	public $capitalsourcetype;

	public final function setId($id) {
		$this->id = $id;
	}

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

	public final function setCapitalsourceid($capitalsourceid) {
		$this->capitalsourceid = $capitalsourceid;
	}

	public final function setCapitalsourcecomment($capitalsourcecomment) {
		$this->capitalsourcecomment = $capitalsourcecomment;
	}

	public final function setCapitalsourcegroupuse($capitalsourcegroupuse) {
		$this->capitalsourcegroupuse = $capitalsourcegroupuse;
	}

	public final function getId() {
		return $this->id;
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

	public final function getCapitalsourceid() {
		return $this->capitalsourceid;
	}

	public final function getCapitalsourcecomment() {
		return $this->capitalsourcecomment;
	}

	public final function getCapitalsourcegroupuse() {
		return $this->capitalsourcegroupuse;
	}

	public final function setCapitalsourcetype($capitalsourcetype) {
		$this->capitalsourcetype = $capitalsourcetype;
	}

	public final function getCapitalsourcetype() {
		return $this->capitalsourcetype;
	}
}

?>