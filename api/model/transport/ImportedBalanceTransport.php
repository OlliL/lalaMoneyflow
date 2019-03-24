<?php

//
// Copyright (c) 2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ImportedBalanceTransport.php,v 1.1 2015/08/15 18:14:39 olivleh1 Exp $
//
namespace api\model\transport;

class ImportedBalanceTransport {
	public $accountNumberCapitalsource;
	public $bankCodeCapitalsource;
	public $balance;

	public final function setBalance($balance) {
		$this->balance = $balance;
	}

	public final function getBalance() {
		return $this->balance;
	}

	public final function getAccountNumberCapitalsource() {
		return $this->accountNumberCapitalsource;
	}

	public final function getBankCodeCapitalsource() {
		return $this->bankCodeCapitalsource;
	}

	public final function setAccountNumberCapitalsource($accountNumberCapitalsource) {
		$this->accountNumberCapitalsource = $accountNumberCapitalsource;
	}

	public final function setBankCodeCapitalsource($bankCodeCapitalsource) {
		$this->bankCodeCapitalsource = $bankCodeCapitalsource;
	}
}

?>