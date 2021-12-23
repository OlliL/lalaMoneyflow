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
namespace api\model\etf;

class listEtfFlowsResponse {
	public $etfTransport;
	public $etfFlowTransport;
	public $etfEffectiveFlowTransport;
	public $calcEtfSaleIsin;
	public $calcEtfSalePieces;
	public $calcEtfBidPrice;
	public $calcEtfAskPrice;
	public $calcEtfTransactionCosts;
	/**
	 * @return mixed
	 */
	public final function getEtfTransport() {
		return $this->etfTransport;
	}

	/**
	 * @param mixed $etfTransport
	 */
	public final function setEtfTransport($etfTransport) {
		$this->etfTransport = $etfTransport;
	}

	/**
	 * @return mixed
	 */
	public final function getEtfEffectiveFlowTransport() {
		return $this->etfEffectiveFlowTransport;
	}

	/**
	 * @param mixed $etfTransport
	 */
	public final function setEtfEffectiveFlowTransport($etfEffectiveFlowTransport) {
		$this->etfEffectiveFlowTransport = $etfEffectiveFlowTransport;
	}

/**
	 * @return mixed
	 */
	public final function getEtfFlowTransport() {
		return $this->etfFlowTransport;
	}

	/**
	 * @param mixed $etfFlowTransport
	 */
	public final function setEtfFlowTransport($etfFlowTransport) {
		$this->etfFlowTransport = $etfFlowTransport;
	}

	/**
	 * @return mixed
	 */
	public final function getCalcEtfSaleIsin() {
		return $this->calcEtfSaleIsin;
	}

	/**
	 * @param mixed $calcEtfSaleIsin
	 */
	public final function setCalcEtfSaleIsin($calcEtfSaleIsin) {
		$this->calcEtfSaleIsin = $calcEtfSaleIsin;
	}

	/**
	 * @return mixed
	 */
	public final function getCalcEtfSalePieces() {
		return $this->calcEtfSalePieces;
	}

	/**
	 * @param mixed $calcEtfSalePieces
	 */
	public final function setCalcEtfSalePieces($calcEtfSalePieces) {
		$this->calcEtfSalePieces = $calcEtfSalePieces;
	}

	/**
	 * @return mixed
	 */
	public final function getCalcEtfBidPrice() {
		return $this->calcEtfBidPrice;
	}

	/**
	 * @param mixed $calcEtfBidPrice
	 */
	public final function setCalcEtfBidPrice($calcEtfBidPrice) {
		$this->calcEtfBidPrice = $calcEtfBidPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getCalcEtfAskPrice() {
		return $this->calcEtfAskPrice;
	}

	/**
	 * @param mixed $calcEtfAskPrice
	 */
	public final function setCalcEtfAskPrice($calcEtfAskPrice) {
		$this->calcEtfAskPrice = $calcEtfAskPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getCalcEtfTransactionCosts() {
		return $this->calcEtfTransactionCosts;
	}

	/**
	 * @param mixed $calcEtfTransactionCosts
	 */
	public final function setCalcEtfTransactionCosts($calcEtfTransactionCosts) {
		$this->calcEtfTransactionCosts = $calcEtfTransactionCosts;
	}

}

