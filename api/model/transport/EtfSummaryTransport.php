<?php

//
// Copyright (c) 2021 Oliver Lehmann <lehmann@ans-netz.de>
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

namespace api\model\transport;

class EtfSummaryTransport extends AbstractTransport {
	public $isin;
	public $name;
	public $chartUrl;
	public $amount;
	public $spentValue;
	public $buyPrice;
	public $sellPrice;
	public $pricesTimestamp;

	/**
	 * @return mixed
	 */
	public final function getIsin() {
		return $this->isin;
	}

	/**
	 * @param mixed $isin
	 */
	public final function setIsin($isin) {
		$this->isin = $isin;
	}

	/**
	 * @return mixed
	 */
	public final function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public final function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public final function getChartUrl() {
		return $this->chartUrl;
	}

	/**
	 * @param mixed $chartUrl
	 */
	public final function setChartUrl($chartUrl) {
		$this->chartUrl = $chartUrl;
	}

	/**
	 * @return mixed
	 */
	public final function getAmount() {
		return $this->amount;
	}

	/**
	 * @param mixed $amount
	 */
	public final function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 * @return mixed
	 */
	public final function getSpentValue() {
		return $this->spentValue;
	}

	/**
	 * @param mixed $spentValue
	 */
	public final function setSpentValue($spentValue) {
		$this->spentValue = $spentValue;
	}

	/**
	 * @return mixed
	 */
	public final function getBuyPrice() {
		return $this->buyPrice;
	}

	/**
	 * @param mixed $buyPrice
	 */
	public final function setBuyPrice($buyPrice) {
		$this->buyPrice = $buyPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getSellPrice() {
		return $this->sellPrice;
	}

	/**
	 * @param mixed $sellPrice
	 */
	public final function setSellPrice($sellPrice) {
		$this->sellPrice = $sellPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getPricesTimestamp() {
		return $this->pricesTimestamp;
	}

	/**
	 * @param mixed $pricesTimestamp
	 */
	public final function setPricesTimestamp($pricesTimestamp) {
		$this->pricesTimestamp = $pricesTimestamp;
	}

}