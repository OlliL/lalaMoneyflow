<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ContractpartnerAccountTransport.php,v 1.3 2015/02/13 00:03:39 olivleh1 Exp $
//
namespace api\model\transport;

class ContractpartnerAccountTransport extends AbstractTransport {
	public $id;
	public $contractpartnerid;
	public $accountNumber;
	public $bankCode;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setContractpartnerid($contractpartnerid) {
		$this->contractpartnerid = $contractpartnerid;
	}

	public final function setAccountNumber($accountNumber) {
		$this->accountNumber = $accountNumber;
	}

	public final function setBankCode($bankCode) {
		$this->bankCode = $bankCode;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getContractpartnerid() {
		return $this->contractpartnerid;
	}


	public final function getAccountNumber() {
		return $this->accountNumber;
	}

	public final function getBankCode() {
		return $this->bankCode;
	}
}

?>