<?php

//
// Copyright (c) 2014-2016 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: showEditMoneyflowResponse.php,v 1.6 2016/12/30 18:34:53 olivleh1 Exp $
//
namespace api\model\moneyflow;

use api\model\transport\MoneyflowTransport;

class showEditMoneyflowResponse implements IshowEditMoneyflowsResponse {
	public $moneyflowTransport;
	public $moneyflowSplitEntryTransport;
	public $capitalsourceTransport;
	public $contractpartnerTransport;
	public $postingAccountTransport;
	public $hasReceipt;

	public final function getMoneyflowTransport() {
		return $this->moneyflowTransport;
	}

	public final function setMoneyflowTransport(MoneyflowTransport $moneyflowTransport) {
		$this->moneyflowTransport = $moneyflowTransport;
	}

	public final function getMoneyflowSplitEntryTransport() {
		return $this->moneyflowSplitEntryTransport;
	}

	public final function setMoneyflowSplitEntryTransport(array $moneyflowSplitEntryTransport) {
		$this->moneyflowSplitEntryTransport = $moneyflowSplitEntryTransport;
	}

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}

	public final function getContractpartnerTransport() {
		return $this->contractpartnerTransport;
	}

	public final function setContractpartnerTransport(array $contractpartnerTransport) {
		$this->contractpartnerTransport = $contractpartnerTransport;
	}

	public final function getPostingAccountTransport() {
		return $this->postingAccountTransport;
	}

	public final function setPostingAccountTransport(array $postingAccountTransport) {
		$this->postingAccountTransport = $postingAccountTransport;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getHasReceipt() {
		return $this->hasReceipt;
	}

	/**
	 *
	 * @param mixed $hasReceipt
	 */
	public final function setHasReceipt($hasReceipt) {
		$this->hasReceipt = $hasReceipt;
	}
}

?>