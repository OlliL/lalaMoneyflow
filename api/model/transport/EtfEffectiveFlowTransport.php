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

class EtfEffectiveFlowTransport extends AbstractTransport {
	public $etfflowid;
	public $isin;
	public $timestamp;
	public $nanoseconds;
	public $amount;
	public $price;

	/**
	 *
	 * @return mixed
	 */
	public final function getEtfflowid() {
		return $this->etfflowid;
	}

	/**
	 *
	 * @param mixed $etfflowid
	 */
	public final function setEtfflowid($etfflowid) {
		$this->etfflowid = $etfflowid;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getIsin() {
		return $this->isin;
	}

	/**
	 *
	 * @param mixed $isin
	 */
	public final function setIsin($isin) {
		$this->isin = $isin;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 *
	 * @param mixed $timestamp
	 */
	public final function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getNanoseconds() {
		return $this->nanoseconds;
	}

	/**
	 *
	 * @param mixed $nanoseconds
	 */
	public final function setNanoseconds($nanoseconds) {
		$this->nanoseconds = $nanoseconds;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getAmount() {
		return $this->amount;
	}

	/**
	 *
	 * @param mixed $amount
	 */
	public final function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getPrice() {
		return $this->price;
	}

	/**
	 *
	 * @param mixed $price
	 */
	public final function setPrice($price) {
		$this->price = $price;
	}
}

